<?php

function checkPassword($pwd, &$errors) {
    $errors_init = $errors;

    if (strlen($pwd) < 8) {
        $errors[] = "Mot de passe trop court ! Minimum 8 caractères.";
    }

    if (!preg_match("#[0-9]+#", $pwd)) {
        $errors[] = "Le mot de passe doit comporter au moins un chiffre !";
    }

    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        $errors[] = "Le mot de passe doit comporter au moins une lettre majuscule et minuscule !";
    }

    if (!preg_match("#[\W]#", $pwd)) {
        $errors[] = "Le mot de passe doit compoter au moins un caractère spécial !";
    }

    return ($errors == $errors_init);
}

?>