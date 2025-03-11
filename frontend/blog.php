<?php
session_start();
require("../backend/utils/ConnectToBDD.php");

$sql = "SELECT * FROM post";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - La Ligne 13</title>
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
            height: 50vh;
            min-height: 400px;
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
        .animate-delay-400 {
            transition-delay: 0.4s;
        }
        .animate-delay-500 {
            transition-delay: 0.5s;
        }

        /* Blog post card */
        .blog-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-white text-darkgray">
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
                        <a href="blog.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Blog
                        </a>
                        <a href="about.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
                        <a href="contact.html" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
                            Contactez-nous
                        </a>
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
                <a href="blog.php" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Blog
                </a>
                <a href="about.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <a href="contact.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Contact
                </a>
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
    <div class="hero-pattern flex items-center">
        <div class="diagonal-line-left"></div>
        <div class="diagonal-line-right"></div>
        <div class="curved-line-bottom"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-jaune sm:text-5xl md:text-6xl animate-hidden animate-element">
                    <span class="block">Notre Blog</span>
                    <span class="block text-white mt-2">Actualités & Réflexions</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg md:text-xl animate-hidden animate-element animate-delay-100">
                    Découvrez nos articles sur l'équité femmes-hommes, le leadership féminin et les enjeux de la diversité dans le monde professionnel.
                </p>
            </div>
        </div>
    </div>

    <!-- Blog Posts Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                <div class="mb-8 text-right">
                    <a href="create_post.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-violet hover:bg-violet/90">
                        <i class="fas fa-plus mr-2"></i> Créer un article
                    </a>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $index => $post): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden blog-card animate-hidden animate-element animate-delay-<?= ($index % 3) * 100; ?>">
                            <div class="h-48 bg-violet relative">
                                <img src="../src/img/blog<?= $index % 5 + 1; ?>.jpg" alt="<?= htmlspecialchars($post['titre']); ?>" class="w-full h-full object-cover opacity-70">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white text-center px-4"><?= htmlspecialchars($post['titre']); ?></h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-500 text-sm mb-4">
                                    <i class="far fa-calendar-alt mr-2"></i> <?= date("d/m/Y", strtotime($post['date_publie'])); ?>
                                </p>
                                <p class="text-gray-700 mb-6 line-clamp-3">
                                    <?= substr(htmlspecialchars($post['contenu']), 0, 150) . '...'; ?>
                                </p>
                                <div class="mt-6 flex justify-between items-center">
                                    <a href="blog/detail.php?id=<?= htmlspecialchars($post['id']); ?>" class="inline-flex items-center text-violet hover:text-violet/80">
                                        Lire la suite <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                    
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                                        <div class="flex space-x-2">
                                            <a href="edit_post.php?id=<?= htmlspecialchars($post['id']); ?>" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../backend/generators/DeletePost.php?id=<?= htmlspecialchars($post['id']); ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center">
                        <p class="text-gray-500 text-lg">Aucun article disponible pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-violet rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4 animate-hidden animate-element">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-jaune sm:text-4xl">
                            <span class="block">Restez informé(e)</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-mauve">
                            Inscrivez-vous à notre newsletter pour recevoir nos derniers articles et actualités.
                        </p>
                        <form class="mt-8 sm:flex">
                            <label for="email-address" class="sr-only">Adresse email</label>
                            <input id="email-address" name="email" type="email" autocomplete="email" required class="w-full px-5 py-3 placeholder-gray-500 focus:ring-jaune focus:border-jaune border-gray-300 rounded-md" placeholder="Votre adresse email">
                            <button type="submit" class="mt-3 w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jaune sm:mt-0 sm:ml-3 sm:w-auto sm:flex-shrink-0">
                                S'inscrire
                            </button>
                        </form>
                    </div>
                </div>
                <div class="relative -mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                    <img class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top sm:translate-x-16 lg:translate-y-20" src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Newsletter">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
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
    </script>
</body>
</html>