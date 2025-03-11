<?php
session_start(); // Démarrer la session pour récupérer l'utilisateur connecté
require("../backend/utils/ConnectToBDD.php");
require("../libs/fpdf.php"); // Inclure FPDF

// Activer le mode debug pour voir les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// ✅ Vérifier si l'utilisateur est bien connecté
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("❌ Erreur : utilisateur non connecté. Vérifiez votre connexion.");
}

$user_id = $_SESSION['user_id']; // Récupération de l'ID utilisateur connecté

// ✅ Vérification des paramètres GET
$required_params = ['payment_id', 'id', 'date', 'hour'];
foreach ($required_params as $param) {
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        die("❌ Erreur : paramètre '$param' manquant ou invalide.");
    }
}

$coaching_id = (int) $_GET['id'];
$payment_id = htmlspecialchars($_GET['payment_id']);
$date = htmlspecialchars($_GET['date']);
$heure_debut = htmlspecialchars($_GET['hour']);

// ✅ Vérifier que l'heure de début est bien au format HH:MM
if (!preg_match('/^\d{2}:\d{2}$/', $heure_debut)) {
    die("❌ Erreur : format d'heure invalide.");
}

// ✅ Calcul automatique de l'heure de fin (1h après)
$heure_fin = date("H:i", strtotime($heure_debut) + 3600);

// ✅ Vérifier que le coaching existe bien
$query = "SELECT id, titre, description FROM coaching WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $coaching_id, PDO::PARAM_INT);
$stmt->execute();
$coaching = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coaching) {
    die("Coaching introuvable.");
}

// ✅ Enregistrement de l'historique de paiement dans `history_user`
$query = "INSERT INTO history_user (id_user, id_event, event_type, date, heure_debut, heure_fin) 
          VALUES (:id_user, :id_event, 'coaching', :date_reservation, :heure_debut, :heure_fin)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':id_event', $coaching_id, PDO::PARAM_INT);
$stmt->bindParam(':date_reservation', $date, PDO::PARAM_STR);
$stmt->bindParam(':heure_debut', $heure_debut, PDO::PARAM_STR);
$stmt->bindParam(':heure_fin', $heure_fin, PDO::PARAM_STR);

if (!$stmt->execute()) {
    die("❌ Erreur lors de l'enregistrement de l'historique.");
}


// ✅ Vérification et création du dossier "invoices" si nécessaire
if (!is_dir("invoices")) {
    mkdir("invoices", 0777, true);
}

// ✅ Génération du fichier PDF avec FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Facture de reservation", 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Coaching : " . mb_convert_encoding($coaching['titre'], "ISO-8859-1", "UTF-8"), 0, 1);
$pdf->Cell(0, 10, "Prix : 50,00€", 0, 1);
$pdf->Cell(0, 10, "Date : " . $date, 0, 1);
$pdf->Cell(0, 10, "Heure : " . $heure_debut . " - " . $heure_fin, 0, 1);
$pdf->Cell(0, 10, "Paiement ID : " . $payment_id, 0, 1);

// ✅ Sauvegarde du PDF
$filename = "facture_" . $payment_id . ".pdf";
$filepath = "invoices/" . $filename;
$pdf->Output($filepath, "F");

// ✅ Vérifier que le fichier PDF a bien été créé
if (!file_exists($filepath)) {
    die("❌ Erreur : le fichier PDF n'a pas été généré.");
}

// ✅ Mettre à jour l'enregistrement en base de données avec le chemin du PDF
$query = "UPDATE history_user SET path_pdf = :path_pdf WHERE id_user = :id_user AND id_event = :id_event";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':path_pdf', $filepath, PDO::PARAM_STR);
$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':id_event', $coaching_id, PDO::PARAM_INT);


if (!$stmt->execute()) {
    die("❌ Erreur lors de la mise à jour du chemin du PDF.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation confirmée</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <h1 class="text-2xl font-bold text-green-600">🎉 Paiement Réussi !</h1>
        <p class="text-gray-700 mt-2">Votre réservation pour <strong><?= htmlspecialchars($coaching['titre'] ?? ''); ?></strong> a été confirmée.</p>
        <p class="text-gray-700">Date : <strong><?= htmlspecialchars($date); ?></strong></p>
        <p class="text-gray-700">Heure : <strong><?= htmlspecialchars($heure_debut); ?> - <?= htmlspecialchars($heure_fin); ?></strong></p>

        <div class="mt-6">
            <a href="<?= htmlspecialchars($filepath); ?>" download class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700">
                📄 Télécharger ma facture
            </a>
            <a href="index.php" class="ml-4 px-6 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-700">
                🔙 Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
