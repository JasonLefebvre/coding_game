<?php

session_start();
require("../utils/ConnectToBDD.php");

$new_title = $_POST['titre'];
$new_description = $_POST['description'];
$new_day =  $_POST['day'];
$new_start_time = $_POST['start_time'];
$new_final_time = $_POST['finish_time'];

$sql = "INSERT INTO atelier_ecriture (titre, description, date, heure_debut, heure_fin)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_title, $new_description, $new_day, $new_start_time, $new_final_time]);

header("Location: ../../frontend/admin.php");