<?php

session_start();
require("../utils/ConnectToBDD.php");

$new_name = $_POST["nom"];
$new_description = $_POST["description"];
$new_date = $_POST["date"];
$new_start_time = $_POST["start_time"];
$new_final_time = $_POST["finish_time"];
$type = $_POST["type"];

$sql = "INSERT INTO atelier_equite (nom, description, date, heure_debut, heure_fin, type) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_name, $new_description, $new_date, $new_start_time, $new_final_time, $type]);

header("Location: ../../frontend/admin.php");