<?php

session_start();
require("../utils/ConnectToBDD.php");

$edited_name = $_POST['nom'];
$edited_description = $_POST['description'];
$edited_date = $_POST['date'];
$edited_heure_debut = $_POST['heure_debut'];
$edited_heure_fin = $_POST['heure_fin'];
$edited_type = $_POST['type'];
$edited_id = intval($_POST["id"]);

echo "Heure de début : " . $edited_heure_debut . "<br>";

$sql = "UPDATE atelier_equite SET nom = ?, description = ?, date = ?, heure_debut = ?, heure_fin = ?, type = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$edited_name, $edited_description, $edited_date, $edited_heure_debut, $edited_heure_fin, $edited_type, $edited_id]);