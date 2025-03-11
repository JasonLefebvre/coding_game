<?php
session_start();
require_once("../utils/ConnectToBDD.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $motif = $_POST['motif'] ?? null;

    // Create datetime strings
    $datetime_debut = $date_debut . ' ' . $heure_debut . ':00';
    $datetime_fin = $date_fin . ' ' . $heure_fin . ':00';

    try {
        // Insert into database
        $query = "INSERT INTO indisponibilites (date_debut, date_fin, motif) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$datetime_debut, $datetime_fin, $motif]);

        // Redirect back with success message
        header('Location: ../../frontend/admin/availabilityAdmin.php?success=1');
        exit;
    } catch (PDOException $e) {
        // Redirect back with error message
        header('Location: ../../frontend/admin/availabilityAdmin.php?error=1');
        exit;
    }
}