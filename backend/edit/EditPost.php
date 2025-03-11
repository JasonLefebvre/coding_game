<?php

session_start();
require("../utils/ConnectToBDD.php");

$edited_title = $_POST["titre"];
$edited_contenu = $_POST["contenu"];
$edited_id  = intval($_POST["post_id"]);

$sql = "UPDATE post SET titre = ?, contenu = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$edited_title, $edited_contenu, $edited_id]);

header("Location: ../../frontend/admin/blogAdmin.php");