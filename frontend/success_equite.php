<?php
require("../backend/utils/ConnectToBDD.php");
require("../libs/fpdf.php"); // Inclure FPDF

if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// Vérification des paramètres
if (!isset($_GET['payment_id']) || !isset($_GET['id'])) {
    die("Paiement non validé.");
}

$atelier_id = $_GET['id'];
$id_user = 1; // Remplacez par l'ID réel de l'utilisateur (ex: via $_SESSION)
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

// Enregistrer l'historique de paiement
$query = "INSERT INTO history_user (id_user, id_event, event_type) VALUES (:id_user, :id_event, 'atelier_equite')";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmt->bindParam(':id_event', $atelier_id, PDO::PARAM_INT);
$stmt->execute();

// ✅ Vérifier si le dossier "invoices" existe, sinon le créer
if (!is_dir("invoices")) {
    mkdir("invoices", 0777, true);
}

// Génération du fichier PDF avec FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Facture de reservation", 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Atelier : " . utf8_decode($atelier['nom']), 0, 1);
$pdf->Cell(0, 10, "Date : " . date("d/m/Y", strtotime($atelier['date'])), 0, 1);
$pdf->Cell(0, 10, "Heure : " . date("H:i", strtotime($atelier['heure_debut'])) . " - " . date("H:i", strtotime($atelier['heure_fin'])), 0, 1);
$pdf->Cell(0, 10, "Lieu : " . utf8_decode($atelier['type']), 0, 1);
$pdf->Cell(0, 10, "Prix : 50,00€", 0, 1);
$pdf->Cell(0, 10, "Paiement ID : " . $payment_id, 0, 1);

// Sauvegarde du PDF
$filename = "facture_" . $payment_id . ".pdf";
$filepath = "invoices/" . $filename;
$pdf->Output($filepath, "F"); // Enregistre dans "invoices/"

// ✅ Insérer le chemin du PDF dans la base de données
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
    <title>Réservation confirmée</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <h1 class="text-2xl font-bold text-green-600">🎉 Paiement Réussi !</h1>
        <p class="text-gray-700 mt-2">Votre réservation pour <strong><?= htmlspecialchars($atelier['nom']); ?></strong> a été confirmée.</p>

        <div class="mt-6">
            <a href="<?= $filepath; ?>" download class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700">
                📄 Télécharger ma facture
            </a>
            <a href="index.php" class="ml-4 px-6 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-700">
                🔙 Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
