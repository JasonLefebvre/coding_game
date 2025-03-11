<?php

session_start();
require("../backend/utils/ConnectToBDD.php");

$sql = "SELECT * FROM post";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Ligne 13</title>
</head>
<body>

<div class="posts">
    <?php foreach ($posts as $post): ?>
    <a href="blog/detail.php?id=<?php echo htmlspecialchars($post['id'])?>">
        <div class="post">
            <p><?php echo $post["titre"] ?></p>
            <p><?php echo $post["contenu"] ?></p>
            <p><?php echo $post["date_publie"] ?></p>
            <button>Modifier</button>
            <button>Supprimer</button>
        </div>
    </a>
    <?php endforeach; ?>
</div>

</body>
</html>
