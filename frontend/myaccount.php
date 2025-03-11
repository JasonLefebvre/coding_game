<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupération des données utilisateur depuis la session
$nom = $_SESSION['name'] ?? "Non renseigné";
$prenom = $_SESSION['firstname'] ?? "Non renseigné";
$date_naissance = $_SESSION['birthday'] ?? "Non renseigné";
$profession = $_SESSION['job'] ?? "Non renseigné";
$email = $_SESSION['email'] ?? "Non renseigné";
$telephone = $_SESSION['phone'] ?? "Non renseigné";
$date_inscription = $_SESSION['registerDate'] ?? "Non renseigné";
$is_verified = $_SESSION['isVerified'] ? "Oui" : "Non";
$role = $_SESSION['role'] ?? "Utilisateur";

// Connexion à la BDD
require("../backend/utils/ConnectToBDD.php");

// Vérifier que la connexion PDO fonctionne
if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Requête SQL pour récupérer les sessions
$query = "
    SELECT h.id_event, h.event_type, h.date, h.prix,
       COALESCE(ae.titre, aq.nom, c.titre) AS titre,
       COALESCE(ae.date, aq.date, NULL) AS date_event,
       COALESCE(ae.description, aq.description, c.description) AS description,
       COALESCE(ae.heure_debut, aq.heure_debut) AS heure_debut,
       COALESCE(ae.heure_fin, aq.heure_fin) AS heure_fin
FROM history_user h
LEFT JOIN atelier_ecriture ae ON h.event_type = 'atelier_ecriture' AND h.id_event = ae.id
LEFT JOIN atelier_equite aq ON h.event_type = 'atelier_equite' AND h.id_event = aq.id
LEFT JOIN coaching c ON h.event_type = 'coaching' AND h.id_event = c.id
WHERE h.id_user = :user_id;
";

// Exécuter la requête
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!is_array($sessions)) {
    $sessions = [];
}

// Séparer les sessions en "Prochaines" et "Historique"
$today = date("Y-m-d");
$prochaines_sessions = [];
$historique_sessions = [];

foreach ($sessions as $session) {
    if (!empty($session['date_event']) && $session['date_event'] >= $today) {
        $prochaines_sessions[] = $session; // Ajout dans les sessions à venir
    } else {
        $historique_sessions[] = $session; // Ajout dans l'historique
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="../src/img/logo.jpg" alt="Logo Ligne 13" class="h-8 w-auto">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.php" class="text-violet font-medium">Accueil</a>
                        <a href="ateliers.html" class="text-gray-600 hover:text-violet">Ateliers d'équité</a>
                        <a href="coaching.html" class="text-gray-600 hover:text-violet">Coaching</a>
                        <a href="ecriture.html" class="text-gray-600 hover:text-violet">Ateliers d'écriture</a>
                        <a href="blog.html" class="text-gray-600 hover:text-violet">Blog</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="contact.html" class="bg-violet text-white px-4 py-2 rounded-md text-sm font-medium">Contactez-nous</a>
                    <a href="myaccount.php" class="ml-4 text-violet font-medium">Mon compte</a>
                    <!-- TODO : a revoir, c'est juste pour tester -->
                    <a href="../backend/user/DisconnectUser.php">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="max-w-6xl mx-auto mt-20 p-6 bg-white shadow-md rounded-lg">
        <!-- Informations utilisateur -->
        <h2 class="text-2xl font-bold text-violet mb-4">Informations personnelles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <p><strong>Nom :</strong> <?= htmlspecialchars($nom); ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($prenom); ?></p>
            <p><strong>Date de naissance :</strong> <?= htmlspecialchars($date_naissance); ?></p>
            <p><strong>Profession :</strong> <?= htmlspecialchars($profession); ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($email); ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($telephone); ?></p>
            <p><strong>Date d'inscription :</strong> <?= htmlspecialchars($date_inscription); ?></p>
        </div>

        <!-- Prochaines sessions -->
        <h2 class="text-2xl font-bold text-violet mt-8 mb-4">Mes prochaines sessions</h2>
        <?php if (!empty($prochaines_sessions)): ?>
            <ul class="space-y-2">
                <?php foreach ($prochaines_sessions as $session): ?>
                    <li class="bg-gray-100 p-4 rounded-md">
                        <strong><?= htmlspecialchars($session['titre']); ?></strong>
                        <br>
                        <span class="text-gray-700">Date :</span> <?= date("d/m/Y", strtotime($session['date_event'])); ?>
                        <br>
                        <span class="text-gray-700">Type :</span> <?= htmlspecialchars($session['event_type']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Aucune session à venir.</p>
        <?php endif; ?>

        <!-- Historique des sessions -->
        <h2 class="text-2xl font-bold text-violet mt-8 mb-4">Historique de mes sessions</h2>
        <?php if (!empty($historique_sessions)): ?>
            <ul class="space-y-2">
                <?php foreach ($historique_sessions as $session): ?>
                    <li class="bg-gray-100 p-4 rounded-md">
                        <strong><?= htmlspecialchars($session['titre']); ?></strong>
                        <br>
                        <span class="text-gray-700">Date :</span> <?= date("d/m/Y", strtotime($session['date_event'])); ?>
                        <br>
                        <span class="text-gray-700">Type :</span> <?= htmlspecialchars($session['event_type']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Aucun historique disponible.</p>
        <?php endif; ?>

        <!-- Mes factures -->
        <h2 class="text-2xl font-bold text-violet mt-8 mb-4">Mes factures</h2>
        <?php
        // Récupérer les factures depuis la table history_user avec les titres des ateliers ou coachings
        $query = "
            SELECT h.id_event, h.event_type, h.path_pdf, h.date, h.prix,
                   COALESCE(ae.titre, aq.nom, c.titre) AS titre
            FROM history_user h
            LEFT JOIN atelier_ecriture ae ON h.event_type = 'atelier_ecriture' AND h.id_event = ae.id
            LEFT JOIN atelier_equite aq ON h.event_type = 'atelier_equite' AND h.id_event = aq.id
            LEFT JOIN coaching c ON h.event_type = 'coaching' AND h.id_event = c.id
            WHERE h.id_user = :user_id AND h.path_pdf IS NOT NULL
            ORDER BY h.date DESC;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (!empty($factures)): ?>
            <ul class="space-y-2">
                <?php foreach ($factures as $facture): ?>
                    <li class="bg-gray-100 p-4 rounded-md flex justify-between items-center">
                        <div>
                            <strong>Facture pour <?= htmlspecialchars($facture['titre']); ?> (<?= htmlspecialchars($facture['event_type']); ?>)</strong>
                            <br>
                            <span class="text-gray-700">Date : <?= date("d/m/Y", strtotime($facture['date'])); ?></span>
                            <br>
                            <span class="text-gray-700">Prix : <?= number_format($facture['prix'], 2, ',', ' ') ?>€</span>
                        </div>
                        <div class="flex space-x-3">
                            <a href="<?= htmlspecialchars($facture['path_pdf']); ?>" target="_blank" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                                📂 Ouvrir
                            </a>
                            <a href="<?= htmlspecialchars($facture['path_pdf']); ?>" download class="px-4 py-2 bg-green-500 text-white rounded-md">
                                ⬇ Télécharger
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Aucune facture disponible.</p>
        <?php endif; ?>
    </div>
</body>
</html>
