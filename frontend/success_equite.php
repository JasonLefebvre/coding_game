<?php
require("../backend/utils/ConnectToBDD.php");
require("../libs/fpdf.php"); // Inclure FPDF
session_start();

if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// Vérification des paramètres
if (!isset($_GET['payment_id']) || !isset($_GET['id'])) {
    die("Paiement non validé.");
}

$atelier_id = $_GET['id'];
$id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Utiliser l'ID de session si disponible
$payment_id = $_GET['payment_id']; // ID du paiement

// Récupération des détails de l'atelier
$query = "SELECT nom, description, date, heure_debut, heure_fin, type FROM atelier_equite WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $atelier_id, PDO::PARAM_INT);
$stmt->execute();
$atelier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$atelier) {
    die("Atelier introuvable.");
}

// Prix de l'atelier
$prix = 50.00;

// Obtenir la date actuelle
$date_validation = date('Y-m-d');

// Enregistrer l'historique de paiement avec la date et le prix
$query = "INSERT INTO history_user (id_user, id_event, event_type, date, prix) VALUES (:id_user, :id_event, 'atelier_equite', :date, :prix)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmt->bindParam(':id_event', $atelier_id, PDO::PARAM_INT);
$stmt->bindParam(':date', $date_validation, PDO::PARAM_STR);
$stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
$stmt->execute();

// Vérifier si le dossier "invoices" existe, sinon le créer
if (!is_dir("invoices")) {
    mkdir("invoices", 0777, true);
}

// Génération du fichier PDF avec FPDF
$pdf = new FPDF();
$pdf->AddPage();

// En-tête du PDF
$pdf->SetFillColor(51, 12, 89); // Couleur violet de La Ligne 13
$pdf->Rect(0, 0, 210, 40, 'F');
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(0, 20, utf8_decode("FACTURE"), 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode("La Ligne 13 - Coaching & Ateliers"), 0, 1, 'C');

// Informations de la facture
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(50);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Facture N° " . $payment_id), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, utf8_decode("Date d'émission : " . date("d/m/Y")), 0, 1);
$pdf->Cell(0, 7, utf8_decode("Client : " . (isset($_SESSION['nom']) ? $_SESSION['prenom'] . ' ' . $_SESSION['nom'] : 'Client #' . $id_user)), 0, 1);
$pdf->Ln(10);

// Détails de la prestation
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(90, 10, utf8_decode("Description"), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode("Date"), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode("Horaire"), 1, 0, 'C', true);
$pdf->Cell(40, 10, utf8_decode("Montant"), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 10, utf8_decode($atelier['nom']), 1, 0, 'L');
$pdf->Cell(30, 10, date("d/m/Y", strtotime($atelier['date'])), 1, 0, 'C');
$pdf->Cell(30, 10, date("H:i", strtotime($atelier['heure_debut'])) . ' - ' . date("H:i", strtotime($atelier['heure_fin'])), 1, 0, 'C');
$pdf->Cell(40, 10, number_format($prix, 2, ',', ' ') . " EUR", 1, 1, 'R');

// Total
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(150, 10, utf8_decode("Total"), 1, 0, 'R', true);
$pdf->Cell(40, 10, number_format($prix, 2, ',', ' ') . " EUR", 1, 1, 'R', true);

// Informations de paiement
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, utf8_decode("Informations de paiement"), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, utf8_decode("Méthode de paiement : Carte bancaire"), 0, 1);
$pdf->Cell(0, 7, utf8_decode("Référence de transaction : " . $payment_id), 0, 1);
$pdf->Cell(0, 7, utf8_decode("Statut : Payé"), 0, 1);

// Pied de page
$pdf->SetY(-40);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode("La Ligne 13 - SIRET : 123 456 789 00012"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("13 Rue de l'Exemple, 75000 Paris"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Email : contact@laligne13.fr - Tél : 01 23 45 67 89"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Merci pour votre confiance !"), 0, 1, 'C');

// Sauvegarde du PDF
$filename = "facture_" . $payment_id . ".pdf";
$filepath = "invoices/" . $filename;
$pdf->Output($filepath, "F"); // Enregistre dans "invoices/"

// Insérer le chemin du PDF dans la base de données
$query = "UPDATE history_user SET path_pdf = :path_pdf WHERE id_user = :id_user AND id_event = :id_event";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':path_pdf', $filepath, PDO::PARAM_STR);
$stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmt->bindParam(':id_event', $atelier_id, PDO::PARAM_INT);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation confirmée - La Ligne 13</title>
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
        
        /* Success card styling */
        .success-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .success-card:hover {
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
                        <a href="ebooks.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ebooks
                </a>
                        <a href="about.html" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
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
                <a href="ateliers.php" class="bg-lightgray border-violet text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
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
                    <span class="block">Réservation confirmée</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-base text-gray-300 sm:text-lg animate-hidden animate-element animate-delay-100">
                    Votre place pour l'atelier a été réservée avec succès
                </p>
            </div>
        </div>
    </div>

    <!-- Success Section -->
    <div class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl p-8 success-card animate-hidden animate-element">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                        <i class="fas fa-check text-4xl text-green-600"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-green-600">Paiement réussi !</h2>
                    <p class="text-xl text-gray-700 mt-2">Votre réservation a été confirmée</p>
                </div>
                
                <div class="bg-lightgray p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-bold text-violet mb-4">Détails de la réservation</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Atelier :</span>
                            <span class="font-semibold"><?= htmlspecialchars($atelier['nom']); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Date :</span>
                            <span class="font-semibold"><?= date("d/m/Y", strtotime($atelier['date'])); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Horaire :</span>
                            <span class="font-semibold"><?= date("H:i", strtotime($atelier['heure_debut'])); ?> - <?= date("H:i", strtotime($atelier['heure_fin'])); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Lieu :</span>
                            <span class="font-semibold"><?= htmlspecialchars($atelier['type']); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Montant payé :</span>
                            <span class="font-semibold"><?= number_format($prix, 2, ',', ' '); ?>€</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Numéro de transaction :</span>
                            <span class="font-semibold"><?= $payment_id; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center space-y-4">
                    <p class="text-gray-700">Un email de confirmation a été envoyé à votre adresse email.</p>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                        <a href="<?= $filepath; ?>" download class="inline-flex items-center justify-center px-6 py-3 bg-violet text-white rounded-md hover:bg-violet/90 transition-colors">
                            <i class="fas fa-download mr-2"></i> Télécharger ma facture
                        </a>
                        
                        <a href="index.php" class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="fas fa-home mr-2"></i> Retour à l'accueil
                        </a>
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