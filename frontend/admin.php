<?php

session_start();


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
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
        .fc {
            max-width: 100%;
            height: auto !important;
        }
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 0.5rem;
        }
        @media (min-width: 768px) {
            .fc .fc-toolbar {
                flex-direction: row;
            }
        }
        .fc-toolbar-title {
            font-size: 1.2rem !important;
        }
        .fc-button-primary {
            background-color: #330c59 !important;
            border-color: #330c59 !important;
        }
        .fc-button-primary:hover {
            background-color: #4a1683 !important;
            border-color: #4a1683 !important;
        }
        .fc-daygrid-day.fc-day-today {
            background-color: #e4c9e5 !important;
        }
        .fc-event {
            background-color: #f9a8c9 !important;
            border-color: #f9a8c9 !important;
            color: #330c59 !important;
            font-size: 0.85em !important;
            cursor: pointer !important;
        }
        .fc-event:hover {
            opacity: 0.9;
        }
        .fc-timegrid-slot {
            height: 3em !important;
        }
        @media (max-width: 640px) {
            .fc-toolbar-title {
                font-size: 1rem !important;
            }
            .fc-button {
                padding: 0.2rem 0.4rem !important;
                font-size: 0.8rem !important;
            }
            .fc-event {
                font-size: 0.75em !important;
            }
        }
        .availability-event {
            background-color: #4CAF50 !important;
            border-color: #4CAF50 !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-lightgray font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-violet text-white p-5 fixed h-full">
            <h1 class="text-2xl font-bold">Admin Panel</h1>
            <nav class="mt-5">
                <ul>
                    <li class="py-2"><a href="#blog" class="block hover:text-jaune"><i class="fas fa-blog mr-2"></i> Blog</a></li>
                    <li class="py-2"><a href="#coaching" class="block hover:text-jaune"><i class="fas fa-chalkboard-teacher mr-2"></i> Coaching</a></li>
                    <li class="py-2"><a href="#rendezvous" class="block hover:text-jaune"><i class="fas fa-calendar-check mr-2"></i> Rendez-vous</a></li>
                    <li class="py-2"><a href="#disponibilites" class="block hover:text-jaune"><i class="fas fa-clock mr-2"></i> Disponibilités</a></li>
                    <li class="py-2"><a href="#utilisateurs" class="block hover:text-jaune"><i class="fas fa-users mr-2"></i> Utilisateurs</a></li>
                    <li class="py-2"><a href="#ebooks" class="block hover:text-jaune"><i class="fas fa-book mr-2"></i> E-books</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10 ml-64 overflow-y-auto">
            <h2 class="text-3xl text-darkgray font-bold">Tableau de Bord</h2>

            <!-- Section Blog -->
            <section id="blog" class="mt-10">
                <h3 class="text-2xl font-bold text-violet">Gestion du Blog</h3>
                <div class="bg-white p-5 rounded-lg shadow mt-5">
                    <h4 class="text-xl font-bold">Créer un post</h4>
                    <form action="../backend/blog/PostGenerator.php" method="post" class="mt-3">
                        <label class="block text-darkgray">Titre</label>
                        <input name="titre" type="text" required class="w-full p-2 border border-gray-300 rounded mt-1">
                        <label class="block text-darkgray mt-3">Contenu</label>
                        <textarea name="context" required class="w-full p-2 border border-gray-300 rounded mt-1"></textarea>
                        <button type="submit" class="mt-3 bg-violet text-white px-4 py-2 rounded hover:bg-opacity-90 transition-colors">Ajouter</button>
                    </form>
                </div>
            </section>

            <!-- Section Coaching -->
            <section id="coaching" class="mt-10">
                <h3 class="text-2xl font-bold text-violet">Gestion des Coachings</h3>
                <div class="bg-white p-5 rounded-lg shadow mt-5">
                    <h4 class="text-xl font-bold">Créer un coaching</h4>
                    <form action="../backend/blog/CoachingGenerator.php" method="post" class="mt-3">
                        <label class="block text-darkgray">Titre</label>
                        <input name="titre" type="text" required class="w-full p-2 border border-gray-300 rounded mt-1">
                        <label class="block text-darkgray mt-3">Catégorie</label>
                        <select name="categorie" id="categorie" class="w-full p-2 border border-gray-300 rounded mt-1">
                            <option value="individuel">Individuel</option>
                            <option value="collectif">Collectif</option>
                        </select>
                        <label class="block text-darkgray mt-3">Description</label>
                        <textarea name="description" required class="w-full p-2 border border-gray-300 rounded mt-1"></textarea>
                        <button type="submit" class="mt-3 bg-violet text-white px-4 py-2 rounded hover:bg-opacity-90 transition-colors">Ajouter</button>
                    </form>
                </div>
            </section>

            <!-- Section Rendez-vous -->
            <section id="rendezvous" class="mt-10">
                <h3 class="text-2xl font-bold text-violet">Gestion des Rendez-vous</h3>
                <div class="bg-white p-5 rounded-lg shadow mt-5">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Calendar -->
                        <div>
                            <div id="calendar-appointments"></div>
                        </div>

                        <!-- Rendez-vous list -->
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-violet">Jean Dupont</h4>
                                        <p class="text-sm text-gray-600">15 Mars 2024 - 10:00</p>
                                        <p class="text-sm text-gray-500">Coaching Individuel</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-green-600 hover:bg-green-50 rounded-full">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-full">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-violet">Marie Curie</h4>
                                        <p class="text-sm text-gray-600">16 Mars 2024 - 14:00</p>
                                        <p class="text-sm text-gray-500">Coaching Collectif</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-green-600 hover:bg-green-50 rounded-full">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-full">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Disponibilités -->
            <section id="disponibilites" class="mt-10">
                <h3 class="text-2xl font-bold text-violet">Gestion des Disponibilités</h3>
                <div class="bg-white p-5 rounded-lg shadow mt-5">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Calendar -->
                        <div>
                            <div id="calendar-availability"></div>
                        </div>

                        <!-- Quick actions -->
                        <div class="space-y-4">
                            <div class="bg-violet bg-opacity-10 p-4 rounded-lg">
                                <h4 class="font-semibold text-violet mb-2">Ajouter une disponibilité</h4>
                                <form class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600">Date</label>
                                        <input type="date" class="w-full p-2 border rounded">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600">Horaire</label>
                                        <select class="w-full p-2 border rounded">
                                            <option>9h-12h</option>
                                            <option>14h-17h</option>
                                            <option>17h-20h</option>
                                        </select>
                                    </div>
                                    <button class="w-full bg-violet text-white py-2 rounded hover:bg-opacity-90 transition-colors">
                                        Ajouter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Utilisateurs -->
            <section id="utilisateurs" class="mt-10 mb-10">
                <h3 class="text-2xl font-bold text-violet">Gestion des Utilisateurs</h3>
                <div class="bg-white p-5 rounded-lg shadow mt-5">
                    <div class="flex justify-between items-center mb-6">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher un utilisateur..." 
                                   class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button class="bg-violet text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Nouvel Utilisateur
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- User Card 1 -->
                        <div class="bg-white p-4 rounded-lg border hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-violet rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-violet">Jean Dupont</h4>
                                    <p class="text-sm text-gray-600">Admin</p>
                                    <p class="text-xs text-gray-500">Inscrit le 01/03/2024</p>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- User Card 2 -->
                        <div class="bg-white p-4 rounded-lg border hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-mauve rounded-full flex items-center justify-center text-violet">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-violet">Marie Curie</h4>
                                    <p class="text-sm text-gray-600">Utilisateur</p>
                                    <p class="text-xs text-gray-500">Inscrit le 28/02/2024</p>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- User Card 3 -->
                        <div class="bg-white p-4 rounded-lg border hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-rose rounded-full flex items-center justify-center text-violet">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-violet">Albert Einstein</h4>
                                    <p class="text-sm text-gray-600">Utilisateur</p>
                                    <p class="text-xs text-gray-500">Inscrit le 25/02/2024</p>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commonCalendarConfig = {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },
                initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
                locale: 'fr',
                slotMinTime: '09:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                height: 'auto',
                expandRows: true,
                stickyHeaderDates: true,
                nowIndicator: true,
                handleWindowResize: true,
                windowResizeDelay: 200,
                eventDidMount: function(info) {
                    // Add tooltip
                    info.el.title = `${info.event.title}\nDébut: ${info.event.start.toLocaleTimeString('fr-FR')}\nFin: ${info.event.end.toLocaleTimeString('fr-FR')}`;
                }
            };

            // Initialize appointments calendar
            var appointmentsCalendar = new FullCalendar.Calendar(document.getElementById('calendar-appointments'), {
                ...commonCalendarConfig,
                slotDuration: '00:30:00',
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                events: [
                    {
                        title: 'Jean Dupont - Coaching Individuel',
                        start: '2024-03-15T10:00:00',
                        end: '2024-03-15T11:00:00',
                        description: 'Séance de coaching individuel'
                    },
                    {
                        title: 'Marie Curie - Coaching Collectif',
                        start: '2024-03-16T14:00:00',
                        end: '2024-03-16T15:30:00',
                        description: 'Séance de coaching collectif'
                    }
                ],
                eventClick: function(info) {
                    alert(`Détails du rendez-vous:\n\nClient: ${info.event.title}\nDébut: ${info.event.start.toLocaleTimeString('fr-FR')}\nFin: ${info.event.end.toLocaleTimeString('fr-FR')}\n${info.event.extendedProps.description}`);
                },
                select: function(info) {
                    const title = prompt('Entrez le nom du client:');
                    if (title) {
                        appointmentsCalendar.addEvent({
                            title: title,
                            start: info.start,
                            end: info.end,
                            description: 'Nouveau rendez-vous'
                        });
                    }
                    appointmentsCalendar.unselect();
                }
            });

            // Initialize availability calendar
            var availabilityCalendar = new FullCalendar.Calendar(document.getElementById('calendar-availability'), {
                ...commonCalendarConfig,
                slotDuration: '01:00:00',
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                events: [
                    {
                        title: 'Disponible',
                        start: '2024-03-18T09:00:00',
                        end: '2024-03-18T17:00:00',
                        classNames: ['availability-event']
                    },
                    {
                        title: 'Disponible',
                        start: '2024-03-20T10:00:00',
                        end: '2024-03-20T18:00:00',
                        classNames: ['availability-event']
                    },
                    {
                        title: 'Disponible',
                        start: '2024-03-22T14:00:00',
                        end: '2024-03-22T20:00:00',
                        classNames: ['availability-event']
                    }
                ],
                select: function(info) {
                    availabilityCalendar.addEvent({
                        title: 'Disponible',
                        start: info.start,
                        end: info.end,
                        classNames: ['availability-event']
                    });
                    availabilityCalendar.unselect();
                },
                eventClick: function(info) {
                    if (confirm('Voulez-vous supprimer cette disponibilité ?')) {
                        info.event.remove();
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const newView = window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek';
                appointmentsCalendar.changeView(newView);
                availabilityCalendar.changeView(newView);
            });

            // Render calendars
            appointmentsCalendar.render();
            availabilityCalendar.render();
        });
    </script>
</body>
</html>