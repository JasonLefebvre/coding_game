<?php

require("../utils/ConnectToBDD.php");

session_start();
if (!(isset($_SESSION['role']) && $_SESSION['role']=="admin")) {
    header("location: ../../frontend/index.php"); // TODO : changer la localisation de la page
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Ligne 13</title>
</head>
<body>

<div>
    <form action="" method="post">
        <div>
            <label for="name">Titre</label>
            <input name="titre" type="text" value="">
        </div>
        <div>
            <label>Contenu</label>
            <textarea name="context"></textarea>
            <button type="submit">Ajouter</button>
        </div>
    </form>
</div>

</body>
</html>
