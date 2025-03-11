<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - La Ligne 13</title>
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
        
        /* Form input animation */
        .form-input {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-input input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: white;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-input input:focus {
            outline: none;
            border-color: #330c59;
            box-shadow: 0 0 0 3px rgba(51, 12, 89, 0.1);
        }
        .form-input label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4a5568;
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
                        <a href="contact.html" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
                            Contactez-nous
                        </a>
                        <a href="login.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium ml-4">
                            Connexion
                        </a>
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
                <a href="blog.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Blog
                </a>
                <a href="about.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <a href="contact.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Contact
                </a>
                <a href="login.php" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Connexion
                </a>
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
                <h1 class="text-4xl font-extrabold tracking-tight text-jaune sm:text-5xl md:text-6xl animate-hidden animate-element">
                    <span class="block">Inscription</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg md:text-xl animate-hidden animate-element animate-delay-100">
                    Créez votre compte pour accéder à tous nos services et réserver vos ateliers.
                </p>
            </div>
        </div>
    </div>

    <!-- Registration Form Section -->
    <div class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl p-8 animate-hidden animate-element">
                <h2 class="text-2xl font-bold text-violet mb-6 text-center">Créez votre compte</h2>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <form action="../backend/user/RegisterUser.php" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-input">
                            <label for="name">Nom :</label>
                            <input type="text" id="name" name="name" placeholder="Entrez votre nom" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="firstname">Prénom :</label>
                            <input type="text" id="firstname" name="firstname" placeholder="Entrez votre prénom" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="birthday">Date d'anniversaire :</label>
                            <input type="date" id="birthday" name="birthday" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="job">Profession :</label>
                            <input type="text" id="job" name="job" placeholder="Entrez votre profession" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="phone">Téléphone :</label>
                            <input type="tel" id="phone" name="phone" placeholder="Entrez votre téléphone" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="password">Mot de passe :</label>
                            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                        </div>
                        
                        <div class="form-input">
                            <label for="confirm_password">Confirmation de mot de passe :</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
                        </div>
                    </div>
                    
                    <div class="mt-6 mb-6 flex items-center">
                        <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-violet focus:ring-violet border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-600">
                            J'accepte les conditions générales d'utilisation et la politique de confidentialité de La Ligne 13.
                        </label>
                    </div>
                    
                    <div class="mb-6 text-sm text-center">
                        <a href="login.php" class="text-violet hover:text-violet/80">
                            Vous avez déjà un compte ? Connectez-vous ici !
                        </a>
                    </div>
                    
                    <button type="submit" class="w-full bg-violet text-white py-3 px-4 rounded-md hover:bg-violet/90 transition-colors">
                        S'inscrire
                    </button>
                </form>
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
        
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>