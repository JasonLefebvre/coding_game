<?php
session_start();
require("../backend/utils/ConnectToBDD.php");
require '../stripe/init.php'; // Adjust path if needed

// Vérifier l'ID de l'ebook
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['id'])) {
    header('Location: ebooks.php');
    exit;
}

$ebook_id = (int) $_GET['id'];

// Récupérer les détails de l'ebook
$query = "SELECT * FROM ebooks WHERE id = :id AND is_active = 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $ebook_id, PDO::PARAM_INT);
$stmt->execute();
$ebook = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ebook) {
    header('Location: ebooks.php');
    exit;
}

// Vérifier si l'utilisateur a déjà acheté cet ebook
$hasAlreadyPurchased = false;
if (isset($_SESSION['user_id'])) {
    $query = "SELECT id FROM ebooks_achats WHERE id_user = :id_user AND id_ebook = :id_ebook";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_user', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':id_ebook', $ebook_id, PDO::PARAM_INT);
    $stmt->execute();
    $hasAlreadyPurchased = $stmt->rowCount() > 0;
}

// Stripe API Key (Replace with your own)
\Stripe\Stripe::setApiKey('sk_test_51Q98K001jgFsFXMETPpgvXUboCcKrzpoL1WXF6cnIKt5MGkTvrLB9uI39ziCnt9rNUxB54DYCzWMAOGXyxwP2c2X00yLr8oWTz');

// Convert price to cents for Stripe
$price_in_cents = (int)($ebook['prix'] * 100);

// Create a Payment Intent for Stripe
$payment_intent = \Stripe\PaymentIntent::create([
    'amount' => $price_in_cents,
    'currency' => 'eur',
    'payment_method_types' => ['card'],
    'metadata' => [
        'ebook_id' => $ebook_id,
        'ebook_title' => $ebook['titre']
    ],
]);

