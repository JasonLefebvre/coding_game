<?php

require("../utils/ConnectToBDD.php");

session_start();

if (empty($_POST["email"]) || empty($_POST["password"])) {
    die("Tous les champs sont obligatoires");
}

$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    echo "Identifiants incorrects. Données manquantes";
} else {
    if ($email ==  $user["email"] && md5($password) == $user["password"]) {

        $_SESSION['user_id'] = $user["id"];
        $_SESSION['name'] = $user["nom"];
        $_SESSION['firstname'] = $user["prenom"];
        $_SESSION['birthday'] = $user['date_naissance'];
        $_SESSION['job'] =  $user['profession'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['telephone'];
        $_SESSION['registerDate'] = $user['date_inscription'];
        $_SESSION['isVerified'] = $user['is_verified'];
        $_SESSION['role'] = $user['role_user'];

        setcookie("user", $user["id"], time() + (86400 * 30));

        if ($user['role_user'] == "admin") {
            header("Location: ../admin.php");
        }
        header("Location: ../../frontend/index.php");
    } else {
        echo "Identifiants incorrects.";
    }
}

?>