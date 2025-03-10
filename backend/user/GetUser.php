<?php
session_start();
header('Content-Type: application/json'); // 🔹 Ajout du bon header JSON
require("../../utils/ConnectToBDD.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT nom, prenom, date_naissance, profession, email, telephone, date_inscription, is_verified, role_user FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(["error" => "Utilisateur introuvable"]);
}
?>
