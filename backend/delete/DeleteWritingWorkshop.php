<?php

require("../utils/ConnectToBDD.php");

$id = $_GET["id"];

$sql = "DELETE FROM atelier_ecriture WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: ../../frontend/admin/coachingAdmin.php");