<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas un administrateur
    // header('Location: login.php');
    // exit;
    
    // Pour le développement, nous ne redirigeons pas
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - La Ligne 13</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .animate-delay-100 {
            transition-delay: 0.1s;
        }
        .animate-delay-200 {
            transition-delay: 0.2s;
        }
        .animate-delay-300 {
            transition-delay: 0.3s;
        }
        
        /* Admin card styling */
        .admin-card {
            transition: all 0.3s ease;
        }
        .admin-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .admin-card:hover .card-icon {
            transform: scale(1.1);
        }
        .card-icon {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-lightgray text-darkgray">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-violet text-white fixed h-full overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-center mb-8">
                    <img src="../src/img/logo.jpg" alt="Logo Ligne 13" class="h-12 w-auto bg-white rounded-full p-1">
                    <h1 class="text-xl font-bold ml-3">Administration</h1>
                </div>
                
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-tachometer-alt w-6"></i>
                                <span class="ml-3">Tableau de bord</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin/blogAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-blog w-6"></i>
                                <span class="ml-3">Blog</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin/coachingAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-chalkboard-teacher w-6"></i>
                                <span class="ml-3">Coaching</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin/writingAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-pen-fancy w-6"></i>
                                <span class="ml-3">Ateliers d'écriture</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin/equityAdmin.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-balance-scale w-6"></i>
                                <span class="ml-3">Ateliers d'équité</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-users w-6"></i>
                                <span class="ml-3">Utilisateurs</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-violet/80 transition-colors">
                                <i class="fas fa-calendar-alt w-6"></i>
                                <span class="ml-3">Rendez-vous</span>
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
                <a href="index.php" class="flex items-center text-white hover:text-jaune transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Retour au site</span>
                </a>
                <a href="logout.php" class="flex items-center text-white hover:text-jaune transition-colors mt-4">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-violet">Tableau de bord</h1>
                <p class="text-gray-600 mt-2">Bienvenue dans l'interface d'administration de La Ligne 13</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 animate-hidden animate-element">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-rose/20 text-rose">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Utilisateurs</h2>
                            <p class="text-2xl font-semibold">254</p>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>12% depuis le mois dernier</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 animate-hidden animate-element animate-delay-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-mauve/20 text-violet">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Réservations</h2>
                            <p class="text-2xl font-semibold">42</p>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>8% depuis le mois dernier</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 animate-hidden animate-element animate-delay-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-jaune/20 text-yellow-600">
                            <i class="fas fa-blog text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Articles</h2>
                            <p class="text-2xl font-semibold">18</p>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-red-600">
                        <i class="fas fa-arrow-down mr-1"></i>
                        <span>3% depuis le mois dernier</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 animate-hidden animate-element animate-delay-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-violet/20 text-violet">
                            <i class="fas fa-euro-sign text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Revenus</h2>
                            <p class="text-2xl font-semibold">2 100€</p>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>15% depuis le mois dernier</span>
                    </div>
                </div>
            </div>
            
            <!-- Admin Cards -->
            <h2 class="text-2xl font-bold text-violet mb-6">Gestion du site</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Blog Card -->
                <a href="admin/blogAdmin.php" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden admin-card animate-hidden animate-element">
                        <div class="h-40 bg-violet relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-blog text-6xl text-white opacity-20 card-icon"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-white">Blog</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Gérez les articles du blog, ajoutez du contenu et modifiez les publications existantes.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-violet font-medium">18 articles</span>
                                <span class="text-violet">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Coaching Card -->
                <a href="admin/coachingAdmin.php" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden admin-card animate-hidden animate-element animate-delay-100">
                        <div class="h-40 bg-rose relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-6xl text-white opacity-20 card-icon"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-white">Coaching</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Gérez les offres de coaching, les séances et les disponibilités.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-violet font-medium">5 offres actives</span>
                                <span class="text-violet">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Writing Workshops Card -->
                <a href="admin/writingAdmin.php" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden admin-card animate-hidden animate-element animate-delay-200">
                        <div class="h-40 bg-mauve relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-pen-fancy text-6xl text-white opacity-20 card-icon"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-white">Ateliers d'écriture</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Gérez les ateliers d'écriture, les dates, les inscriptions et le contenu.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-violet font-medium">8 ateliers programmés</span>
                                <span class="text-violet">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Equity Workshops Card -->
                <a href="admin/equityAdmin.php" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden admin-card animate-hidden animate-element">
                        <div class="h-40 bg-jaune relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-balance-scale text-6xl text-darkgray opacity-20 card-icon"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-darkgray">Ateliers d'équité</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Gérez les ateliers d'équité, les dates, les inscriptions et le contenu.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-violet font-medium">6 ateliers programmés</span>
                                <span class="text-violet">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Users Card -->
                <a href="#" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden admin-card animate-hidden animate-element animate-delay-100">
                        <div class="h-40 bg-violet/80 relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-users text-6xl text-white opacity-20 card-icon"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-white">Utilisateurs</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Gérez les comptes utilisateurs, les rôles et les permissions.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-violet font-medium">254 utilisateurs</span>
                                <span class="text-violet">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Settings Card -->
                <a href="#" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden admin-card animate-hidden animate-element animate-delay-200">
                        <div class="h-40 bg-gray-700 relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-cog text-6xl text-white opacity-20 card-icon"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-white">Paramètres</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Configurez les paramètres du site, les options générales et les préférences.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-violet font-medium">Configuration</span>
                                <span class="text-violet">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Recent Activity -->
            <h2 class="text-2xl font-bold text-violet mt-12 mb-6">Activité récente</h2>
            
            <div class="bg-white rounded-lg shadow-md p-6 animate-hidden animate-element">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Réservation</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Marie Dupont</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">Aujourd'hui, 10:23</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Atelier d'écriture "Écrire avec les sens"
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Inscription</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Jean Martin</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">Hier, 15:47</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Nouvel utilisateur inscrit
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Publication</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Admin</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">Il y a 2 jours</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Nouvel article de blog "L'importance de l'équité"
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            // Initial check for elements in viewport
            checkVisibility();
            
            // Add scroll event listener
            window.addEventListener('scroll', checkVisibility);
            
            function checkVisibility() {
                const elements = document.querySelectorAll('.animate-hidden');
                
                elements.forEach(element => {
                    if (isElementInViewport(element)) {
                        element.classList.add('animate-visible');
                    }
                });
            }
            
            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85 &&
                    rect.bottom >= 0
                );
            }
        });
    </script>
</body>
</html>