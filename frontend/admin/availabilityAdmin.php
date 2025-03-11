<?php
session_start();
require("../../backend/utils/ConnectToBDD.php");

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas un administrateur
    // header('Location: ../login.php');
    // exit;
    
    // Pour le développement, nous ne redirigeons pas
}

// Récupérer toutes les indisponibilités
$query = "SELECT * FROM indisponibilites ORDER BY date_debut";
$stmt = $pdo->prepare($query);
$stmt->execute();
$unavailabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les rendez-vous
$query_appointments = "SELECT h.*, u.nom, u.prenom
        FROM history_user h 
        LEFT JOIN users u ON h.id_user = u.id 
        WHERE h.date IS NOT NULL 
        ORDER BY h.date";
$stmt_appointments = $pdo->prepare($query_appointments);
$stmt_appointments->execute();
$appointments = $stmt_appointments->fetchAll(PDO::FETCH_ASSOC);

// Préparer les événements pour le calendrier
$calendarEvents = [];

// Ajouter les plages horaires par défaut (8h-12h et 14h-18h) pour les 3 prochains mois
$start = new DateTime();
$end = (new DateTime())->modify('+3 months');
$interval = new DateInterval('P1D');
$period = new DatePeriod($start, $interval, $end);

// Fonction pour vérifier si une plage horaire spécifique est en conflit
function isTimeSlotUnavailable($dateTime, $endDateTime, $unavailabilities, $appointments) {
    // Vérifier les indisponibilités
    foreach ($unavailabilities as $unavailability) {
        $unavailStartDateTime = new DateTime($unavailability['date_debut']);
        $unavailEndDateTime = new DateTime($unavailability['date_fin']);
        
        // Vérifier si le créneau chevauche l'indisponibilité
        if (
            ($dateTime >= $unavailStartDateTime && $dateTime < $unavailEndDateTime) ||
            ($endDateTime > $unavailStartDateTime && $endDateTime <= $unavailEndDateTime) ||
            ($dateTime <= $unavailStartDateTime && $endDateTime >= $unavailEndDateTime)
        ) {
            return true;
        }
    }
    
    // Vérifier les rendez-vous
    foreach ($appointments as $appointment) {
        if ($appointment['date'] === $dateTime->format('Y-m-d')) {
            $appointmentStart = new DateTime($appointment['date'] . ' ' . $appointment['heure_debut']);
            $appointmentEnd = new DateTime($appointment['date'] . ' ' . ($appointment['heure_fin'] ?? date('H:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_debut'] . ' +1 hour'))));
            
            // Vérifier si le créneau chevauche le rendez-vous
            if (
                ($dateTime >= $appointmentStart && $dateTime < $appointmentEnd) ||
                ($endDateTime > $appointmentStart && $endDateTime <= $appointmentEnd) ||
                ($dateTime <= $appointmentStart && $endDateTime >= $appointmentEnd)
            ) {
                return true;
            }
        }
    }
    
    return false;
}

// Fonction pour obtenir les créneaux disponibles pour une période donnée
function getAvailableSlots($date, $startTime, $endTime, $unavailabilities, $appointments) {
    $slots = [];
    $currentTime = new DateTime($date->format('Y-m-d') . ' ' . $startTime);
    $endDateTime = new DateTime($date->format('Y-m-d') . ' ' . $endTime);
    $interval = new DateInterval('PT30M'); // Intervalle de 30 minutes
    
    while ($currentTime < $endDateTime) {
        $slotEnd = clone $currentTime;
        $slotEnd->add($interval);
        
        if ($slotEnd > $endDateTime) {
            $slotEnd = clone $endDateTime;
        }
        
        if (!isTimeSlotUnavailable($currentTime, $slotEnd, $unavailabilities, $appointments)) {
            // Si le créneau précédent existe et est disponible, on fusionne
            if (!empty($slots)) {
                $lastSlot = end($slots);
                if ($lastSlot['end'] === $currentTime->format('Y-m-d\TH:i:s')) {
                    $slots[key($slots)]['end'] = $slotEnd->format('Y-m-d\TH:i:s');
                } else {
                    $slots[] = [
                        'title' => 'Disponible',
                        'start' => $currentTime->format('Y-m-d\TH:i:s'),
                        'end' => $slotEnd->format('Y-m-d\TH:i:s'),
                        'className' => 'available'
                    ];
                }
            } else {
                $slots[] = [
                    'title' => 'Disponible',
                    'start' => $currentTime->format('Y-m-d\TH:i:s'),
                    'end' => $slotEnd->format('Y-m-d\TH:i:s'),
                    'className' => 'available'
                ];
            }
        }
        
        $currentTime = clone $slotEnd;
    }
    
    return $slots;
}

