<?php

session_start();

if (!($_SESSION['role'] == "admin")) {
    header("Location: index.php");
}

echo "Bienvenue administrateur <strong>" . $_SESSION['name'] . " " . $_SESSION['firstname'] . "</strong>";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div>
    <div>Créer un post</div>
    <form action="../backend/blog/PostGenerator.php" method="post">
        <div>
            <label for="titre">Titre</label>
            <input name="titre" type="text" value="" required>
        </div>
        <div>
            <label>Contenu</label>
            <textarea name="context" required></textarea>
            <button type="submit">Ajouter</button>
        </div>
    </form>
</div>

<div>
    <div>Créer un coaching</div>
    <form action="../backend/blog/CoachingGenerator.php" method="post">
        <div>
            <label for="titre">Titre</label>
            <input name="titre" type="text" value="" required>
        </div>
        <div>
            <label for="categorie">Categorie</label>
            <select name="categorie" id="categorie">
                <option value="individuel">Individuel</option>
                <option value="collectif">Collectif</option>
            </select>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea name="description" required></textarea>
            <button type="submit">Ajouter</button>
        </div>
    </form>
</div>


</body>
</html>
