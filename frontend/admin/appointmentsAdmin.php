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

// Récupérer tous les rendez-vous depuis history_user
$query = "SELECT h.*, u.nom, u.prenom
        FROM history_user h 
        LEFT JOIN users u ON h.id_user = u.id 
        WHERE h.date IS NOT NULL 
        ORDER BY h.date";
$stmt = $pdo->prepare($query);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les événements pour le calendrier
$calendarEvents = [];

// Ajouter les rendez-vous
foreach ($appointments as $appointment) {
    if ($appointment['date']) {
        // Déterminer le type d'événement pour l'affichage
        $eventType = '';
        $className = '';
        switch ($appointment['event_type']) {
            case 'atelier_ecriture':
                $eventType = 'Atelier d\'écriture';
                $className = 'writing-workshop';
                break;
            case 'atelier_equite':
                $eventType = 'Atelier d\'équité';
                $className = 'equity-workshop';
                break;
            case 'coaching':
                $eventType = 'Coaching';
                $className = 'coaching';
                break;
            default:
                $eventType = 'Rendez-vous';
                $className = 'appointment';
        }
        
        // Créer le titre de l'événement
        $title = $eventType;
        if (!empty($appointment['nom']) && !empty($appointment['prenom'])) {
            $title .= ' - ' . $appointment['prenom'] . ' ' . $appointment['nom'];
        }
        
        // Utiliser la date et heure
        $start = date('Y-m-d\TH:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_debut']));
        // Ajouter une heure par défaut pour la fin si non spécifiée
        $end = !empty($appointment['heure_fin']) 
            ? date('Y-m-d\TH:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_fin']))
            : date('Y-m-d\TH:i:s', strtotime($appointment['date'] . ' ' . $appointment['heure_debut'] . ' +1 hour'));
        
        $calendarEvents[] = [
            'id' => $appointment['id'],
            'title' => $title,
            'start' => $start,
            'end' => $end,
            'className' => $className,
            'extendedProps' => [
                'type' => $appointment['event_type'],
                'id_user' => $appointment['id_user']
            ]
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
    <title>Administration Rendez-vous - La Ligne 13</title>
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
        .fc-event.coaching {
            background-color: #330c59;
            border-color: #330c59;
            color: white;
        }
        .fc-event.writing-workshop {
            background-color: #e4c9e5;
            border-color: #e4c9e5;
            color: #330c59;
        }
        .fc-event.equity-workshop {
            background-color: #f9a8c9;
            border-color: #f9a8c9;
            color: #330c59;
        }
        .fc-event.appointment {
            background-color: #ffeb5b;
            border-color: #ffeb5b;
            color: #333333;
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
                            <a href="appointmentsAdmin.php" class="flex items-center px-4 py-3 rounded-lg bg-violet/80 transition-colors">
                                <i class="fas fa-calendar-alt w-6"></i>
                                <span class="ml-3">Rendez-vous</span>
                            </a>
                        </li>
                        <li>
                            <a href="availabilityAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
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
                <a href="../index.php" class="flex items-center text-white hover:text-jaune transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Retour au site</span>
                </a>
                <a href="../logout.php" class="flex items-center text-white hover:text-jaune transition-colors mt-4">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-violet">Gestion des Rendez-vous</h1>
                    <p class="text-gray-600 mt-2">Consultez et gérez les rendez-vous des clients</p>
                </div>
                <button class="bg-violet text-white px-4 py-2 rounded-lg hover:bg-violet/90 transition-colors flex items-center" id="newAppointmentBtn">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau rendez-vous
                </button>
            </div>
            
            <!-- New Appointment Form (Hidden by default) -->
            <div id="newAppointmentForm" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden">
                <h2 class="text-xl font-bold text-violet mb-4">Créer un nouveau rendez-vous</h2>
                
                <form action="../../backend/generators/AppointmentGenerator.php" method="post" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="client" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                            <select id="client" name="id_user" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                <option value="">Sélectionner un client</option>
                                <?php
                                // Récupérer tous les utilisateurs
                                $query = "SELECT id, nom, prenom FROM users ORDER BY nom, prenom";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute();
                                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                foreach ($users as $user) {
                                    echo '<option value="' . $user['id'] . '">' . $user['prenom'] . ' ' . $user['nom'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="event_type" class="block text-sm font-medium text-gray-700 mb-1">Type d'événement</label>
                            <select id="event_type" name="event_type" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                <option value="">Sélectionner un type</option>
                                <option value="coaching">Coaching</option>
                                <option value="atelier_ecriture">Atelier d'écriture</option>
                                <option value="atelier_equite">Atelier d'équité</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="event_id_container" class="hidden">
                        <label for="id_event" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner l'événement</label>
                        <select id="id_event" name="id_event" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <option value="">Sélectionner un événement</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                            <input type="time" id="heure_debut" name="heure_debut" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                            <input type="time" id="heure_fin" name="heure_fin" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                        <input type="number" id="prix" name="prix" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-violet text-white rounded-md hover:bg-violet/90 transition-colors">
                            Créer
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Appointments Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Calendar -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div id="calendar-appointments" style="max-width: 100%; overflow: hidden;"></div>
                </div>

                <!-- Appointments List -->
                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                        <h3 class="font-semibold text-violet mb-2">Rendez-vous à venir</h3>
                        <div class="relative">
                            <input type="text" id="search-appointments" placeholder="Rechercher un rendez-vous..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-3 max-h-[500px] overflow-y-auto" id="appointments-list">
                        <?php if (empty($appointments)): ?>
                            <div class="text-center py-8">
                                <p class="text-gray-500">Aucun rendez-vous trouvé.</p>
                            </div>
                        <?php else: ?>
                            <?php 
                            $upcomingAppointments = array_filter($appointments, function($appointment) {
                                return !empty($appointment['date']) && strtotime($appointment['date']) >= strtotime('today');
                            });
                            
                            usort($upcomingAppointments, function($a, $b) {
                                return strtotime($a['date']) - strtotime($b['date']);
                            });
                            
                            foreach ($upcomingAppointments as $appointment): 
                                // Déterminer le type d'événement pour l'affichage
                                $eventType = '';
                                $bgColor = '';
                                switch ($appointment['event_type']) {
                                    case 'atelier_ecriture':
                                        $eventType = 'Atelier d\'écriture';
                                        $bgColor = 'bg-mauve text-violet';
                                        break;
                                    case 'atelier_equite':
                                        $eventType = 'Atelier d\'équité';
                                        $bgColor = 'bg-rose text-violet';
                                        break;
                                    case 'coaching':
                                        $eventType = 'Coaching';
                                        $bgColor = 'bg-violet text-white';
                                        break;
                                    default:
                                        $eventType = 'Rendez-vous';
                                        $bgColor = 'bg-jaune text-darkgray';
                                }
                            ?>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center">
                                                <h4 class="font-semibold text-violet">
                                                    <?= !empty($appointment['prenom']) && !empty($appointment['nom']) ? htmlspecialchars($appointment['prenom'] . ' ' . $appointment['nom']) : 'Client #' . $appointment['id_user']; ?>
                                                </h4>
                                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full <?= $bgColor ?>">
                                                    <?= $eventType ?>
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                <?= date('d/m/Y', strtotime($appointment['date'])) ?>
                                                <?php if (!empty($appointment['heure_debut'])): ?>
                                                    - <?= substr($appointment['heure_debut'], 0, 5) ?>
                                                <?php endif; ?>
                                                <?php if (!empty($appointment['heure_fin'])): ?>
                                                    à <?= substr($appointment['heure_fin'], 0, 5) ?>
                                                <?php endif; ?>
                                            </p>
                                            <?php if (!empty($appointment['prix'])): ?>
                                                <p class="text-sm text-gray-500">Prix: <?= number_format($appointment['prix'], 2, ',', ' ') ?> €</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-full edit-appointment" data-id="<?= $appointment['id'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-full delete-appointment" data-id="<?= $appointment['id'] ?>">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <h3 class="font-semibold text-violet text-lg mb-4">Légende</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-violet rounded mr-2"></div>
                                <span>Coaching</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-mauve rounded mr-2"></div>
                                <span>Atelier d'écriture</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-rose rounded mr-2"></div>
                                <span>Atelier d'équité</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-jaune rounded mr-2"></div>
                                <span>Autre rendez-vous</span>
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
            <h3 class="text-xl font-bold text-violet mb-4">Confirmer l'annulation</h3>
            <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir annuler ce rendez-vous ? Cette action est irréversible.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Retour
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Annuler le rendez-vous
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Calendar
            const calendarEl = document.getElementById('calendar-appointments');
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
                eventClick: function(info) {
                    alert('Rendez-vous: ' + info.event.title);
                },
                windowResize: function() {
                    calendar.updateSize(); // Mettre à jour la taille lors du redimensionnement
                }
            });
            calendar.render();
            
            // New Appointment Form Toggle
            const newAppointmentBtn = document.getElementById('newAppointmentBtn');
            const newAppointmentForm = document.getElementById('newAppointmentForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            newAppointmentBtn.addEventListener('click', function() {
                newAppointmentForm.classList.remove('hidden');
                newAppointmentBtn.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', function() {
                newAppointmentForm.classList.add('hidden');
                newAppointmentBtn.classList.remove('hidden');
            });
            
            // Event Type Change Handler
            const eventTypeSelect = document.getElementById('event_type');
            const eventIdContainer = document.getElementById('event_id_container');
            const eventIdSelect = document.getElementById('id_event');
            
            eventTypeSelect.addEventListener('change', function() {
                const eventType = this.value;
                if (eventType) {
                    // Afficher le conteneur de sélection d'événement
                    eventIdContainer.classList.remove('hidden');
                    
                    // Vider le select
                    eventIdSelect.innerHTML = '<option value="">Sélectionner un événement</option>';
                    
                    // Charger les événements correspondants
                    let table = '';
                    switch (eventType) {
                        case 'coaching':
                            table = 'coaching';
                            break;
                        case 'atelier_ecriture':
                            table = 'atelier_ecriture';
                            break;
                        case 'atelier_equite':
                            table = 'atelier_equite';
                            break;
                    }
                    
                    if (table) {
                        // Faire une requête AJAX pour récupérer les événements
                        fetch(`../../backend/api/get_events.php?table=${table}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    data.events.forEach(event => {
                                        const option = document.createElement('option');
                                        option.value = event.id;
                                        option.textContent = event.title || `Événement #${event.id}`;
                                        eventIdSelect.appendChild(option);
                                    });
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                } else {
                    eventIdContainer.classList.add('hidden');
                }
            });
            
            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-appointment');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let appointmentIdToDelete = null;
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    appointmentIdToDelete = this.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                appointmentIdToDelete = null;
            });
            
            confirmDelete.addEventListener('click', function() {
                if (appointmentIdToDelete) {
                    // Send delete request to server
                    fetch('../../backend/generators/AppointmentDelete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: appointmentIdToDelete
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
            
            // Search functionality
            const searchInput = document.getElementById('search-appointments');
            const appointmentsList = document.getElementById('appointments-list');
            const appointmentCards = appointmentsList.querySelectorAll('div[class*="bg-white p-4 rounded-lg border"]');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                appointmentCards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>