<?php

/**
 * Connexion à la base de données via PDO.
 *
 * @global PDO $pdo L'objet PDO pour la connexion à la base de données.
 * @throws PDOException Si la connexion à la base de données échoue.
 */
try {
    global $pdo;
    $pdo = new PDO(
        "mysql:host=100.83.27.15;dbname=coding_game;charset=UTF8",
        "b2-info",
        "1234",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " .  $e->getMessage());
}