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

// Fonction pour formater proprement les types d'événements
function formatEventType($type) {
    return match ($type) {
        'atelier_equite' => "Atelier d'équité",
        'atelier_ecriture' => "Atelier d'écriture",
        'coaching' => "Coaching",
        default => "Inconnu",
    };
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - La Ligne 13</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        violet: '#330c59',
                        jaune: '#ffeb5b',
                        mauve: '#e4c9e5',
                        rose: '#f9a8c9',
                        lightgray: '#f5f5f5',
                        darkgray: '#333333',
                    }
                }
            }
        }
    </script>
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body class="bg-lightgray min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="../src/img/logo.jpg" alt="Logo Ligne 13" class="h-8 w-auto">
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Accueil
                        </a>
                        <a href="ateliers.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'équité
                        </a>
                        <a href="coaching.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Coaching
                        </a>
                        <a href="ecriture.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Ateliers d'écriture
                        </a>
                        <a href="blog.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            Blog
                        </a>
                        <a href="ebooks.php" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Ebooks
                </a>
                        <a href="about.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="admin.php" class="bg-violet text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Administration
                    </a>
                <?php else: ?>
                    <a href="contact.html" class="border-transparent text-gray-600 hover:bg-gray-100 hover:border-violet hover:text-violet block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                        Contact
                    </a>
                <?php endif; ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="myaccount.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium ml-4">
                                Mon compte
                            </a>
                            <form action="../backend/user/DisconnectUser.php">
                                <input type="submit" value="Déconnexion">
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium ml-4">
                                Connexion
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <div class="flex -mb-px">
                        <button class="tab-button text-violet border-violet px-4 py-2 border-b-2 font-medium text-sm" data-tab="profile">
                            <i class="fas fa-user mr-2"></i>Profil
                        </button>
                        <button class="tab-button text-gray-500 hover:text-violet px-4 py-2 border-b-2 border-transparent font-medium text-sm" data-tab="sessions">
                            <i class="fas fa-calendar-alt mr-2"></i>Mes sessions
                        </button>
                        <button class="tab-button text-gray-500 hover:text-violet px-4 py-2 border-b-2 border-transparent font-medium text-sm" data-tab="invoices">
                            <i class="fas fa-file-invoice mr-2"></i>Mes factures
                        </button>
                    </div>
                </div>

                <!-- Contenu des tabs -->
                <!-- Tab Profil -->
                <div id="profile" class="tab-content active">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-violet/5 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-violet mb-3">Informations personnelles</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Nom :</span> <?= htmlspecialchars($nom) ?></p>
                                    <p><span class="font-medium">Prénom :</span> <?= htmlspecialchars($prenom) ?></p>
                                    <p><span class="font-medium">Date de naissance :</span> <?= htmlspecialchars($date_naissance) ?></p>
                                    <p><span class="font-medium">Profession :</span> <?= htmlspecialchars($profession) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-violet/5 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-violet mb-3">Coordonnées</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Email :</span> <?= htmlspecialchars($email) ?></p>
                                    <p><span class="font-medium">Téléphone :</span> <?= htmlspecialchars($telephone) ?></p>
                                    <p><span class="font-medium">Date d'inscription :</span> <?= htmlspecialchars($date_inscription) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Prochaines sessions -->
        <h2 class="text-2xl font-bold text-violet mt-8 mb-4">Mes prochaines sessions</h2>
        <?php if (!empty($prochaines_sessions)): ?>
            <ul class="space-y-2">
                <?php foreach ($prochaines_sessions as $session): ?>
                    <li class="bg-gray-100 p-4 rounded-md">
                        <strong><?= htmlspecialchars($session['titre']); ?></strong>
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

    <script>
        // Gestion des tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Retirer la classe active de tous les boutons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('text-violet', 'border-violet');
                        btn.classList.add('text-gray-500', 'border-transparent');
                    });

                    // Ajouter la classe active au bouton cliqué
                    button.classList.remove('text-gray-500', 'border-transparent');
                    button.classList.add('text-violet', 'border-violet');

                    // Masquer tous les contenus
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Afficher le contenu correspondant
                    const tabId = button.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>