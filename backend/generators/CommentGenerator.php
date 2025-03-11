<?php

session_start();
require("../utils/ConnectToBDD.php");

$new_comment = $_POST["comment"];
$user_id = $_SESSION["user_id"];
$post_id = $_POST["post_id"];
$date = date("Y-m-d H:i:s");

$sql =  "INSERT INTO commentaire (post_id, user_id, commentaire, date_publi) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id, $user_id, $new_comment, $date]);

header("Location: ../../frontend/index.php");