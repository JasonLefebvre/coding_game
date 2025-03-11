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

// Récupérer tous les coachings
$query = "SELECT * FROM coaching ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$coachings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<<<<<<< Updated upstream
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Coaching - La Ligne 13</title>
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
        
        /* Coaching card styling */
        .coaching-card {
            transition: all 0.3s ease;
        }
        .coaching-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
=======
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coaching - Admin</title>
    <script>
        function toggleEdit(postId) {
            let inputs = document.querySelectorAll('#post-' + postId + ' .editable');
            inputs.forEach(function(input) {
                input.disabled = !input.disabled;
            });
        }
    </script>
>>>>>>> Stashed changes
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
                    <h1 class="text-3xl font-bold text-violet">Gestion du Coaching</h1>
                    <p class="text-gray-600 mt-2">Créez, modifiez et supprimez les offres de coaching</p>
                </div>
                <button class="bg-violet text-white px-4 py-2 rounded-lg hover:bg-violet/90 transition-colors flex items-center" id="newCoachingBtn">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle offre
                </button>
            </div>
            
            <!-- New Coaching Form (Hidden by default) -->
            <div id="newCoachingForm" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden animate-hidden">
                <h2 class="text-xl font-bold text-violet mb-4">Créer une nouvelle offre de coaching</h2>
                
                <form action="../../backend/generators/CoachingGenerator.php" method="post" class="space-y-4">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" id="titre" name="titre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select id="categorie" name="categorie" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <option value="individuel">Individuel</option>
                            <option value="collectif">Collectif</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent"></textarea>
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
                        <input type="text" placeholder="Rechercher une offre..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <option value="">Toutes les catégories</option>
                            <option value="individuel">Individuel</option>
                            <option value="collectif">Collectif</option>
                        </select>
                        
                        <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Coaching List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($coachings)): ?>
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">Aucune offre de coaching trouvée. Créez votre première offre !</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($coachings as $index => $coaching): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden coaching-card animate-hidden animate-element <?= $index % 2 ? 'animate-delay-100' : ''; ?>">
                            <div class="h-24 bg-rose relative">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-xl font-bold text-white text-center px-4"><?= htmlspecialchars($coaching['titre']); ?></h3>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-mauve text-violet">
                                        <?= ucfirst(htmlspecialchars($coaching['categorie'])); ?>
                                    </span>
                                </div>
                                <p class="text-gray-700 mb-4 line-clamp-3">
                                    <?= htmlspecialchars(substr($coaching['description'], 0, 150)) . (strlen($coaching['description']) > 150 ? '...' : ''); ?>
                                </p>
                                <div class="flex justify-between items-center">
                                    <a href="../coaching.php" class="text-violet hover:text-violet/80 transition-colors" target="_blank">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 transition-colors edit-coaching" data-id="<?= $coaching['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 transition-colors delete-coaching" data-id="<?= $coaching['id']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

<<<<<<< Updated upstream
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-xl font-bold text-violet mb-4">Confirmer la suppression</h3>
            <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer cette offre de coaching ? Cette action est irréversible.</p>
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
            
            // New Coaching Form Toggle
            const newCoachingBtn = document.getElementById('newCoachingBtn');
            const newCoachingForm = document.getElementById('newCoachingForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            newCoachingBtn.addEventListener('click', function() {
                newCoachingForm.classList.remove('hidden');
                newCoachingForm.classList.add('animate-visible');
                newCoachingBtn.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', function() {
                newCoachingForm.classList.add('hidden');
                newCoachingForm.classList.remove('animate-visible');
                newCoachingBtn.classList.remove('hidden');
            });
            
            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-coaching');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let coachingIdToDelete = null;
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    coachingIdToDelete = this.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                coachingIdToDelete = null;
            });
            
            confirmDelete.addEventListener('click', function() {
                if (coachingIdToDelete) {
                    // Send delete request to server
                    // For now, just close the modal
                    deleteModal.classList.add('hidden');
                    alert('Offre de coaching supprimée avec succès !');
                    // Reload the page or remove the element from DOM
                }
            });
        });
    </script>
=======
<div class="coachings">
    <?php foreach ($coachings as $coaching): ?>
        <div class="coaching">
            <form action="../../backend/edit/EditCoaching.php" method="post" id="post-<?php echo $coaching['id']; ?>">
                <input type="hidden" name="post_id" value="<?php echo $coaching['id']; ?>">
                <input class="editable" name="titre" disabled value="<?php echo $coaching["titre"] ?>">
                <input class="editable" disabled name="description" value="<?php echo $coaching["description"] ?>">
                <select name="categorie" class="editable" disabled>
                    <option selected value="<?php echo $coaching["categorie"] ?>"><?php echo $coaching["categorie"] ?></option>
                    <option value="<?php echo ($coaching["categorie"] == 'individuel') ? 'collectif' : 'Individuel';?>"><?php echo ($coaching["categorie"] == 'individuel') ? 'Collectif' : 'Individuel';?></option>
                </select>
                <input type="submit" class="editable" disabled value="envoyer">
            </form>
            <button onclick="toggleEdit(<?php echo $coaching["id"]?>)">Modifier</button>
            <a href="../../backend/delete/DeleteCoaching.php?id=<?php echo $coaching["id"]; ?>">Supprimer</a>
        </div>
        <br>
    <?php endforeach; ?>
</div>
>>>>>>> Stashed changes
</body>
</html>