$client_secret = $payment_intent->client_secret;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ebook['titre']); ?> - La Ligne 13</title>
    <script src="https://js.stripe.com/v3/"></script>
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
        .ebook-cover {
            height: 400px;
            width: 100%;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 1rem;
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
    <div class="hero-pattern flex items-center">
        <div class="diagonal-line-left"></div>
        <div class="diagonal-line-right"></div>
        <div class="curved-line-bottom"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16">
            <div class="text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-jaune sm:text-4xl md:text-5xl animate-hidden animate-element">
                    <span class="block"><?= htmlspecialchars($ebook['titre']); ?></span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg animate-hidden animate-element animate-delay-100">
                    <?= htmlspecialchars($ebook['auteur']); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- E-book Details Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- E-book Image and Info -->
                <div class="animate-hidden animate-element">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="mb-6">
                            <div class="bg-violet relative rounded-lg overflow-hidden p-4">
                                <?php if (!empty($ebook['image'])): ?>
                                    <img src="<?= htmlspecialchars($ebook['image']); ?>" alt="<?= htmlspecialchars($ebook['titre']); ?>" class="w-full h-[500px] object-contain bg-gray-50 rounded-lg">
                                <?php else: ?>
                                    <div class="w-full h-[500px] flex items-center justify-center bg-gray-50 rounded-lg">
                                        <i class="fas fa-book text-violet text-6xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-violet"><?= number_format($ebook['prix'], 2, ',', ' '); ?>€</span>
                                <span class="bg-mauve text-violet px-3 py-1 rounded-full text-sm"><?= htmlspecialchars($ebook['categorie']); ?></span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-violet mr-2"></i>
                                    <span><?= $ebook['nombre_pages']; ?> pages</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-violet mr-2"></i>
                                    <span>Publié le <?= date("d/m/Y", strtotime($ebook['date_publication'])); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-violet mr-2"></i>
                                    <span>Format <?= htmlspecialchars($ebook['format']); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user text-violet mr-2"></i>
                                    <span>Par <?= htmlspecialchars($ebook['auteur']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- E-book Description and Purchase -->
                <div class="animate-hidden animate-element animate-delay-200">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-violet mb-4">Description</h2>
                        
                        <div class="prose max-w-none mb-8">
                            <p class="text-gray-700"><?= nl2br(htmlspecialchars($ebook['description'])); ?></p>
                        </div>
                        
                        <?php if ($hasAlreadyPurchased): ?>
                            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl mr-2"></i>
                                    <p class="font-semibold">Vous avez déjà acheté cet e-book</p>
                                </div>
                                <p class="mt-2">Vous pouvez le télécharger depuis votre compte.</p>
                                <a href="myaccount.php" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                                    Accéder à mon compte
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="bg-lightgray p-6 rounded-lg mb-6">
                                <h3 class="text-lg font-semibold text-violet mb-4">Acheter cet e-book</h3>
                                
                                <?php if (!isset($_SESSION['user_id'])): ?>
                                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-4">
                                        <p>Vous devez être connecté pour acheter cet e-book.</p>
                                        <a href="login.php?redirect=ebook-details.php?id=<?= $ebook_id; ?>" class="mt-2 inline-block text-yellow-800 underline">Se connecter</a>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-6">
                                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700"><?= htmlspecialchars($ebook['titre']); ?></span>
                                                <span class="font-semibold"><?= number_format($ebook['prix'], 2, ',', ' '); ?>€</span>
                                            </div>
                                            <div class="border-t border-gray-300 my-2"></div>
                                            <div class="flex justify-between items-center font-bold">
                                                <span>Total</span>
                                                <span><?= number_format($ebook['prix'], 2, ',', ' '); ?>€</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <form id="payment-form" class="space-y-6">
                                        <div>
                                            <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                                                Informations de carte
                                            </label>
                                            <div id="card-element" class="p-4 border border-gray-300 rounded-md bg-white"></div>
                                            <div id="card-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
                                        </div>
                                        
                                        <button id="submit" type="submit" class="w-full bg-violet text-white py-3 px-4 rounded-md hover:bg-violet/90 transition-colors flex items-center justify-center">
                                            <span id="button-text">Payer <?= number_format($ebook['prix'], 2, ',', ' '); ?>€</span>
                                            <div id="spinner" class="hidden">
                                                <i class="fas fa-spinner fa-spin ml-2"></i>
                                            </div>
                                        </button>
                                        
                                        <p id="payment-message" class="text-center text-sm text-gray-500"></p>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-6">
                            <a href="ebooks.php" class="inline-flex items-center text-violet hover:text-violet/80 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour à la liste des e-books
                            </a>
                        </div>
                    </div>
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

        // Stripe integration
        const stripe = Stripe("pk_test_51Q98K001jgFsFXMEM3vu9B1lai14BgLEjvSUjIjDmWb8ERdH1RvDIsEbxzvSetyJ11sdTPCOXk1Ke4MiiGVitzst00LJys7J8K");
        const clientSecret = "<?= $client_secret; ?>";

        // Create an instance of Elements
        const elements = stripe.elements();
        const cardElement = elements.create("card", {
            style: {
                base: {
                    color: "#32325d",
                    fontFamily: 'Arial, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#aab7c4"
                    }
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            }
        });
        cardElement.mount("#card-element");

        // Handle form submission
        const form = document.getElementById("payment-form");
        const submitButton = document.getElementById("submit");
        const spinner = document.getElementById("spinner");
        const buttonText = document.getElementById("button-text");
        const paymentMessage = document.getElementById("payment-message");

        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            // Disable the submit button to prevent repeated clicks
            submitButton.disabled = true;
            buttonText.textContent = "Traitement en cours...";
            spinner.classList.remove("hidden");

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardElement }
            });

            if (error) {
                // Show error message
                document.getElementById("card-errors").textContent = error.message;
                buttonText.textContent = "Payer <?= number_format($ebook['prix'], 2, ',', ' '); ?>€";
                spinner.classList.add("hidden");
                submitButton.disabled = false;
            } else if (paymentIntent.status === "succeeded") {
                // Payment successful
                paymentMessage.textContent = "Paiement réussi ! Redirection en cours...";
                buttonText.textContent = "Paiement confirmé";
                
                // Redirect to success page
                setTimeout(() => {
                    window.location.href = "success-ebook.php?id=<?= $ebook_id; ?>&payment_id=" + paymentIntent.id;
                }, 2000);
            }
        });
    </script>
</body>
</html>