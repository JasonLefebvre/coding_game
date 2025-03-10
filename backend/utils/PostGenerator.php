<?php

session_start();
require("../utils/ConnectToBDD.php");

$new_title = $_POST["titre"];
$new_context =  $_POST["context"];
$date = date("Y-m-d");

$sql = "INSERT INTO post (Titre, Contenu, Date) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_title, $new_context, $date]);

header("Location: ../index.php");