foreach ($period as $date) {
    // Skip weekends
    if ($date->format('N') >= 6) continue;
    
    // Ajouter les créneaux disponibles du matin (8h-12h)
    $morningSlots = getAvailableSlots($date, '08:00:00', '12:00:00', $unavailabilities, $appointments);
    $calendarEvents = array_merge($calendarEvents, $morningSlots);
    
    // Ajouter les créneaux disponibles de l'après-midi (14h-18h)
    $afternoonSlots = getAvailableSlots($date, '14:00:00', '18:00:00', $unavailabilities, $appointments);
    $calendarEvents = array_merge($calendarEvents, $afternoonSlots);
}

// Ajouter les indisponibilités
foreach ($unavailabilities as $unavailability) {
    $calendarEvents[] = [
        'id' => 'unavail_' . $unavailability['id'],
        'title' => 'Indisponible' . ($unavailability['motif'] ? ' - ' . $unavailability['motif'] : ''),
        'start' => $unavailability['date_debut'],
        'end' => $unavailability['date_fin'],
        'className' => 'unavailable'
    ];
}

// Ajouter les rendez-vous
foreach ($appointments as $appointment) {
    if ($appointment['date']) {
        $start = date('Y-m-d\TH:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_debut']));
        $end = !empty($appointment['heure_fin']) 
            ? date('Y-m-d\TH:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_fin']))
            : date('Y-m-d\TH:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_debut'] . ' +1 hour'));
        
        $calendarEvents[] = [
            'id' => 'appt_' . $appointment['id'],
            'title' => 'Rendez-vous - ' . ($appointment['prenom'] ? $appointment['prenom'] . ' ' . $appointment['nom'] : 'Client #' . $appointment['id_user']),
            'start' => $start,
            'end' => $end,
            'className' => 'appointment'
        ];
    }
}

