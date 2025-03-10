<?php

session_start();
require("../utils/ConnectToBDD.php");

$new_title = $_POST['titre'];
$new_categorie = $_POST['categorie'];
$new_description = $_POST['description'];

$sql = "INSERT INTO coaching (titre, categorie, description) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_title, $new_categorie, $new_description]);

header("Location: ../../frontend/admin.php");