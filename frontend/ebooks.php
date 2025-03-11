<?php
session_start();
require("../backend/utils/ConnectToBDD.php");

// Récupérer tous les ebooks disponibles
$query = "SELECT * FROM ebooks WHERE is_active = 1 ORDER BY date_publication DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$ebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories pour le filtre
$query = "SELECT DISTINCT categorie FROM ebooks WHERE is_active = 1 ORDER BY categorie";
$stmt = $pdo->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-books - La Ligne 13</title>
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
        .hero-pattern {
            background-color: #1a1a1a;
            position: relative;
            overflow: hidden;
            height: 40vh;
            min-height: 300px;
        }
        .diagonal-line-left {
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 4px;
            background-color: #ffeb5b;
            transform: rotate(15deg);
            transform-origin: top left;
        }
        .diagonal-line-right {
            position: absolute;
            right: 0;
            top: 20%;
            height: 60%;
            width: 4px;
            background-color: #f9a8c9;
            transform: rotate(-15deg);
            transform-origin: top right;
        }
        .curved-line-bottom {
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            border: 4px solid #ffeb5b;
            border-top: none;
            border-right: none;
            border-radius: 0 0 0 300px;
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
        
        /* Card styling */
        .ebook-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .ebook-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }


    </style>
</head>
<body class="bg-lightgray text-darkgray">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="../src/img/logo.jpg" alt="Logo Ligne 13" class="h-8 w-auto">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Accueil
                        </a>
                        <a href="ateliers.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'équité
                        </a>
                        <a href="coaching.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Coaching
                        </a>
                        <a href="ecriture.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'écriture
                        </a>
                        <a href="ebooks.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            E-books
                        </a>
                        <a href="blog.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Blog
                        </a>
                        <a href="about.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <a href="admin.php" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
                                Administration
                            </a>
                        <?php else: ?>
                            <a href="contact.html" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
                                Contactez-nous
                            </a>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="myaccount.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium ml-4">
                                Mon compte
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium ml-4">
                                Connexion
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="-mr-2 ml-4 flex items-center sm:hidden">
                        <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-violet hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-violet" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Ouvrir le menu</span>
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="index.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Accueil
                </a>
                <a href="ateliers.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'équité
                </a>
                <a href="coaching.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Coaching
                </a>
                <a href="ecriture.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'écriture
                </a>
                <a href="ebooks.php" class="border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    E-books
                </a>
                <a href="blog.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Blog
                </a>
                <a href="about.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="admin.php" class="bg-violet text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Administration
                    </a>
                <?php else: ?>
                    <a href="contact.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Contact
                    </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="myaccount.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Mon compte
                    </a>
                <?php else: ?>
                    <a href="login.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Connexion
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-pattern bg-violet flex items-center">
        <div class="diagonal-line-left"></div>
        <div class="diagonal-line-right"></div>
        <div class="curved-line-bottom"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16">
            <div class="text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-jaune sm:text-4xl md:text-5xl animate-hidden animate-element">
                    <span class="block">E-books</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg animate-hidden animate-element animate-delay-100">
                    Découvrez notre collection de ressources numériques pour approfondir vos connaissances
                </p>
            </div>
        </div>
    </div>

    <!-- E-books Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-12 animate-hidden animate-element">
                <div class="bg-lightgray rounded-lg p-6 shadow-md">
                    <h2 class="text-xl font-bold text-violet mb-4">Filtrer les e-books</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie
                            </label>
                            <select id="category-filter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                <option value="">Toutes les catégories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="price-filter" class="block text-sm font-medium text-gray-700 mb-1">
                                Prix
                            </label>
                            <select id="price-filter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                <option value="">Tous les prix</option>
                                <option value="0-10">Moins de 10€</option>
                                <option value="10-20">10€ - 20€</option>
                                <option value="20+">Plus de 20€</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="search-filter" class="block text-sm font-medium text-gray-700 mb-1">
                                Recherche
                            </label>
                            <div class="relative">
                                <input type="text" id="search-filter" placeholder="Rechercher un e-book..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-violet focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- E-books Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="ebooks-container">
                <?php if (empty($ebooks)): ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">Aucun e-book disponible pour le moment.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($ebooks as $ebook): ?>
                        <div class="ebook-card bg-white rounded-lg shadow-md overflow-hidden animate-hidden animate-element" data-category="<?= htmlspecialchars($ebook['categorie']); ?>" data-price="<?= $ebook['prix']; ?>">
                            <div class="bg-violet relative p-4">
                                <?php if (!empty($ebook['image'])): ?>
                                    <img src="<?= htmlspecialchars($ebook['image']); ?>" alt="<?= htmlspecialchars($ebook['titre']); ?>" class="w-full h-[400px] object-contain bg-gray-50 rounded-lg">
                                <?php else: ?>
                                    <div class="w-full h-[400px] flex items-center justify-center bg-gray-50 rounded-lg">
                                        <i class="fas fa-book text-violet text-5xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-2 right-2 bg-jaune text-darkgray px-2 py-1 rounded-md text-sm font-bold">
                                    <?= number_format($ebook['prix'], 2, ',', ' '); ?>€
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-bold text-violet"><?= htmlspecialchars($ebook['titre']); ?></h3>
                                    <span class="text-xs bg-mauve text-violet px-2 py-1 rounded-full"><?= htmlspecialchars($ebook['categorie']); ?></span>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars(substr($ebook['description'], 0, 100)) . (strlen($ebook['description']) > 100 ? '...' : ''); ?></p>
                                
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <div class="flex items-center mr-4">
                                        <i class="fas fa-user mr-1"></i>
                                        <span><?= htmlspecialchars($ebook['auteur']); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        <span><?= $ebook['nombre_pages']; ?> pages</span>
                                    </div>
                                </div>
                                
                                <a href="ebook-details.php?id=<?= $ebook['id']; ?>" class="block w-full bg-violet text-white text-center py-2 rounded-md hover:bg-violet/90 transition-colors">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- No Results Message -->
            <div id="no-results" class="hidden text-center py-12 animate-hidden animate-element">
                <p class="text-gray-500 text-lg">Aucun e-book ne correspond à vos critères de recherche.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-lightgray border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
            <nav class="-mx-5 -my-2 flex flex-wrap justify-center" aria-label="Footer">
                <div class="px-5 py-2">
                    <a href="index.php" class="text-base text-gray-600 hover:text-violet">
                        Accueil
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="ateliers.php" class="text-base text-gray-600 hover:text-violet">
                        Ateliers d'équité
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="coaching.php" class="text-base text-gray-600 hover:text-violet">
                        Coaching
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="ecriture.php" class="text-base text-gray-600 hover:text-violet">
                        Ateliers d'écriture
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="ebooks.php" class="text-base text-gray-600 hover:text-violet">
                        E-books
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="blog.php" class="text-base text-gray-600 hover:text-violet">
                        Blog
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="about.html" class="text-base text-gray-600 hover:text-violet">
                        À propos
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="contact.html" class="text-base text-gray-600 hover:text-violet">
                        Contact
                    </a>
                </div>
            </nav>
            <div class="mt-8 flex justify-center space-x-6">
                <a href="https://www.linkedin.com/in/audrey-rebout-9144b7162/" class="text-gray-500 hover:text-violet">
                    <span class="sr-only">LinkedIn</span>
                    <i class="fab fa-linkedin text-xl"></i>
                </a>
                <a href="https://www.instagram.com/la_ligne_13.coaching/" class="text-gray-500 hover:text-violet">
                    <span class="sr-only">Instagram</span>
                    <i class="fab fa-instagram text-xl"></i>
                </a>
            </div>
            <p class="mt-8 text-center text-base text-gray-500">
                &copy; 2025 La Ligne 13. Tous droits réservés.
            </p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

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

        // Filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('category-filter');
            const priceFilter = document.getElementById('price-filter');
            const searchFilter = document.getElementById('search-filter');
            const ebooksContainer = document.getElementById('ebooks-container');
            const noResults = document.getElementById('no-results');
            const ebookCards = document.querySelectorAll('.ebook-card');
            
            // Apply filters when any filter changes
            categoryFilter.addEventListener('change', applyFilters);
            priceFilter.addEventListener('change', applyFilters);
            searchFilter.addEventListener('input', applyFilters);
            
            function applyFilters() {
                const selectedCategory = categoryFilter.value.toLowerCase();
                const selectedPrice = priceFilter.value;
                const searchTerm = searchFilter.value.toLowerCase();
                
                let visibleCount = 0;
                
                ebookCards.forEach(card => {
                    const category = card.dataset.category.toLowerCase();
                    const price = parseFloat(card.dataset.price);
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    const description = card.querySelector('p').textContent.toLowerCase();
                    
                    // Check category filter
                    const categoryMatch = selectedCategory === '' || category === selectedCategory;
                    
                    // Check price filter
                    let priceMatch = true;
                    if (selectedPrice === '0-10') {
                        priceMatch = price < 10;
                    } else if (selectedPrice === '10-20') {
                        priceMatch = price >= 10 && price <= 20;
                    } else if (selectedPrice === '20+') {
                        priceMatch = price > 20;
                    }
                    
                    // Check search filter
                    const searchMatch = searchTerm === '' || 
                                       title.includes(searchTerm) || 
                                       description.includes(searchTerm);
                    
                    // Show or hide the card based on all filters
                    if (categoryMatch && priceMatch && searchMatch) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Show or hide the "No results" message
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>