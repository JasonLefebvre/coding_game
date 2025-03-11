<?php
session_start();
require("../../backend/utils/ConnectToBDD.php");

$sql = "SELECT * FROM coaching";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$coachings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coaching - Admin</title>
</head>
<body>

<!-- Section Coaching -->
<section id="coaching" class="mt-10">
    <h3 class="text-2xl font-bold text-violet">Gestion des Coachings</h3>
    <div class="bg-white p-5 rounded-lg shadow mt-5">
        <h4 class="text-xl font-bold">Créer un coaching</h4>
        <form action="../../backend/generators/CoachingGenerator.php" method="post" class="mt-3">
            <label class="block text-darkgray">Titre</label>
            <input name="titre" type="text" required class="w-full p-2 border border-gray-300 rounded mt-1">
            <label class="block text-darkgray mt-3">Catégorie</label>
            <select name="categorie" id="categorie" class="w-full p-2 border border-gray-300 rounded mt-1">
                <option value="individuel">Individuel</option>
                <option value="collectif">Collectif</option>
            </select>
            <label class="block text-darkgray mt-3">Description</label>
            <textarea name="description" required class="w-full p-2 border border-gray-300 rounded mt-1"></textarea>
            <button type="submit" class="mt-3 bg-violet text-white px-4 py-2 rounded hover:bg-opacity-90 transition-colors">Ajouter</button>
        </form>
    </div>
</section>

<div class="workshops">
    <?php foreach ($coachings as $coaching): ?>
        <div class="workshop">
            <p><?php echo $coaching["titre"] ?></p>
            <p><?php echo $coaching["description"] ?></p>
            <p><?php echo $coaching["categorie"] ?></p>
            <button>Modifier</button>
            <button>Supprimer</button>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>