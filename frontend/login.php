<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
<form action="../backend/user/LoginUser.php" method="POST">
    <label for="username">Pseudo :</label>
    <input type="text" placeholder="Entrez votre pseudo" name="username" required>
    <label for="email">Adresse mail :</label>
    <input type="text" placeholder="Entrez votre adresse mail" name="email" required>
    <label for="password">Mot de passe :</label>
    <input type="password" placeholder="Entre votre mot de passe" name="password" required>
    <span><a href="register.php">Si vous n'avez pas de compte, enregistrez-vous ici ! </a></span>
    <button class="login-button" type="submit">Login</button>
</form>
</body>
</html>
