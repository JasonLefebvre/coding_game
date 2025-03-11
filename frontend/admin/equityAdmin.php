<?php
session_start();
require("../../backend/utils/ConnectToBDD.php");

$sql = "SELECT * FROM atelier_equite";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atelier Equité - Admin</title>
</head>
<body>

<form action="../../backend/generators/EquityWorkshopGenerator.php" method="post">
    <div>
        <label for="titre">Titre</label>
        <input type="text" name="titre" required>
    </div>
    <div>
        <label for="description">Description</label>
        <textarea name="description" required></textarea>
    </div>
    <div>
        <label for="date">Date</label>
        <input type="date" name="date" required>
    </div>
    <div>
        <label for="date">Heure de début</label>
        <input type="datetime-local" name="start_time" required>
    </div>
    <div>
        <label for="date">Heure de fin</label>
        <input type="datetime-local" name="finish_time" required>
    </div>
    <button type="submit">Ajouter</button>
</form>

<div class="workshops">
    <?php foreach ($workshops as $workshop): ?>
        <div class="workshop">
            <p><?php echo $workshop["nom"] ?></p>
            <p><?php echo $workshop["description"] ?></p>
            <p><?php echo $workshop["date"] ?></p>
            <p>Début : <?php echo $workshop["heure_debut"] ?></p>
            <p>Fin : <?php echo $workshop["heure_fin"] ?></p>
            <p>Type : <?php echo $workshop["type"] ?></p>
            <button>Modifier</button>
            <button>Supprimer</button>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>