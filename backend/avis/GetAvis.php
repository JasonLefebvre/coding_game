<?php
session_start();
require("../utils/ConnectToBDD.php");

$sql = "SELECT * FROM avis";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Location: ../../frontend/index.php");
?>
