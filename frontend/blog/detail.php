<?php

session_start();

require("../../backend/utils/ConnectToBDD.php");
if (isset($_GET["id"]) && is_numeric($_GET["id"])){
    $id = intval($_GET["id"]);

    $query = "SELECT titre, contenu, date_publie FROM post WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if ($post) {
        $titre = $post["titre"];
        $contenu = $post["contenu"];
        $date_publie = $post["date_publie"];
    } else {
        die("Post inconnu");
    }
} else {
    die("ID Invalide");
}

$query = "SELECT * FROM commentaire WHERE post_id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$comments = $stmt->fetchAll();

function getUserName($userId, $pdo) {
    $query = "SELECT nom, prenom FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    return ["nom" => $user["nom"], "prenom" => $user["prenom"]];
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $post["titre"] ?></title>
</head>
<body>

<div class="post">
    <div class="post-body">
        <h3><?php echo $post["titre"] ?></h3>
        <div><?php echo $post["contenu"]?></div>
        <div><?php echo $post["date_publie"] ?></div>
    </div>
    <div class="post-comments">
        <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <div><?php echo getUserName($comment["user_id"], $pdo)["nom"] . " " . getUserName($comment["user_id"], $pdo)["prenom"] ?></div>
            <div><?php echo $comment["commentaire"] ?></div>
            <div><?php echo $comment["date_publi"] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="send-comment">
        <form action="../../backend/generators/CommentGenerator.php" method="post">
            <div>
                <input type="hidden" name="post_id" value="<?php echo $id ?>">
                <label for="comment">Ajouter un commentaire :</label>
                <textarea name="comment"></textarea>
            </div>
            <button>Envoyer</button>
        </form>
    </div>
</div>

</body>
</html>
