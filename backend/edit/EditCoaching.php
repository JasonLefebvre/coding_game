<?php

session_start();
require("../utils/ConnectToBDD.php");

$edited_title = $_POST["titre"];
$edited_categorie = $_POST["categorie"];
$edited_id  = intval($_POST["post_id"]);
$edited_description = $_POST["description"];

$sql = "UPDATE coaching SET titre = ?, categorie = ?, description = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$edited_title, $edited_categorie, $edited_description, $edited_id]);

header("Location: ../../frontend/admin/coachingAdmin.php");