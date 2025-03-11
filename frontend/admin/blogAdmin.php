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

// Récupérer tous les articles du blog
$query = "SELECT * FROM post ORDER BY date_publie DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scazqle=1.0">
    <title>Administration Blog - La Ligne 13</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function toggleEdit(postId) {
            let inputs = document.querySelectorAll('#post-' + postId + ' .editable');
            inputs.forEach(function(input) {
                input.disabled = !input.disabled;
            });
        }
    </script>
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
        
        /* Blog post card styling */
        .blog-card {
            transition: all 0.3s ease;
        }
        .blog-card:hover {
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
                    <h1 class="text-3xl font-bold text-violet">Gestion du Blog</h1>
                    <p class="text-gray-600 mt-2">Créez, modifiez et supprimez les articles du blog</p>
                </div>
                <button class="bg-violet text-white px-4 py-2 rounded-lg hover:bg-violet/90 transition-colors flex items-center" id="newPostBtn">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvel article
                </button>
            </div>
            
            <!-- New Post Form (Hidden by default) -->
            <div id="newPostForm" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden animate-hidden">
                <h2 class="text-xl font-bold text-violet mb-4">Créer un nouvel article</h2>
                
                <form action="../../backend/generators/PostGenerator.php" method="post" class="space-y-4">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" id="titre" name="titre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="context" class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
                        <textarea id="context" name="context" rows="10" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-violet text-white rounded-md hover:bg-violet/90 transition-colors">
                            Publier
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-8 animate-hidden animate-element">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Rechercher un article..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                            <option value="">Tous les articles</option>
                            <option value="recent">Articles récents</option>
                            <option value="popular">Articles populaires</option>
                        </select>
                        
                        <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Blog Posts List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($posts)): ?>
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">Aucun article de blog trouvé. Créez votre premier article !</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $index => $post): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden blog-card animate-hidden animate-element <?= $index % 2 ? 'animate-delay-100' : ''; ?>">
                            <div class="h-40 bg-violet relative">
                                <img src="../../src/img/blog<?= ($index % 5) + 1; ?>.jpg" alt="<?= htmlspecialchars($post['titre']); ?>" class="w-full h-full object-cover opacity-70">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-xl font-bold text-white text-center px-4"><?= htmlspecialchars($post['titre']); ?></h3>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-500 text-sm mb-2">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?= date("d/m/Y", strtotime($post['date_publie'])); ?>
                                </p>
                                <p class="text-gray-700 mb-4 line-clamp-3">
                                    <?= htmlspecialchars(substr($post['contenu'], 0, 150)) . (strlen($post['contenu']) > 150 ? '...' : ''); ?>
                                </p>
                                <div class="flex justify-between items-center">
                                    <a href="../blog/detail.php?id=<?= $post['id']; ?>" class="text-violet hover:text-violet/80 transition-colors" target="_blank">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 transition-colors edit-post" data-id="<?= $post['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 transition-colors delete-post" data-id="<?= $post['id']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center mt-8 animate-hidden animate-element animate-delay-200">
                <nav class="inline-flex rounded-md shadow">
                    <a href="#" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-violet font-medium">
                        1
                    </a>
                    <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                        2
                    </a>
                    <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                        3
                    </a>
                    <a href="#" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-xl font-bold text-violet mb-4">Confirmer la suppression</h3>
            <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.</p>
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

    <!-- Modal d'édition -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-8 border w-3/4 max-w-4xl shadow-2xl rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-violet">Modifier l'article</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editForm" action="../../backend/edit/EditPost.php" method="post" class="space-y-6">
                    <input type="hidden" name="post_id" id="modalPostId">
                    <div>
                        <label for="modalTitre" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                        <input type="text" name="titre" id="modalTitre" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent text-lg">
                    </div>
                    <div>
                        <label for="modalContenu" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                        <textarea name="contenu" id="modalContenu" rows="12" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent resize-none"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <button type="button" id="closeModalBtn" 
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                            Annuler
                        </button>
                        <button type="submit" 
                            class="px-6 py-2.5 bg-violet text-white rounded-lg hover:bg-violet/90 transition-colors font-medium flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
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
            
            // New Post Form Toggle
            const newPostBtn = document.getElementById('newPostBtn');
            const newPostForm = document.getElementById('newPostForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            newPostBtn.addEventListener('click', function() {
                newPostForm.classList.remove('hidden');
                newPostForm.classList.add('animate-visible');
                newPostBtn.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', function() {
                newPostForm.classList.add('hidden');
                newPostForm.classList.remove('animate-visible');
                newPostBtn.classList.remove('hidden');
            });
            
            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-post');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let postIdToDelete = null;
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    postIdToDelete = this.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                postIdToDelete = null;
            });
            
            confirmDelete.addEventListener('click', function() {
                if (postIdToDelete) {
                    // Send delete request to server
                    // For now, just close the modal
                    deleteModal.classList.add('hidden');
                    alert('Article supprimé avec succès !');
                    // Reload the page or remove the element from DOM
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editModal');
            const editButtons = document.querySelectorAll('.edit-post');
            const closeModal = document.getElementById('closeModal');
            const editForm = document.getElementById('editForm');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-id');
                    
                    // Récupérer les données du post via AJAX
                    fetch(`../../backend/get/GetPost.php?id=${postId}`)
                        .then(response => response.json())
                        .then(post => {
                            document.getElementById('modalPostId').value = post.id;
                            document.getElementById('modalTitre').value = post.titre;
                            document.getElementById('modalContenu').value = post.contenu;
                            modal.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Une erreur est survenue lors de la récupération des données');
                        });
                });
            });

            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Fermer le modal en cliquant en dehors
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // Empêcher la propagation du clic depuis le formulaire
            editForm.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>