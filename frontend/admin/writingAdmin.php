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

// Récupérer tous les ateliers d'écriture
$query = "SELECT * FROM atelier_ecriture ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<<<<<<< Updated upstream
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Ateliers d'Écriture - La Ligne 13</title>
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
        
        /* Workshop card styling */
        .workshop-card {
            transition: all 0.3s ease;
        }
        .workshop-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
=======
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atelier Ecriture - Admin</title>
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
                    <h1 class="text-3xl font-bold text-violet">Gestion des Ateliers d'Écriture</h1>
                    <p class="text-gray-600 mt-2">Créez, modifiez et supprimez les ateliers d'écriture</p>
                </div>
                <button class="bg-violet text-white px-4 py-2 rounded-lg hover:bg-violet/90 transition-colors flex items-center" id="newWorkshopBtn">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvel atelier
                </button>
            </div>
            
            <!-- New Workshop Form (Hidden by default) -->
            <div id="newWorkshopForm" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden animate-hidden">
                <h2 class="text-xl font-bold text-violet mb-4">Créer un nouvel atelier d'écriture</h2>
                
                <form action="../../backend/generators/WritingWorkshopGenerator.php" method="post" class="space-y-4">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" id="titre" name="titre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                            <input type="time" id="start_time" name="start_time" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="finish_time" class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                            <input type="time" id="finish_time" name="finish_time" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        </div>
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
                        <input type="text" placeholder="Rechercher un atelier..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center">
                        <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Workshop List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($workshops)): ?>
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">Aucun atelier d'écriture trouvé. Créez votre premier atelier !</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($workshops as $index => $workshop): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden workshop-card animate-hidden animate-element <?= $index % 2 ? 'animate-delay-100' : ''; ?>">
                            <div class="h-24 bg-mauve relative">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-xl font-bold text-violet text-center px-4"><?= htmlspecialchars($workshop['titre']); ?></h3>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600 text-sm mb-2">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    <?= date('d/m/Y', strtotime($workshop['date'])); ?>
                                </p>
                                <p class="text-gray-600 text-sm mb-4">
                                    <i class="far fa-clock mr-2"></i>
                                    <?= substr($workshop['heure_debut'], 0, 5); ?> - <?= substr($workshop['heure_fin'], 0, 5); ?>
                                </p>
                                <p class="text-gray-700 mb-4 line-clamp-3">
                                    <?= htmlspecialchars(substr($workshop['description'], 0, 150)) . (strlen($workshop['description']) > 150 ? '...' : ''); ?>
                                </p>
                                <div class="flex justify-between items-center">
                                    <a href="../writing-workshops.php" class="text-violet hover:text-violet/80 transition-colors" target="_blank">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 transition-colors edit-workshop" data-id="<?= $workshop['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 transition-colors delete-workshop" data-id="<?= $workshop['id']; ?>">
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
            <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer cet atelier d'écriture ? Cette action est irréversible.</p>
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
            
            // New Workshop Form Toggle
            const newWorkshopBtn = document.getElementById('newWorkshopBtn');
            const newWorkshopForm = document.getElementById('newWorkshopForm');
            const cancelBtn = document.getElementById('cancelBtn');
            
            newWorkshopBtn.addEventListener('click', function() {
                newWorkshopForm.classList.remove('hidden');
                newWorkshopForm.classList.add('animate-visible');
                newWorkshopBtn.classList.add('hidden');
            });
            
            cancelBtn.addEventListener('click', function() {
                newWorkshopForm.classList.add('hidden');
                newWorkshopForm.classList.remove('animate-visible');
                newWorkshopBtn.classList.remove('hidden');
            });
            
            // Delete Modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-workshop');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let workshopIdToDelete = null;
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    workshopIdToDelete = this.getAttribute('data-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                workshopIdToDelete = null;
            });
            
            confirmDelete.addEventListener('click', function() {
                if (workshopIdToDelete) {
                    // Send delete request to server
                    // For now, just close the modal
                    deleteModal.classList.add('hidden');
                    alert('Atelier d\'écriture supprimé avec succès !');
                    // Reload the page or remove the element from DOM
                }
            });
        });
    </script>
=======

<div class="workshops">
    <?php foreach ($workshops as $workshop): ?>
        <div class="workshop">
            <?php
            $heure_debut = date("Y-m-d\TH:i", strtotime($workshop["heure_debut"]));
            $heure_fin = date("Y-m-d\TH:i", strtotime($workshop["heure_fin"]));
            ?>
            <form action="../../backend/edit/EditWritingWorkshop.php" method="post" id="post-<?php echo $workshop['id']; ?>">
                <input type="hidden" name="id" value="<?php echo $workshop['id']; ?>">
                <input class="editable" name="titre" disabled value="<?php echo $workshop["titre"] ?>">
                <input class="editable" name="description" disabled value="<?php echo $workshop["description"] ?>">
                <input type="date" class="editable" disabled name="date" value="<?php echo $workshop["date"] ?>">
                <input type="datetime-local" class="editable" disabled name="heure_debut" value="<?php echo $heure_debut ?>">
                <input type="datetime-local" class="editable" disabled name="heure_fin" value="<?php echo $heure_fin ?>">
                <input type="submit" class="editable" disabled value="envoyer">
            </form>
            <button onclick="toggleEdit(<?php echo $workshop["id"]?>)">Modifier</button>
            <a href="../../backend/delete/DeleteWritingWorkshop.php?id=<?php echo $workshop["id"]; ?>">Supprimer</a>
        </div>
        <br>
    <?php endforeach; ?>
</div>
>>>>>>> Stashed changes
</body>
</html>