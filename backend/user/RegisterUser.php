<?php

require("../utils/ConnectToBDD.php");

if (empty($_POST["name"] || empty($_POST["firstname"]) || empty($_POST["birthday"]) || empty($_POST["job"]) || empty($_POST["email"]) || empty($_POST["phone"]) || empty($_POST["password"]) || empty($_POST("confirm_password")))) {
    die("Il manque une/des informations");
} else if ($_POST["password"] != $_POST["confirm_password"]) {
    die("Les mots de passe ne correspondent pas");
}

$name = $_POST["name"];
$firstname = $_POST["firstname"];
$birthday = $_POST["birthday"];
$job = $_POST["job"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$password = md5($_POST["password"]);
$confirm_password = md5($_POST["confirm_password"]);

$sql = 'INSERT INTO users (nom, prenom, date_naissance, profession, email, telephone, password, role_user) VALUES (?, ?, ?, ?, ?,?, ?, ?)';
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $firstname, $birthday, $job, $email, $phone, $password, "user"]);
header("Location: ../../frontend/index.php");