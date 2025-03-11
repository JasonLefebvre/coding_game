<?php

session_start();
require("../utils/ConnectToBDD.php");

$new_title = $_POST["titre"];
$new_context =  $_POST["context"];
$date = date("Y-m-d  H:i:s");

$sql = "INSERT INTO post (titre, contenu, date_publie) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_title, $new_context, $date]);

header("Location: ../../frontend/admin.php");