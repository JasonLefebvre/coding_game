<?php

session_start();
require("../../backend/utils/ConnectToBDD.php");

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
    <title>Blog - Admin</title>
    <script>
        function toggleEdit(postId) {
            let inputs = document.querySelectorAll('#post-' + postId + ' .editable');
            inputs.forEach(function(input) {
                input.disabled = !input.disabled;
            });
        }
    </script>
</head>
<body>
<!-- Section Blog -->
<section id="blog" class="mt-10">
    <h3 class="text-2xl font-bold text-violet">Gestion du Blog</h3>
    <div class="bg-white p-5 rounded-lg shadow mt-5">
        <h4 class="text-xl font-bold">Créer un post</h4>
        <form action="../../backend/generators/PostGenerator.php" method="post" class="mt-3">
            <label class="block text-darkgray">Titre</label>
            <input name="titre" type="text" required class="w-full p-2 border border-gray-300 rounded mt-1">
            <label class="block text-darkgray mt-3">Contenu</label>
            <textarea name="context" required class="w-full p-2 border border-gray-300 rounded mt-1"></textarea>
            <button type="submit" class="mt-3 bg-violet text-white px-4 py-2 rounded hover:bg-opacity-90 transition-colors">Ajouter</button>
        </form>
    </div>
</section>

<div class="posts">
    <?php foreach ($posts as $post): ?>
    <div class="post">
        <form action="../../backend/edit/EditPost.php" method="post" id="post-<?php echo $post['id']; ?>">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input class="editable" name="titre" disabled value="<?php echo $post["titre"] ?>">
            <input class="editable" disabled name="contenu" value="<?php echo $post["contenu"] ?>">
            <p><?php echo $post["date_publie"] ?></p>
            <input type="submit" class="editable" disabled value="envoyer">
        </form>
        <button onclick="toggleEdit(<?php echo $post["id"]?>)">Modifier</button>
        <a href="../../backend/delete/DeletePost.php?id=<?php echo $post["id"]; ?>">Supprimer</a>
    </div>
    <br>
    <?php endforeach; ?>
</div>

</body>
</html>
