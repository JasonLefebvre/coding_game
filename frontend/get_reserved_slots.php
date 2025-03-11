<?php
require("../backend/utils/ConnectToBDD.php");

if (!isset($_GET['date']) || empty($_GET['date'])) {
    echo json_encode([]);
    exit;
}

$date = $_GET['date'];

// Log de la date reçue
error_log("Date reçue: " . $date);

$stmt = $pdo->prepare("
    SELECT DISTINCT DATE_FORMAT(heure_debut, '%H:%i') AS heure 
    FROM history_user 
    WHERE date = :date 
    AND heure_debut IS NOT NULL
");

$stmt->execute(['date' => $date]);
$reserved = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Log des résultats de la requête SQL
error_log("Horaires récupérés: " . json_encode($reserved));

header('Content-Type: application/json');
echo json_encode($reserved);

?>