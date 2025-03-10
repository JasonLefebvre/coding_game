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
<form action="../backend/user/RegisterUser.php" method="POST">
    <div class="form-container">
        <!-- Partie gauche -->
        <div class="form-left">
            <label for="name">Nom :</label>
            <input type="text" placeholder="Entrez votre nom" name="name" required>
            <label for="firstname">Prénom :</label>
            <input type="text" placeholder="Entrez votre prénom" name="firstname" required>
            <label for="birthday">Date d'anniversaire :</label>
            <input type="date" name="birthday" required>
            <label for="job">Profession :</label>
            <input type="text" placeholder="Entrez votre profession" name="job" required>
            <label for="email">Email :</label>
            <input type="email" placeholder="Entrez votre email" name="email" required>
            <label for="phone">Téléphone :</label>
            <input type="tel" placeholder="Entrez votre téléphone" name="phone" required>
            <label for="password">Mot de passe :</label>
            <input type="password" placeholder="Entrez votre mot de passe" name="password" required>
            <label for="confirm_password">Confirmation de mot de passe :</label>
            <input type="password" placeholder="Confirmez votre mot de passe" name="confirm_password" required>
        </div>
        <button type="submit">S'enregistrer</button>
    </div>
</form>
</body>
</html>