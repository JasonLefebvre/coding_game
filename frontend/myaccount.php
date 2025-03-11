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
                        <a href="about.php" class="border-transparent text-gray-600 hover:text-violet inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-violet text-sm font-medium">
                            À propos
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden sm:flex sm:items-center">
                        <a href="contact.php" class="bg-violet text-white hover:bg-violet/90 px-4 py-2 rounded-md text-sm font-medium">
                            Contactez-nous
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="myaccount.php" class="border-violet text-violet inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium ml-4">
                                Mon compte
                            </a>
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
                                    <p><span class="font-medium">Compte vérifié :</span> <?= $is_verified ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Sessions -->
                <div id="sessions" class="tab-content">
                    <div class="space-y-6">
                        <!-- Prochaines sessions -->
                        <div>
                            <h3 class="text-lg font-semibold text-violet mb-4">Prochaines sessions</h3>
                            <?php if (!empty($prochaines_sessions)): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <?php foreach ($prochaines_sessions as $session): ?>
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <h4 class="font-semibold text-violet"><?= htmlspecialchars($session['titre']) ?></h4>
                                            <p class="text-sm text-gray-600 mt-2">
                                                <i class="far fa-calendar mr-2"></i>
                                                <?= date("d/m/Y", strtotime($session['date_event'])) ?>
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <i class="far fa-clock mr-2"></i>
                                                <?= $session['heure_debut'] ?> - <?= $session['heure_fin'] ?>
                                            </p>
                                            <div class="mt-2">
                                                <span class="inline-block bg-violet/10 text-violet text-xs px-2 py-1 rounded">
                                                    <?= ucfirst(str_replace('_', ' ', $session['event_type'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-500 text-center py-4">Aucune session à venir</p>
                            <?php endif; ?>
                        </div>

                        <!-- Historique des sessions -->
                        <div>
                            <h3 class="text-lg font-semibold text-violet mb-4">Historique des sessions</h3>
                            <?php if (!empty($historique_sessions)): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <?php foreach ($historique_sessions as $session): ?>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                            <h4 class="font-semibold text-gray-700"><?= htmlspecialchars($session['titre']) ?></h4>
                                            <p class="text-sm text-gray-600 mt-2">
                                                <i class="far fa-calendar mr-2"></i>
                                                <?= date("d/m/Y", strtotime($session['date_event'])) ?>
                                            </p>
                                            <div class="mt-2">
                                                <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                                                    <?= ucfirst(str_replace('_', ' ', $session['event_type'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-500 text-center py-4">Aucun historique disponible</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab Factures -->
                <div id="invoices" class="tab-content">
                    <h3 class="text-lg font-semibold text-violet mb-4">Mes factures</h3>
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
                        <div class="space-y-4">
                            <?php foreach ($factures as $facture): ?>
                                <div class="bg-white border border-gray-200 rounded-lg p-4 flex justify-between items-center hover:shadow-md transition-shadow">
                                    <div>
                                        <h4 class="font-semibold text-violet">
                                            <?= htmlspecialchars($facture['titre']) ?>
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="far fa-calendar mr-2"></i>
                                            <?= date("d/m/Y", strtotime($facture['date'])) ?>
                                        </p>
                                        <p class="text-sm font-medium text-violet">
                                            <?= number_format($facture['prix'], 2, ',', ' ') ?>€
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="<?= htmlspecialchars($facture['path_pdf']) ?>" target="_blank" 
                                           class="inline-flex items-center px-3 py-2 border border-violet text-violet rounded-md hover:bg-violet hover:text-white transition-colors">
                                            <i class="far fa-eye mr-2"></i>Voir
                                        </a>
                                        <a href="<?= htmlspecialchars($facture['path_pdf']) ?>" download 
                                           class="inline-flex items-center px-3 py-2 bg-violet text-white rounded-md hover:bg-violet/90 transition-colors">
                                            <i class="fas fa-download mr-2"></i>Télécharger
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">Aucune facture disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
