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

// Récupérer tous les utilisateurs
$query = "SELECT * FROM users ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Utilisateurs - La Ligne 13</title>
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
        
        /* User card styling */
        .user-card {
            transition: all 0.3s ease;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
                    <h1 class="text-3xl font-bold text-violet">Gestion des Utilisateurs</h1>
                    <p class="text-gray-600 mt-2">Gérez les comptes utilisateurs et leurs permissions</p>
                </div>
                <button class="bg-violet text-white px-4 py-2 rounded-lg hover:bg-violet/90 transition-colors flex items-center" id="newUserBtn">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvel utilisateur
                </button>
            </div>
            
            <!-- New User Form (Hidden by default) -->
            <div id="newUserForm" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden animate-hidden">
                <h2 class="text-xl font-bold text-violet mb-4">Créer un nouvel utilisateur</h2>
                
                <form action="../../backend/generators/UserGenerator.php" method="post" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" id="nom" name="nom" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <select id="role" name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
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
            
            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-8 animate-hidden animate-element">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Rechercher un utilisateur..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <option value="">Tous les rôles</option>
                            <option value="admin">Administrateur</option>
                            <option value="user">Utilisateur</option>
                        </select>
                        
                        <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Users List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($users)): ?>
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">Aucun utilisateur trouvé. Créez votre premier utilisateur !</p>
                    </div>
                <?php else: ?>
                    <!-- User Card 1 -->
                    <div class="bg-white p-4 rounded-lg border hover:shadow-lg transition-shadow user-card animate-hidden animate-element">
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
                                <button class="text-blue-600 hover:text-blue-800 edit-user" data-id="1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 delete-user" data-id="1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Card 2 -->
                    <div class="bg-white p-4 rounded-lg border hover:shadow-lg transition-shadow user-card animate-hidden animate-element animate-delay-100">
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
                                <button class="text-blue-600 hover:text-blue-800 edit-user" data-id="2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 delete-user" data-id="2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Card 3 -->
                    <div class="bg-white p-4 rounded-lg border hover:shadow-lg transition-shadow user-card animate-hidden animate-element animate-delay-200">
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
                                <button class="text-blue-600 hover:text-blue-800 edit-user" data-id="3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 delete-user" data-id="3">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-xl font-bold text-violet mb-4">Confirmer la suppression</h3>
            <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
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
            
            // New User Form Toggle
            const newUserBtn = document.getElementById('newUserBtn');
            const newUserForm = document.getElementById('newUserForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            newUserBtn.addEventListener('click', function() {
                newUserForm.classList.remove('hidden');
                newUserForm.classList.add('animate-visible');
                newUserBtn.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', function() {
                newUserForm.classList.add('hidden');
                newUserForm.classList.remove('animate-visible');
                newUserBtn.classList.remove('hidden');
            });
            
            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-user');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let userIdToDelete = null;
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    userIdToDelete = this.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                userIdToDelete = null;
            });
            
            confirmDelete.addEventListener('click', function() {
                if (userIdToDelete) {
                    // Send delete request to server
                    // For now, just close the modal
                    deleteModal.classList.add('hidden');
                    alert('Utilisateur supprimé avec succès !');
                    // Reload the page or remove the element from DOM
                }
            });
        });
    </script>
</body>
</html>