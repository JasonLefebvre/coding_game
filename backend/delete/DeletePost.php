<?php

require("../utils/ConnectToBDD.php");

$id = $_GET["id"];

$sql = "DELETE FROM post WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: ../../frontend/admin/blogAdmin.php");