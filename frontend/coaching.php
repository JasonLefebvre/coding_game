<?php
require("../backend/utils/ConnectToBDD.php");

if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// Obtenir la date actuelle
$today = date("Y-m-d");

// Récupérer uniquement les coaching à venir
$query_future = "SELECT id, titre, description, categorie 
                 FROM coaching";

                 $stmt_future = $pdo->query($query_future);
                 $coachings_futurs = $stmt_future->fetchAll(PDO::FETCH_ASSOC);
                 
                 
                
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coaching en leadership féminin - La Ligne 13</title>
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
        .coaching-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .coaching-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Benefit animation */
        .benefit-item {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .benefit-item.animate-visible {
            opacity: 1;
            transform: translateX(0);
        }
        .benefit-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            background-color: #e4c9e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #330c59;
        }
        
        /* Testimonial slider */
        .testimonial-slider {
            position: relative;
            overflow: hidden;
        }
        .testimonial-slides {
            display: flex;
            transition: transform 0.5s ease;
        }
        .testimonial-slide {
            flex: 0 0 100%;
            padding: 1rem;
        }
        .testimonial-controls {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        .testimonial-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ccc;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .testimonial-dot.active {
            background-color: #330c59;
        }
        
        /* Progress bar animation */
        .progress-bar {
            height: 8px;
            background-color: #e4c9e5;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .progress-fill {
            height: 100%;
            background-color: #330c59;
            width: 0;
            transition: width 1.5s ease;
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
                        <a href="coaching.html" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Coaching
                        </a>
                        <a href="ecriture.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'écriture
                        </a>
                        <a href="blog.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Blog
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="contact.html" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
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
                <a href="index.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Accueil
                </a>
                <a href="ateliers.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'équité
                </a>
                <a href="coaching.html" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Coaching
                </a>
                <a href="ecriture.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ateliers d'écriture
                </a>
                <a href="blog.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Blog
                </a>
                <a href="contact.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
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
                    <span class="block">Lire entre les lignes</span>
                    <span class="block text-white mt-2">Coaching en leadership féminin</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg md:text-xl animate-hidden animate-element animate-delay-100">
                    Un accompagnement personnalisé pour développer votre confiance et affirmer votre place.
                    <br>Révélez votre potentiel et transformez votre carrière.
                </p>
                
                <div class="mt-8 flex justify-center">
                    <a href="#coaching" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 shadow-md animate-hidden animate-element animate-delay-200">
                        Découvrir nos programmes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Coaching Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                <div>
                    <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                        Pourquoi le coaching en leadership féminin ?
                    </h2>
                    <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                        Dans un monde professionnel encore marqué par des inégalités, le coaching en leadership féminin vous permet de :
                    </p>
                    
                    <div class="mt-8">
                        <div class="benefit-item animate-hidden animate-element animate-delay-200">
                            <div class="benefit-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Développer votre confiance</h3>
                            <p class="text-gray-600">Renforcez votre estime de soi et votre assurance pour oser prendre votre place.</p>
                        </div>
                        
                        <div class="benefit-item animate-hidden animate-element animate-delay-300">
                            <div class="benefit-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Affirmer votre leadership</h3>
                            <p class="text-gray-600">Développez votre propre style de leadership authentique et impactant.</p>
                        </div>
                        
                        <div class="benefit-item animate-hidden animate-element animate-delay-400">
                            <div class="benefit-icon">
                                <i class="fas fa-chess-queen"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Définir votre stratégie</h3>
                            <p class="text-gray-600">Construisez une vision claire de votre parcours professionnel et des étapes pour y parvenir.</p>
                        </div>
                        
                        <div class="benefit-item animate-hidden animate-element animate-delay-500">
                            <div class="benefit-icon">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Équilibrer vie pro/perso</h3>
                            <p class="text-gray-600">Trouvez votre équilibre personnel pour un épanouissement global et durable.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-10 lg:mt-0 animate-hidden animate-element animate-delay-200">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2076&q=80" alt="Coaching en leadership féminin" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    L'impact de notre coaching
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Des résultats concrets pour votre développement professionnel
                </p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="animate-hidden animate-element">
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="92"></div>
                    </div>
                    <p class="font-bold text-violet text-xl">92%</p>
                    <p class="text-gray-600">des coachées ont obtenu une promotion dans l'année suivant le coaching</p>
                </div>
                
                <div class="animate-hidden animate-element animate-delay-100">
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="85"></div>
                    </div>
                    <p class="font-bold text-violet text-xl">85%</p>
                    <p class="text-gray-600">des participantes rapportent une augmentation significative de leur confiance</p>
                </div>
                
                <div class="animate-hidden animate-element animate-delay-200">
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="78"></div>
                    </div>
                    <p class="font-bold text-violet text-xl">78%</p>
                    <p class="text-gray-600">ont amélioré leur équilibre vie professionnelle/personnelle</p>
                </div>
                
                <div class="animate-hidden animate-element animate-delay-300">
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="95"></div>
                    </div>
                    <p class="font-bold text-violet text-xl">95%</p>
                    <p class="text-gray-600">recommandent notre coaching à leurs collègues et amies</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Coaching Programs Section -->
    <div id="coaching" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Nos programmes de coaching
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Des formats adaptés à vos besoins et à votre agenda
                </p>
            </div>
            
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($coachings_futurs as $coaching) : ?>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden coaching-card animate-hidden animate-element">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-violet"><?php echo htmlspecialchars($coaching['titre']); ?></h3>
                <p class="text-gray-600 mb-4">
                    <span class="font-bold">Catégorie :</span> <?php echo htmlspecialchars($coaching['categorie']); ?>
                </p>
                <p class="text-gray-700 mb-6">
                    <?php echo nl2br(htmlspecialchars($coaching['description'])); ?>
                </p>
                <div class="mt-6">
                    <a href="reservation_coaching.php?id=<?php echo $coaching['id']; ?>" 
                       class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-violet hover:bg-violet/90 w-full">
                        Voir plus
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                    Elles témoignent
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                    Ce que nos coachées disent de leur expérience
                </p>
            </div>
            
            <div class="testimonial-slider animate-hidden animate-element animate-delay-200">
                <div class="testimonial-slides">
                    <!-- Testimonial 1 -->
                    <div class="testimonial-slide">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sophie Martin" class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-bold text-gray-800">Sophie Martin</h4>
                                    <p class="text-sm text-gray-600">Directrice Marketing, Tech Innovate</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Le coaching avec La Ligne 13 a été un véritable tournant dans ma carrière. J'ai appris à affirmer mon leadership et à prendre ma place dans un environnement majoritairement masculin. Résultat : une promotion que je n'aurais jamais osé demander avant !"
                            </p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="testimonial-slide">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Marie Laurent" class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-bold text-gray-800">Marie Laurent</h4>
                                    <p class="text-sm text-gray-600">Ingénieure en chef, Capgemini</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "J'étais constamment en proie au syndrome de l'imposteur. Le coaching m'a permis de reconnaître ma valeur et mes compétences. Aujourd'hui, je dirige une équipe de 15 personnes et je m'épanouis pleinement dans mon rôle de leader."
                            </p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="testimonial-slide">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Émilie Rousseau" class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <h4 class="font-bold text-gray-800">Émilie Rousseau</h4>
                                    <p class="text-sm text-gray-600">Entrepreneure, Fondatrice de EcoTech</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Le programme Excellence m'a accompagnée dans la création de mon entreprise. J'ai appris à équilibrer ambition professionnelle et vie personnelle, tout en développant un leadership authentique. Une expérience transformatrice !"
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-controls">
                    <div class="testimonial-dot active" data-index="0"></div>
                    <div class="testimonial-dot" data-index="1"></div>
                    <div class="testimonial-dot" data-index="2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coach Bio Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                <div class="animate-hidden animate-element">
                    <img src="https://images.unsplash.com/photo-1573497620053-ea5300f94f21?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Audrey Rebout" class="rounded-lg shadow-xl">
                </div>
                
                <div class="mt-10 lg:mt-0">
                    <h2 class="text-3xl font-extrabold text-violet sm:text-4xl animate-hidden animate-element">
                        Votre coach : Audrey Rebout
                    </h2>
                    <p class="mt-4 text-lg text-gray-600 animate-hidden animate-element animate-delay-100">
                        Experte en leadership féminin et en équité femmes-hommes dans la tech
                    </p>
                    
                    <div class="mt-6 text-gray-700 space-y-4 animate-hidden animate-element animate-delay-200">
                        <p>
                            Forte de 15 ans d'expérience dans le secteur de la tech, Audrey a accompagné plus de 200 femmes dans le développement de leur leadership et de leur carrière.
                        </p>
                        <p>
                            Certifiée en coaching professionnel et spécialisée dans les enjeux d'équité femmes-hommes, elle combine expertise technique et approche humaine pour des résultats concrets et durables.
                        </p>
                        <p>
                            Sa mission : permettre à chaque femme de révéler son potentiel et de prendre sa juste place dans le monde professionnel, en particulier dans les secteurs à dominante masculine.
                        </p>
                    </div>
                    
                    <div class="mt-8 flex animate-hidden animate-element animate-delay-300">
                        <a href="https://www.linkedin.com/in/audrey-rebout-9144b7162/" class="text-violet hover:text-violet/80 mr-4">
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

    <!-- CTA Section -->
    <div class="py-16 bg-lightgray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-violet rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4 animate-hidden animate-element">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-jaune sm:text-4xl">
                            <span class="block">Prête à révéler votre potentiel ?</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-mauve">
                            Réservez un appel découverte gratuit de 30 minutes pour discuter de vos objectifs et voir comment nous pouvons vous accompagner.
                        </p>
                        <div class="mt-8">
                            <a href="contact.html" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-violet bg-jaune hover:bg-jaune/90 shadow-md">
                                Réserver un appel découverte
                            </a>
                        </div>
                    </div>
                </div>
                <div class="relative -mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                    <img class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top sm:translate-x-16 lg:translate-y-20" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2076&q=80" alt="Coaching en leadership féminin">
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
                    <a href="coaching.html" class="text-base text-gray-600 hover:text-violet">
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
            
            // Progress bar animation
            const progressBars = document.querySelectorAll('.progress-fill');
            
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        setTimeout(() => {
                            bar.style.width = width + '%';
                        }, 300);
                        observer.disconnect();
                    }
                });
                
                observer.observe(bar);
            });
            
            // Testimonial slider
            const slides = document.querySelector('.testimonial-slides');
            const dots = document.querySelectorAll('.testimonial-dot');
            let currentIndex = 0;
            
            function goToSlide(index) {
                currentIndex = index;
                slides.style.transform = `translateX(-${index * 100}%)`;
                
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
            }
            
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    goToSlide(i);
                });
            });
            
            // Auto slide every 5 seconds
            setInterval(() => {
                currentIndex = (currentIndex + 1) % dots.length;
                goToSlide(currentIndex);
            }, 5000);
        });
    </script>
</body>
</html>