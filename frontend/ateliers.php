<?php
require("../backend/utils/ConnectToBDD.php");

if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// Obtenir la date actuelle
$today = date("Y-m-d");

// Récupérer uniquement les ateliers à venir
$query_future = "SELECT id, nom, description, date, heure_debut, heure_fin, type 
                 FROM atelier_equite 
                 WHERE date >= '" . date("Y-m-d") . "' 
                 ORDER BY date ASC";

$stmt_future = $pdo->query($query_future);
$ateliers_futurs = $stmt_future->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les ateliers passés
$query_past = "SELECT id, nom, description, date, heure_debut, heure_fin, type 
               FROM atelier_equite 
               WHERE date < :today 
               ORDER BY date DESC";
$stmt_past = $pdo->prepare($query_past);
$stmt_past->bindParam(':today', $today, PDO::PARAM_STR);
$stmt_past->execute();
$ateliers_passes = $stmt_past->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ateliers d'équité - La Ligne 13</title>
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

        /* Card hover animation */
        .workshop-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .workshop-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Timeline animation */
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 2rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #330c59;
            opacity: 0;
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 1s ease, opacity 1s ease;
        }
        .timeline-item.animate-visible::before {
            opacity: 1;
            transform: scaleY(1);
        }
        .timeline-dot {
            position: absolute;
            left: -8px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #330c59;
            opacity: 0;
            transform: scale(0);
            transition: transform 0.5s ease, opacity 0.5s ease;
            transition-delay: 0.5s;
        }
        .timeline-item.animate-visible .timeline-dot {
            opacity: 1;
            transform: scale(1);
        }
        
        /* Counter animation */
        .counter-value {
            display: inline-block;
            font-weight: bold;
            font-size: 3rem;
            color: #330c59;
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
                        <a href="ateliers.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                <a href="about.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
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
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-jaune sm:text-5xl md:text-6xl animate-hidden animate-element">
                    <span class="block">Bouger les lignes</span>
                    <span class="block text-white mt-2">Ateliers d'équité</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg md:text-xl animate-hidden animate-element animate-delay-100">
                    Des ateliers pour faire bouger les mentalités sur l'équité femmes & hommes dans la tech et ailleurs.
                    <br>Transformez votre culture d'entreprise et libérez le potentiel de vos équipes.
                </p>
                
                <div class="mt-8 flex justify-center">
                    <a href="#workshops" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 shadow-md animate-hidden animate-element animate-delay-200">
                        Découvrir nos ateliers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    L'impact de nos ateliers
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Des résultats concrets pour transformer votre environnement professionnel
                </p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-lightgray p-6 rounded-lg text-center animate-hidden animate-element">
                    <div class="counter-value" data-target="87">0</div>
                    <p class="text-gray-600 mt-2">% de satisfaction client</p>
                </div>
                
                <div class="bg-lightgray p-6 rounded-lg text-center animate-hidden animate-element animate-delay-100">
                    <div class="counter-value" data-target="42">0</div>
                    <p class="text-gray-600 mt-2">entreprises accompagnées</p>
                </div>
                
                <div class="bg-lightgray p-6 rounded-lg text-center animate-hidden animate-element animate-delay-200">
                    <div class="counter-value" data-target="1500">0</div>
                    <p class="text-gray-600 mt-2">personnes formées</p>
                </div>
                
                <div class="bg-lightgray p-6 rounded-lg text-center animate-hidden animate-element animate-delay-300">
                    <div class="counter-value" data-target="35">0</div>
                    <p class="text-gray-600 mt-2">% d'augmentation de la mixité</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Workshops Section - Ateliers à venir -->
    <div id="workshops" class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Nos ateliers à venir
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Découvrez et réservez nos prochains ateliers !
                </p>
            </div>

            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                <?php if (!empty($ateliers_futurs)): ?>
                    <?php foreach ($ateliers_futurs as $index => $atelier): ?>
                        <div class="bg-white rounded-lg shadow-xl overflow-hidden workshop-card animate-hidden animate-element animate-delay-<?= ($index % 3) * 100; ?>">
                            <div class="h-48 bg-violet relative">
                                <img src="../src/img/workshop<?= htmlspecialchars($atelier['id']); ?>.jpg" 
                                     alt="<?= htmlspecialchars($atelier['nom']); ?>" 
                                     class="w-full h-full object-cover opacity-70">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white">
                                        <?= htmlspecialchars($atelier['nom']); ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">
                                    <span class="font-bold">Date :</span> <?= date("d/m/Y", strtotime($atelier['date'])); ?><br>
                                    <span class="font-bold">Heure :</span> <?= date("H:i", strtotime($atelier['heure_debut'])); ?> - <?= date("H:i", strtotime($atelier['heure_fin'])); ?><br>
                                    <span class="font-bold">Lieu :</span> <?= htmlspecialchars($atelier['type']); ?>
                                </p>
                                <p class="text-gray-700 mb-6">
                                    <?= htmlspecialchars($atelier['description']); ?>
                                </p>
                                <div class="mt-6">
                                    <a href="reservation_equite.php?id=<?= htmlspecialchars($atelier['id']); ?>" 
                                       class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 w-full">
                                        Réserver ma place
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center">
                        <p class="text-gray-500 text-lg">Aucun atelier disponible pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Section des dernières sessions -->
    <div id="past-workshops" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Les dernières sessions
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Retrouvez les ateliers précédents organisés par La Ligne 13.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                <?php if (!empty($ateliers_passes)): ?>
                    <?php foreach ($ateliers_passes as $index => $atelier): ?>
                        <div class="bg-gray-100 rounded-lg shadow-xl overflow-hidden workshop-card animate-hidden animate-element animate-delay-<?= ($index % 3) * 100; ?>">
                            <div class="h-48 bg-violet relative">
                                <img src="../src/img/workshop<?= $atelier['id']; ?>.jpg" alt="<?= htmlspecialchars($atelier['nom']); ?>" class="w-full h-full object-cover opacity-70">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white"><?= htmlspecialchars($atelier['nom']); ?></h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 mb-4">
                                    <span class="font-bold">Date :</span> <?= date("d/m/Y", strtotime($atelier['date'])); ?><br>
                                    <span class="font-bold">Heure :</span> <?= date("H:i", strtotime($atelier['heure_debut'])); ?> - <?= date("H:i", strtotime($atelier['heure_fin'])); ?><br>
                                    <span class="font-bold">Lieu :</span> <?= htmlspecialchars($atelier['type']); ?>
                                </p>
                                <p class="text-gray-700 mb-6">
                                    <?= htmlspecialchars($atelier['description']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center">
                        <p class="text-gray-500 text-lg">Aucune session passée disponible.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Process Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Notre méthodologie
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Un processus éprouvé pour des résultats concrets
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <!-- Timeline Item 1 -->
                <div class="timeline-item animate-hidden animate-element">
                    <div class="timeline-dot"></div>
                    <h3 class="text-xl font-bold text-violet mb-2">1. Diagnostic</h3>
                    <p class="text-gray-700 mb-4">
                        Nous commençons par un diagnostic approfondi de votre situation actuelle : culture d'entreprise, pratiques existantes, défis spécifiques. Cette étape nous permet de personnaliser notre approche à vos besoins.
                    </p>
                </div>
                
                <!-- Timeline Item 2 -->
                <div class="timeline-item animate-hidden animate-element animate-delay-100">
                    <div class="timeline-dot"></div>
                    <h3 class="text-xl font-bold text-violet mb-2">2. Sensibilisation</h3>
                    <p class="text-gray-700 mb-4">
                        Nous organisons des sessions de sensibilisation adaptées à votre contexte pour créer une base commune de compréhension des enjeux d'équité femmes-hommes dans votre secteur.
                    </p>
                </div>
                
                <!-- Timeline Item 3 -->
                <div class="timeline-item animate-hidden animate-element animate-delay-200">
                    <div class="timeline-dot"></div>
                    <h3 class="text-xl font-bold text-violet mb-2">3. Co-construction</h3>
                    <p class="text-gray-700 mb-4">
                        Nous impliquons vos équipes dans la co-construction de solutions adaptées à votre réalité. Cette approche participative garantit l'adhésion et l'engagement de tous.
                    </p>
                </div>
                
                <!-- Timeline Item 4 -->
                <div class="timeline-item animate-hidden animate-element animate-delay-300">
                    <div class="timeline-dot"></div>
                    <h3 class="text-xl font-bold text-violet mb-2">4. Mise en œuvre</h3>
                    <p class="text-gray-700 mb-4">
                        Nous vous accompagnons dans la mise en œuvre des actions définies, avec des outils concrets et des indicateurs de suivi pour mesurer les progrès.
                    </p>
                </div>
                
                <!-- Timeline Item 5 -->
                <div class="timeline-item animate-hidden animate-element animate-delay-400">
                    <div class="timeline-dot"></div>
                    <h3 class="text-xl font-bold text-violet mb-2">5. Suivi et ajustement</h3>
                    <p class="text-gray-700 mb-4">
                        Nous assurons un suivi régulier pour évaluer l'impact des actions mises en place et ajuster si nécessaire. Notre objectif : des résultats durables.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Ils témoignent
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Ce que nos clients disent de nos ateliers d'équité
                </p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- Testimonial 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md animate-hidden animate-element">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Jean Dupont" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-800">Jean Dupont</h4>
                            <p class="text-sm text-gray-600">DRH, Entreprise Tech</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">
                        "Les ateliers de La Ligne 13 ont transformé notre approche de l'équité. Nos équipes sont plus conscientes des biais inconscients et nous avons mis en place des actions concrètes qui portent déjà leurs fruits."
                    </p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md animate-hidden animate-element animate-delay-100">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Marie Laurent" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-800">Marie Laurent</h4>
                            <p class="text-sm text-gray-600">CEO, Startup innovante</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">
                        "J'ai été impressionnée par la qualité des ateliers et la pertinence des outils proposés. Nous avons pu adapter notre culture d'entreprise dès le départ pour créer un environnement  Nous avons pu adapter notre culture d'entreprise dès le départ pour créer un environnement vraiment inclusif."
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-violet rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4 animate-hidden animate-element">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-jaune sm:text-4xl">
                            <span class="block">Prêt à transformer votre entreprise ?</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-mauve">
                            Contactez-nous pour organiser un atelier d'équité adapté à vos besoins et à votre contexte.
                        </p>
                        <div class="mt-8">
                            <a href="contact.html" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 shadow-md">
                                Réserver un atelier
                            </a>
                        </div>
                    </div>
                </div>
                <div class="relative -mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                    <img class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top sm:translate-x-16 lg:translate-y-20" src="https://images.unsplash.com/photo-1573497620053-ea5300f94f21?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Atelier d'équité">
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
            
            // Counter animation
            const counters = document.querySelectorAll('.counter-value');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000; // ms
                const step = target / (duration / 16); // 60fps
                
                let current = 0;
                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                if (isElementInViewport(counter)) {
                    updateCounter();
                } else {
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            updateCounter();
                            observer.disconnect();
                        }
                    });
                    observer.observe(counter);
                }
            });
        });
    </script>
</body>
</html>