// Convertir les événements en JSON pour le calendrier
$calendarEventsJson = json_encode($calendarEvents);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Disponibilités - La Ligne 13</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: '#330c59',
                        jaune: '#ffeb5b',
                        mauve: '#e4c9e5',
                        rose: '#f9a8c9',
                        lightgray: '#f5f5f5',
                        darkgray: '#333333',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        
        /* Animation classes */
        .animate-hidden {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .animate-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Calendar styling */
        .fc-theme-standard .fc-scrollgrid {
            border-color: #e5e7eb;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(227, 201, 229, 0.3);
        }
        .fc .fc-button-primary {
            background-color: #330c59;
            border-color: #330c59;
        }
        .fc .fc-button-primary:hover {
            background-color: #4b1683;
            border-color: #4b1683;
        }
        .fc-event.available {
            background-color: #10b981;
            border-color: #10b981;
            color: white;
        }
        .fc-event.unavailable {
            background-color: #ef4444;
            border-color: #ef4444;
            color: white;
        }
        .fc-event.appointment {
            background-color: #330c59;
            border-color: #330c59;
            color: white;
        }
        
        /* Correction pour que le calendrier reste dans sa div */
        .fc-view-harness {
            height: 500px !important; /* Hauteur fixe */
        }
        .fc-scroller {
            overflow-y: auto !important;
        }
        .fc-dayGridMonth-view .fc-daygrid-body {
            max-height: 450px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-lightgray text-darkgray">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-violet text-white fixed h-full overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-center mb-8">
                    <img src="../../src/img/logo.jpg" alt="Logo Ligne 13" class="h-12 w-auto bg-white rounded-full p-1">
                    <h1 class="text-xl font-bold ml-3">Administration</h1>
                </div>
                
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="../admin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-tachometer-alt w-6"></i>
                                <span class="ml-3">Tableau de bord</span>
                            </a>
                        </li>
                        <li>
                            <a href="blogAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-blog w-6"></i>
                                <span class="ml-3">Blog</span>
                            </a>
                        </li>
                        <li>
                            <a href="coachingAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-chalkboard-teacher w-6"></i>
                                <span class="ml-3">Coaching</span>
                            </a>
                        </li>
                        <li>
                            <a href="writingAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-pen-fancy w-6"></i>
                                <span class="ml-3">Ateliers d'écriture</span>
                            </a>
                        </li>
                        <li>
                            <a href="equityAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-balance-scale w-6"></i>
                                <span class="ml-3">Ateliers d'équité</span>
                            </a>
                        </li>
                        <li>
                            <a href="usersAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-users w-6"></i>
                                <span class="ml-3">Utilisateurs</span>
                            </a>
                        </li>
                        <li>
                            <a href="appointmentsAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-calendar-alt w-6"></i>
                                <span class="ml-3">Rendez-vous</span>
                            </a>
                        </li>
                        <li>
                            <a href="availabilityAdmin.php" class="flex items-center px-4 py-3 rounded-lg bg-violet/80 transition-colors">
                                <i class="fas fa-clock w-6"></i>
                                <span class="ml-3">Disponibilités</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-cog w-6"></i>
                                <span class="ml-3">Paramètres</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <div class="p-6 border-t border-violet/30 mt-6">
            <a href="../frontend/index.php" class="flex items-center text-white hover:text-jaune transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Retour au site</span>
                </a>
                <a href="../backend/DisconnectUser.php" class="flex items-center text-white hover:text-jaune transition-colors mt-4">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-violet">Gestion des Disponibilités</h1>
                    <p class="text-gray-600 mt-2">Définissez vos périodes d'indisponibilité</p>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Calendar -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div id="calendar-availability" style="max-width: 100%; overflow: hidden;"></div>
                </div>

                <!-- Quick actions -->
                <div class="space-y-4">
                    <div class="bg-violet bg-opacity-10 p-6 rounded-lg shadow-md">
                        <h3 class="font-semibold text-violet text-lg mb-4">Ajouter une indisponibilité</h3>
                        <form action="../../backend/generators/UnavailabilityGenerator.php" method="post" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                    <input type="date" id="date_debut" name="date_debut" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                    <input type="date" id="date_fin" name="date_fin" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                                    <input type="time" id="heure_debut" name="heure_debut" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                                    <input type="time" id="heure_fin" name="heure_fin" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                </div>
                            </div>
                            
                            <div>
                                <label for="motif" class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                                <input type="text" id="motif" name="motif" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            </div>
                            
                            <button type="submit" class="w-full bg-violet text-white py-2 rounded-md hover:bg-violet/90 transition-colors">
                                Ajouter
                            </button>
                        </form>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <h3 class="font-semibold text-violet text-lg mb-4">Indisponibilités à venir</h3>
                        <div class="space-y-2 max-h-[300px] overflow-y-auto">
                            <?php if (empty($unavailabilities)): ?>
                                <p class="text-gray-500 text-center py-4">Aucune indisponibilité programmée</p>
                            <?php else: ?>
                                <?php foreach ($unavailabilities as $unavailability): ?>
                                    <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-md">
                                        <div>
                                            <p class="font-medium">
                                                <?= (new DateTime($unavailability['date_debut']))->format('d/m/Y') ?>
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <?= (new DateTime($unavailability['date_debut']))->format('H:i') ?> - 
                                                <?= (new DateTime($unavailability['date_fin']))->format('H:i') ?>
                                            </p>
                                            <?php if (!empty($unavailability['motif'])): ?>
                                                <p class="text-sm text-gray-500"><?= htmlspecialchars($unavailability['motif']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <button class="text-red-500 hover:text-red-700 delete-unavailability" data-id="<?= $unavailability['id'] ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <h3 class="font-semibold text-violet text-lg mb-4">Légende</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-[#10b981] rounded mr-2"></div>
                                <span>Disponible</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-[#ef4444] rounded mr-2"></div>
                                <span>Indisponible</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-[#330c59] rounded mr-2"></div>
                                <span>Rendez-vous</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-xl font-bold text-violet mb-4">Confirmer la suppression</h3>
            <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer cette période d'indisponibilité ?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Calendar
            const calendarEl = document.getElementById('calendar-availability');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '08:00:00',
                slotMaxTime: '19:00:00',
                events: <?= $calendarEventsJson ?>,
                locale: 'fr',
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
                },
                allDaySlot: false,
                slotDuration: '00:30:00',
                expandRows: true,
                height: 500, // Hauteur fixe pour le calendrier
                contentHeight: 500, // Hauteur fixe pour le contenu
                aspectRatio: 1.5, // Rapport largeur/hauteur
                eventOverlap: false,
                eventClick: function(info) {
                    if (info.event.classNames.includes('unavailable')) {
                        const id = info.event.id;
                        if (id) {
                            unavailabilityIdToDelete = id.replace('unavail_', '');
                            deleteModal.classList.remove('hidden');
                        }
                    }
                },
                windowResize: function() {
                    calendar.updateSize(); // Mettre à jour la taille lors du redimensionnement
                }
            });
            calendar.render();
            
            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-unavailability');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let unavailabilityIdToDelete = null;
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    unavailabilityIdToDelete = this.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                unavailabilityIdToDelete = null;
            });
            
            confirmDelete.addEventListener('click', function() {
                if (unavailabilityIdToDelete) {
                    // Send delete request to server
                    fetch('../../backend/generators/UnavailabilityDelete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: unavailabilityIdToDelete
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la suppression');
                    });
                    
                    deleteModal.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>