<?php
session_start();
require("../backend/utils/ConnectToBDD.php");

$sql = "SELECT * FROM avis";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Ligne 13 - Équité femmes & hommes dans la tech</title>
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
            height: 100vh; /* Exactement la hauteur de l'écran */
            min-height: 600px; /* Hauteur minimale pour les petits écrans */
        }
        .metro-line {
            height: 8px;
            background-color: #330c59;
            position: relative;
        }
        .metro-station {
            width: 16px;
            height: 16px;
            background-color: #330c59;
            border-radius: 50%;
            position: absolute;
            top: -4px;
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

        /* SVG Animation */
        .metro-svg .main-line {
            stroke-dasharray: 700;
            stroke-dashoffset: 700;
            transition: stroke-dashoffset 1.5s ease;
        }
        .metro-svg.animate-visible .main-line {
            stroke-dashoffset: 0;
        }
        .metro-svg circle, .metro-svg text {
            opacity: 0;
            transform: scale(0.8);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .metro-svg.animate-visible circle, .metro-svg.animate-visible text {
            opacity: 1;
            transform: scale(1);
        }
        .metro-svg .station-1 {
            transition-delay: 0.3s;
        }
        .metro-svg .station-2 {
            transition-delay: 0.6s;
        }
        .metro-svg .station-3 {
            transition-delay: 0.9s;
        }
        .metro-svg .station-4 {
            transition-delay: 1.2s;
        }
        .metro-svg .station-5 {
            transition-delay: 1.5s;
        }
        .metro-svg .symbol {
            opacity: 0;
            transition: opacity 0.5s ease;
            transition-delay: 1.8s;
        }
        .metro-svg.animate-visible .symbol {
            opacity: 1;
        }

        /* Card hover animation */
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Play button */
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background-color: rgba(255, 235, 91, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .play-button:hover {
            transform: translate(-50%, -50%) scale(1.1);
            background-color: rgba(255, 235, 91, 1);
        }
        .play-button i {
            color: #330c59;
            font-size: 30px;
            margin-left: 5px;
        }
        
        /* Logo slider */
        .logo-slider {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }
        .logo-slide {
            display: inline-block;
            animation: slide 30s linear infinite;
        }
        @keyframes slide {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-100%);
            }
        }
        
        /* Hero video container */
        .hero-video-container {
            position: relative;
            height: 300px;
            width: 100%;
            max-width: 540px;
            margin: 0 auto;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        /* Testimonial carousel */
        .testimonial-carousel {
            position: relative;
            overflow: hidden;
        }
        .testimonial-container {
            display: flex;
            transition: transform 0.5s ease;
        }
        .testimonial-slide {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            transition: all 0.3s ease;
        }
        .carousel-control:hover {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .carousel-control.prev {
            left: 10px;
        }
        .carousel-control.next {
            right: 10px;
        }
        .carousel-indicators {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ccc;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .carousel-dot.active {
            background-color: #330c59;
        }
        .testimonial-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin: 0.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .testimonial-quote {
            position: relative;
            padding-left: 1.5rem;
        }
        .testimonial-quote::before {
            content: """;
            position: absolute;
            left: 0;
            top: -10px;
            font-size: 3rem;
            color: #e4c9e5;
            font-family: serif;
            line-height: 1;
        }
        
        @media (min-width: 768px) {
            .testimonial-slide {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (min-width: 1024px) {
            .testimonial-slide {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
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
                        <a href="index.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                        <a href="about.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
                        <a href="contact.php" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
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
                <a href="index.php" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
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
                <a href="about.php" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    À propos
                </a>
                <a href="contact.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-extrabold tracking-tight text-jaune sm:text-5xl md:text-6xl lg:text-7xl animate-hidden animate-element">
                        <span class="block">Chaque chemin commence</span>
                        <span class="block text-white mt-2">par une ligne !</span>
                    </h1>
                    <p class="mt-6 max-w-2xl mx-auto lg:mx-0 text-base text-gray-300 sm:text-lg md:text-xl animate-hidden animate-element animate-delay-100">
                        La solution agile et accessible qui transforme les mentalités et développe le leadership féminin.
                        <br>Partout en France et à l'international.
                    </p>
                    
                    <div class="mt-8 flex justify-center lg:justify-start">
                        <a href="#services" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 shadow-md animate-hidden animate-element animate-delay-200">
                            Découvrir nos services
                        </a>
                    </div>
                </div>
                
                <div class="hero-video-container animate-hidden animate-element animate-delay-200">
                    <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80" alt="La Ligne 13" class="w-full h-full object-cover">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logos Section -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 mb-8">Ils nous font confiance</p>
            <div class="logo-slider">
                <div class="logo-slide">
                    <div class="flex justify-around items-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Capgemini_Logo.svg/2560px-Capgemini_Logo.svg.png" alt="Capgemini" class="h-12 mx-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Logo_of_Orange_Bank.svg/2560px-Logo_of_Orange_Bank.svg.png" alt="Orange Bank" class="h-10 mx-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/2560px-Google_2015_logo.svg.png" alt="Google" class="h-8 mx-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/2560px-Amazon_logo.svg.png" alt="Amazon" class="h-8 mx-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/08/Sanofi_logo.svg/2560px-Sanofi_logo.svg.png" alt="Sanofi" class="h-8 mx-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/96/Microsoft_logo_%282012%29.svg/2560px-Microsoft_logo_%282012%29.svg.png" alt="Microsoft" class="h-8 mx-8 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Heading -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl md:text-5xl animate-hidden animate-element">
                    DE QUELLE LIGNE AVEZ-VOUS<br>BESOIN AUJOURD'HUI ?
                </h2>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div id="services" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                <!-- Service 1 -->
                <div class="bg-white rounded-lg shadow-xl overflow-hidden animate-hidden animate-element">
                    <div class="h-48 bg-violet relative">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Atelier d'équité" class="w-full h-full object-cover opacity-70">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <h3 class="text-2xl font-bold text-white">Bouger les lignes</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">
                            Ateliers pour faire bouger les mentalités sur l'équité femmes & hommes dans la tech et ailleurs.
                        </p>
                        <p class="text-gray-700 mb-6">
                            Vos équipes ne se comprennent plus ? Vous voulez relever les challenges de demain avec les femmes et les hommes ?
                        </p>
                        <div class="mt-6">
                            <a href="ateliers.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-violet hover:bg-violet/90 w-full">
                                Découvrir les ateliers
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="bg-white rounded-lg shadow-xl overflow-hidden animate-hidden animate-element animate-delay-100">
                    <div class="h-48 bg-violet relative">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2076&q=80" alt="Coaching en leadership féminin" class="w-full h-full object-cover opacity-70">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <h3 class="text-2xl font-bold text-white">Lire entre les lignes</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">
                            Coaching en leadership féminin pour développer votre confiance et affirmer votre place.
                        </p>
                        <p class="text-gray-700 mb-6">
                            Vous souhaitez Oser ? Prendre confiance ? Trouver votre place dans un environnement professionnel ?
                        </p>
                        <div class="mt-6">
                            <a href="coaching.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-violet hover:bg-violet/90 w-full">
                                Découvrir le coaching
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="bg-white rounded-lg shadow-xl overflow-hidden animate-hidden animate-element animate-delay-200">
                    <div class="h-48 bg-violet relative">
                        <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2073&q=80" alt="Atelier d'écriture" class="w-full h-full object-cover opacity-70">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <h3 class="text-2xl font-bold text-white">Écrire sur des lignes</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">
                            Ateliers coaching par l'écriture pour vous exprimer et libérer votre potentiel.
                        </p>
                        <p class="text-gray-700 mb-6">
                            Vous souhaitez vous exprimer, mais n'osez pas franchir la porte d'une coach ? Déposer ? Libérer le trop plein ?
                        </p>
                        <div class="mt-6">
                            <a href="ecriture.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-violet hover:bg-violet/90 w-full">
                                Découvrir les ateliers
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metro Line SVG -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Notre parcours vers l'équité
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Suivez la ligne 13 pour transformer votre environnement professionnel
                </p>
            </div>
            
            <div class="metro-svg animate-hidden animate-element animate-delay-200">
                <svg viewBox="0 0 800 120" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <!-- Main Line -->
                    <line x1="50" y1="60" x2="750" y2="60" stroke="#330c59" stroke-width="8" class="main-line" />
                    
                    <!-- Stations -->
                    <circle cx="100" cy="60" r="12" fill="#330c59" class="station-1" />
                    <text x="100" y="90" text-anchor="middle" fill="#333333" font-size="14" class="station-1">Sensibilisation</text>
                    
                    <circle cx="250" cy="60" r="12" fill="#330c59" class="station-2" />
                    <text x="250" y="90" text-anchor="middle" fill="#  class="station-2" />
                    <text x="250" y="90" text-anchor="middle" fill="#333333" font-size="14" class="station-2">Équité</text>
                    
                    <circle cx="400" cy="60" r="12" fill="#330c59" class="station-3" />
                    <text x="400" y="90" text-anchor="middle" fill="#333333" font-size="14" class="station-3">Leadership</text>
                    
                    <circle cx="550" cy="60" r="12" fill="#330c59" class="station-4" />
                    <text x="550" y="90" text-anchor="middle" fill="#333333" font-size="14" class="station-4">Expression</text>
                    
                    <circle cx="700" cy="60" r="12" fill="#330c59" class="station-5" />
                    <text x="700" y="90" text-anchor="middle" fill="#333333" font-size="14" class="station-5">Transformation</text>
                    
                    <!-- Female and Male Symbols -->
                    <g class="symbol">
                        <circle cx="175" cy="30" r="10" stroke="#f9a8c9" stroke-width="2" fill="none" />
                        <line x1="175" y1="40" x2="175" y2="60" stroke="#f9a8c9" stroke-width="2" />
                    </g>
                    
                    <g class="symbol">
                        <circle cx="325" cy="30" r="10" stroke="#330c59" stroke-width="2" fill="none" />
                        <line x1="325" y1="40" x2="325" y2="60" stroke="#330c59" stroke-width="2" />
                        <line x1="315" y1="20" x2="335" y2="20" stroke="#330c59" stroke-width="2" />
                    </g>
                    
                    <g class="symbol">
                        <circle cx="475" cy="30" r="10" stroke="#f9a8c9" stroke-width="2" fill="none" />
                        <line x1="475" y1="40" x2="475" y2="60" stroke="#f9a8c9" stroke-width="2" />
                    </g>
                    
                    <g class="symbol">
                        <circle cx="625" cy="30" r="10" stroke="#330c59" stroke-width="2" fill="none" />
                        <line x1="625" y1="40" x2="625" y2="60" stroke="#330c59" stroke-width="2" />
                        <line x1="615" y1="20" x2="635" y2="20" stroke="#330c59" stroke-width="2" />
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <!-- Testimonials Carousel -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Ce qu'ils disent de nous
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Découvrez les témoignages de nos clients qui ont transformé leur environnement professionnel
                </p>
            </div>
            
            <div class="testimonial-carousel animate-hidden animate-element animate-delay-200 relative">
                <div class="testimonial-container">
                    <?php foreach ($avis as $avisItem): ?>
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <div class="flex items-center mb-4">
                                    <img src="https://randomuser.me/api/portraits/men/<?php echo $avisItem['id_user']; ?>.jpg" alt="Utilisateur" class="w-12 h-12 rounded-full mr-4">
                                    <div>
                                        <h4 class="font-bold text-gray-800">Utilisateur <?php echo $avisItem['id_user']; ?></h4>
                                        <p class="text-sm text-gray-600">Date: <?php echo date("d/m/Y", strtotime($avisItem['date_publication'])); ?></p>
                                    </div>
                                </div>
                                <div class="testimonial-quote">
                                    <p class="text-gray-700"><?php echo htmlspecialchars($avisItem['texte']); ?></p>
                                </div>
                                <div class="mt-4 flex">
                                    <?php
                                    $note = intval($avisItem['note']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $note) {
                                            echo '<i class="fas fa-star text-jaune"></i>';
                                        } else {
                                            echo '<i class="far fa-star text-jaune"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Carousel Controls -->
                <div class="carousel-control prev">
                    <i class="fas fa-chevron-left text-violet"></i>
                </div>
                <div class="carousel-control next">
                    <i class="fas fa-chevron-right text-violet"></i>
                </div>
                
                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    <div class="carousel-dot active"></div>
                    <div class="carousel-dot"></div>
                    <div class="carousel-dot"></div>
                    <div class="carousel-dot"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-violet rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4 animate-hidden animate-element">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-jaune sm:text-4xl">
                            <span class="block">Restez informé(e)</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-mauve">
                            Inscrivez-vous à notre newsletter pour recevoir nos actualités et les dates de nos prochains ateliers.
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
        
        // Play button click
        document.querySelector('.play-button').addEventListener('click', function() {
            alert('Vidéo de présentation de La Ligne 13');
            // Ici vous pourriez remplacer par un code pour lancer une vidéo
        });
        
        // Testimonial carousel
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.testimonial-container');
            const slides = document.querySelectorAll('.testimonial-slide');
            const prevBtn = document.querySelector('.carousel-control.prev');
            const nextBtn = document.querySelector('.carousel-control.next');
            const dots = document.querySelectorAll('.carousel-dot');
            
            let currentIndex = 0;
            let slideWidth = 100;
            let slidesToShow = 1;
            
            // Determine how many slides to show based on screen width
            function updateSlidesToShow() {
                if (window.innerWidth >= 1024) {
                    slidesToShow = 3;
                } else if (window.innerWidth >= 768) {
                    slidesToShow = 2;
                } else {
                    slidesToShow = 1;
                }
                
                // Update slide width
                slideWidth = 100 / slidesToShow;
                slides.forEach(slide => {
                    slide.style.flex = `0 0 ${slideWidth}%`;
                    slide.style.maxWidth = `${slideWidth}%`;
                });
                
                // Move to current slide
                goToSlide(currentIndex);
            }
            
            function goToSlide(index) {
                if (index < 0) {
                    index = slides.length - slidesToShow;
                } else if (index > slides.length - slidesToShow) {
                    index = 0;
                }
                
                currentIndex = index;
                container.style.transform = `translateX(-${currentIndex * slideWidth}%)`;
                
                // Update dots
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }
            
            // Event listeners
            prevBtn.addEventListener('click', () => {
                goToSlide(currentIndex - 1);
            });
            
            nextBtn.addEventListener('click', () => {
                goToSlide(currentIndex + 1);
            });
            
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    goToSlide(i);
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', updateSlidesToShow);
            
            // Initialize
            updateSlidesToShow();
            
            // Auto slide every 5 seconds
            setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 5000);
        });
    </script>
</body>
</html>