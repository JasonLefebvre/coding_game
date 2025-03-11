<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - La Ligne 13</title>
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
            height: 70vh;
            min-height: 500px;
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

        /* Form input animation */
        .form-input {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-input input,
        .form-input textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: white;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-input input:focus,
        .form-input textarea:focus {
            outline: none;
            border-color: #330c59;
            box-shadow: 0 0 0 3px rgba(51, 12, 89, 0.1);
        }
        .form-input label {
            position: absolute;
            left: 0.75rem;
            top: 0.75rem;
            color: #718096;
            pointer-events: none;
            transition: transform 0.3s ease, font-size 0.3s ease, color 0.3s ease;
        }
        .form-input input:focus + label,
        .form-input textarea:focus + label,
        .form-input input:not(:placeholder-shown) + label,
        .form-input textarea:not(:placeholder-shown) + label {
            transform: translateY(-1.5rem);
            font-size: 0.75rem;
            color: #330c59;
        }

        /* Contact info card animation */
        .contact-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .contact-icon {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .contact-card:hover .contact-icon {
            transform: scale(1.1);
            background-color: #330c59;
            color: white;
        }

        /* Map animation */
        .map-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .map-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(51, 12, 89, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .map-container:hover .map-overlay {
            opacity: 1;
        }
        .map-button {
            background-color: #330c59;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .map-container:hover .map-button {
            transform: translateY(0);
            opacity: 1;
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
                        <span class="text-violet text-2xl font-bold">La Ligne 13</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Accueil
                        </a>
                        <a href="ateliers.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'équité
                        </a>
                        <a href="coaching.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Coaching
                        </a>
                        <a href="ecriture.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'écriture
                        </a>
                        <a href="blog.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Blog
                        </a>
                        <a href="about.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="contact.html" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-violet hover:bg-violet/90">
                        Contactez-nous
                    </a>
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
                <a href="index.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Accueil
                </a>
                <a href="ateliers.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'équité
                </a>
                <a href="coaching.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Coaching
                </a>
                <a href="ecriture.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'écriture
                </a>
                <a href="blog.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Blog
                </a>
                <a href="about.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <a href="contact.html" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Contact
                </a>
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
                    <span class="block">Contactez-nous</span>
                    <span class="block text-white mt-2">Parlons de vos projets</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg md:text-xl animate-hidden animate-element animate-delay-100">
                    Vous souhaitez en savoir plus sur nos services ou discuter de vos besoins ?
                    <br>Nous sommes à votre écoute.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                <!-- Contact Form -->
                <div class="animate-hidden animate-element">
                    <h2 class="text-3xl font-extrabold text-violet mb-8">Envoyez-nous un message</h2>
                    
                    <form action="#" method="POST" class="space-y-6">
                        <div class="form-input">
                            <input type="text" id="name" name="name" placeholder=" " required>
                            <label for="name">Nom complet</label>
                        </div>
                        
                        <div class="form-input">
                            <input type="email" id="email" name="email" placeholder=" " required>
                            <label for="email">Adresse email</label>
                        </div>
                        
                        <div class="form-input">
                            <input type="tel" id="phone" name="phone" placeholder=" ">
                            <label for="phone">Téléphone (optionnel)</label>
                        </div>
                        
                        <div class="form-input">
                            <select id="subject" name="subject" class="w-full p-3 border-2 border-gray-200 rounded-md focus:outline-none focus:border-violet focus:ring-1 focus:ring-violet">
                                <option value="" disabled selected>Choisissez un sujet</option>
                                <option value="ateliers">Ateliers d'équité</option>
                                <option value="coaching">Coaching</option>
                                <option value="ecriture">Ateliers d'écriture</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-input">
                            <textarea id="message" name="message" rows="4" placeholder=" " required></textarea>
                            <label for="message">Votre message</label>
                        </div>
                        
                        <div class="flex items-start">
                            <input id="privacy" name="privacy" type="checkbox" class="h-4 w-4 text-violet focus:ring-violet border-gray-300 rounded mt-1" required>
                            <label for="privacy" class="ml-2 block text-sm text-gray-600">
                                J'accepte que mes données soient traitées conformément à la politique de confidentialité de La Ligne 13.
                            </label>
                        </div>
                        
                        <div>
                            <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-violet hover:bg-violet/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet">
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Contact Info -->
                <div class="mt-12 lg:mt-0 animate-hidden animate-element animate-delay-200">
                    <h2 class="text-3xl font-extrabold text-violet mb-8">Nos coordonnées</h2>
                    
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="contact-card bg-lightgray p-6 rounded-lg flex items-start">
                            <div class="contact-icon bg-white p-3 rounded-full mr-4 text-violet">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-violet mb-1">Adresse</h3>
                                <p class="text-gray-600">
                                    13 Rue de la Ligne<br>
                                    75013 Paris, France
                                </p>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="contact-card bg-lightgray p-6 rounded-lg flex items-start">
                            <div class="contact-icon bg-white p-3 rounded-full mr-4 text-violet">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-violet mb-1">Email</h3>
                                <p class="text-gray-600">
                                    <a href="mailto:contact@laligne13.fr" class="hover:text-violet">contact@laligne13.fr</a>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="contact-card bg-lightgray p-6 rounded-lg flex items-start">
                            <div class="contact-icon bg-white p-3 rounded-full mr-4 text-violet">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-violet mb-1">Téléphone</h3>
                                <p class="text-gray-600">
                                    <a href="tel:+33123456789" class="hover:text-violet">+33 1 23 45 67 89</a>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Social Media -->
                        <div class="contact-card bg-lightgray p-6 rounded-lg flex items-start">
                            <div class="contact-icon bg-white p-3 rounded-full mr-4 text-violet">
                                <i class="fas fa-share-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-violet mb-1">Réseaux sociaux</h3>
                                <div class="flex space-x-4 mt-2">
                                    <a href="https://www.linkedin.com/in/audrey-rebout-9144b7162/" class="text-violet hover:text-violet/80">
                                        <i class="fab fa-linkedin text-2xl"></i>
                                    </a>
                                    <a href="https://www.instagram.com/la_ligne_13.coaching/" class="text-violet hover:text-violet/80">
                                        <i class="fab fa-instagram text-2xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Nous trouver
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Venez nous rencontrer dans nos locaux
                </p>
            </div>
            
            <div class="map-container animate-hidden animate-element animate-delay-200">
                <img src="https://maps.googleapis.com/maps/api/staticmap?center=48.8291,2.3716&zoom=15&size=1200x400&markers=color:purple%7C48.8291,2.3716&key=YOUR_API_KEY" alt="Carte de localisation" class="w-full h-96 object-cover">
                <div class="map-overlay">
                    <a href="https://goo.gl/maps/1234567890" target="_blank" class="map-button">
                        Ouvrir dans Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Questions fréquentes
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Vous avez des questions ? Nous avons des réponses.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="space-y-8">
                    <!-- FAQ Item 1 -->
                    <div class="bg-lightgray p-6 rounded-lg shadow-md animate-hidden animate-element">
                        <h3 class="text-xl font-bold text-violet mb-2">Quels sont vos délais de réponse ?</h3>
                        <p class="text-gray-700">
                            Nous nous engageons à répondre à toutes les demandes dans un délai de 48 heures ouvrées. Pour les demandes urgentes, n'hésitez pas à nous contacter par téléphone.
                        </p>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="bg-lightgray p-6 rounded-lg shadow-md animate-hidden animate-element animate-delay-100">
                        <h3 class="text-xl font-bold text-violet mb-2">Proposez-vous des interventions en dehors de Paris ?</h3>
                        <p class="text-gray-700">
                            Oui, nous intervenons dans toute la France et proposons également des formats à distance pour certains de nos services. N'hésitez pas à nous contacter pour discuter de vos besoins spécifiques.
                        </p>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="bg-lightgray p-6 rounded-lg shadow-md animate-hidden animate-element animate-delay-200">
                        <h3 class="text-xl font-bold text-violet mb-2">Comment se déroule un premier rendez-vous ?</h3>
                        <p class="text-gray-700">
                            Le premier rendez-vous est un temps d'échange pour comprendre vos besoins et vous présenter nos services. Il peut se faire en présentiel dans nos locaux ou à distance, selon votre préférence. Ce premier rendez-vous est gratuit et sans engagement.
                        </p>
                    </div>
                </div>
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
                            Inscrivez-vous à notre newsletter pour recevoir nos actualités, conseils et invitations à nos événements.
                        </p>
                        <div class="mt-8">
                            <form action="#" method="POST" class="sm:flex">
                                <label for="email-address" class="sr-only">Adresse email</label>
                                <input id="email-address" name="email" type="email" autocomplete="email" required class="w-full px-5 py-3 border border-transparent placeholder-gray-500 focus:ring-2 focus:ring-offset-2 focus:ring-offset-violet focus:ring-white focus:border-white sm:max-w-xs rounded-md" placeholder="Votre adresse email">
                                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                                    <button type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jaune">
                                        S'inscrire
                                    </button>
                                </div>
                            </form>
                        </div>
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
                    <a href="index.html" class="text-base text-gray-600 hover:text-violet">
                        Accueil
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="ateliers.html" class="text-base text-gray-600 hover:text-violet">
                        Ateliers d'équité
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="coaching.php" class="text-base text-gray-600 hover:text-violet">
                        Coaching
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="ecriture.html" class="text-base text-gray-600 hover:text-violet">
                        Ateliers d'écriture
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="blog.html" class="text-base text-gray-600 hover:text-violet">
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
            
            // Form input animation
            const formInputs = document.querySelectorAll('.form-input input, .form-input textarea');
            
            formInputs.forEach(input => {
                // Check if input has value on load
                if (input.value !== '') {
                    input.nextElementSibling.classList.add('transform', 'translateY(-1.5rem)', 'text-xs', 'text-violet');
                }
                
                // Add event listeners
                input.addEventListener('focus', function() {
                    this.nextElementSibling.classList.add('transform', 'translateY(-1.5rem)', 'text-xs', 'text-violet');
                });
                
                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.nextElementSibling.classList.remove('transform', 'translateY(-1.5rem)', 'text-xs', 'text-violet');
                    }
                });
            });
        });
    </script>
</body>
</html>