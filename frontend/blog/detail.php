<?php
session_start();
require("../../backend/utils/ConnectToBDD.php");

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]);

    $query = "SELECT titre, contenu, date_publie FROM post WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if ($post) {
        $titre = $post["titre"];
        $contenu = $post["contenu"];
        $date_publie = $post["date_publie"];
    } else {
        die("Post inconnu");
    }
} else {
    die("ID Invalide");
}

$query = "SELECT * FROM commentaire WHERE post_id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$comments = $stmt->fetchAll();

function getUserName($userId, $pdo) {
    $query = "SELECT nom, prenom FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    return ["nom" => $user["nom"], "prenom" => $user["prenom"]];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?> - La Ligne 13</title>
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
        
        /* Blog content styling */
        .blog-content {
            line-height: 1.8;
        }
        .blog-content p {
            margin-bottom: 1.5rem;
        }
        .blog-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #330c59;
        }
        .blog-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #330c59;
        }
        .blog-content ul, .blog-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .blog-content ul {
            list-style-type: disc;
        }
        .blog-content ol {
            list-style-type: decimal;
        }
        .blog-content a {
            color: #330c59;
            text-decoration: underline;
        }
        
        /* Comment styling */
        .comment {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .comment:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
                        <img src="../../src/img/logo.jpg" alt="Logo Ligne 13" class="h-8 w-auto">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="../index.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Accueil
                        </a>
                        <a href="../ateliers.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'équité
                        </a>
                        <a href="../coaching.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Coaching
                        </a>
                        <a href="../ecriture.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'écriture
                        </a>
                        <a href="../blog.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Blog
                        </a>
                        <a href="../about.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
                        <a href="../contact.html" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
                            Contactez-nous
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../myaccount.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium ml-4">
                                Mon compte
                            </a>
                        <?php else: ?>
                            <a href="../login.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium ml-4">
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
                <a href="../index.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Accueil
                </a>
                <a href="../ateliers.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'équité
                </a>
                <a href="../coaching.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Coaching
                </a>
                <a href="../ecriture.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'écriture
                </a>
                <a href="../blog.php" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Blog
                </a>
                <a href="../about.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <a href="../contact.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Contact
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../myaccount.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Mon compte
                    </a>
                <?php else: ?>
                    <a href="../login.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
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
                    <span class="block"><?= htmlspecialchars($titre) ?></span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg animate-hidden animate-element animate-delay-100">
                    <i class="far fa-calendar-alt mr-2"></i> Publié le <?= date("d/m/Y", strtotime($date_publie)); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Blog Content Section -->
    <div class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8 animate-hidden animate-element">
                <div class="blog-content text-gray-700">
                    <?= nl2br(htmlspecialchars($contenu)); ?>
                </div>
                
                <div class="mt-8 flex justify-between items-center border-t border-gray-200 pt-6">
                    <a href="../blog.php" class="inline-flex items-center text-violet hover:text-violet/80">
                        <i class="fas fa-arrow-left mr-2"></i> Retour aux articles
                    </a>
                    
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-violet">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-violet">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-violet">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="py-12 bg-lightgray">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-violet mb-8 animate-hidden animate-element">Commentaires (<?= count($comments) ?>)</h2>
            
            <?php if (!empty($comments)): ?>
                <div class="space-y-6 mb-12">
                    <?php foreach ($comments as $index => $comment): ?>
                        <?php $user = getUserName($comment["user_id"], $pdo); ?>
                        <div class="bg-white p-6 rounded-lg shadow-md comment animate-hidden animate-element animate-delay-<?= ($index % 3) * 100; ?>">
                            <div class="flex items-start">
                                <img src="https://randomuser.me/api/portraits/men/<?= $comment["user_id"] % 100; ?>.jpg" alt="Avatar" class="w-10 h-10 rounded-full mr-4">
                                <div>
                                    <div class="flex items-center mb-2">
                                        <h4 class="font-bold text-gray-800"><?= htmlspecialchars($user["prenom"] . " " . $user["nom"]); ?></h4>
                                        <span class="text-sm text-gray-500 ml-2">• <?= date("d/m/Y à H:i", strtotime($comment["date_publi"])); ?></span>
                                    </div>
                                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($comment["commentaire"])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 mb-8 animate-hidden animate-element">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="bg-white p-6 rounded-lg shadow-md animate-hidden animate-element animate-delay-200">
                    <h3 class="text-xl font-bold text-violet mb-4">Ajouter un commentaire</h3>
                    <form action="../../backend/generators/CommentGenerator.php" method="post">
                        <input type="hidden" name="post_id" value="<?= $id ?>">
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Votre commentaire :</label>
                            <textarea id="comment" name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-violet focus:border-violet" required></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-violet hover:bg-violet/90">
                            Envoyer
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-white p-6 rounded-lg shadow-md text-center animate-hidden animate-element animate-delay-200">
                    <p class="text-gray-700 mb-4">Vous devez être connecté pour laisser un commentaire.</p>
                    <a href="../login.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-violet hover:bg-violet/90">
                        Se connecter
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Articles Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-violet mb-8 text-center animate-hidden animate-element">Articles similaires</h2>
            
            <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                <!-- Placeholder for related articles -->
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden animate-hidden animate-element animate-delay-<?= $i * 100; ?>">
                        <div class="h-48 bg-violet relative">
                            <img src="../../src/img/blog<?= $i; ?>.jpg" alt="Article similaire" class="w-full h-full object-cover opacity-70">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h3 class="text-xl font-bold text-white text-center px-4">Article similaire <?= $i; ?></h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-500 text-sm mb-4">
                                <i class="far fa-calendar-alt mr-2"></i> <?= date("d/m/Y", strtotime("-$i week")); ?>
                            </p>
                            <p class="text-gray-700 mb-6 line-clamp-3">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl vel ultricies lacinia, nisl nisl aliquam nisl, eget aliquam nisl nisl sit amet nisl.
                            </p>
                            <a href="#" class="inline-flex items-center text-violet hover:text-violet/80">
                                Lire la suite <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-lightgray border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
            <nav class="-mx-5 -my-2 flex flex-wrap justify-center" aria-label="Footer">
                <div class="px-5 py-2">
                    <a href="../index.php" class="text-base text-gray-600 hover:text-violet">
                        Accueil
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="../ateliers.php" class="text-base text-gray-600 hover:text-violet">
                        Ateliers d'équité
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="../coaching.php" class="text-base text-gray-600 hover:text-violet">
                        Coaching
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="../ecriture.php" class="text-base text-gray-600 hover:text-violet">
                        Ateliers d'écriture
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="../blog.php" class="text-base text-gray-600 hover:text-violet">
                        Blog
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="../about.html" class="text-base text-gray-600 hover:text-violet">
                        À propos
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="../contact.html" class="text-base text-gray-600 hover:text-violet">
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