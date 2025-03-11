<?php
session_start(); // Démarrer la session pour récupérer l'utilisateur connecté
require("../backend/utils/ConnectToBDD.php");
require '../stripe/init.php'; // Ajuster le chemin si nécessaire

// ✅ Vérifier si l'utilisateur est bien connecté
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("❌ Erreur : utilisateur non connecté. Vérifiez votre connexion.");
}

$user_id = $_SESSION['user_id']; // Récupération de l'ID utilisateur connecté

// ✅ Vérifier que la connexion PDO fonctionne
if (!$pdo) {
    die("Erreur de connexion à la base de données");
}

// ✅ Vérifier l'ID de l'atelier
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['id'])) {
    die("❌ Erreur : atelier non trouvé.");
}

$atelier_id = (int) $_GET['id'];

// ✅ Récupérer les détails de l'atelier
$query = "SELECT id, titre, description, date, heure_debut, heure_fin FROM atelier_ecriture WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $atelier_id, PDO::PARAM_INT);
$stmt->execute();
$atelier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$atelier) {
    die("❌ Erreur : atelier introuvable.");
}

// ✅ Initialiser Stripe avec la clé API
\Stripe\Stripe::setApiKey('sk_test_51Q98K001jgFsFXMETPpgvXUboCcKrzpoL1WXF6cnIKt5MGkTvrLB9uI39ziCnt9rNUxB54DYCzWMAOGXyxwP2c2X00yLr8oWTz');

// ✅ Créer un Payment Intent pour Stripe
$payment_intent = \Stripe\PaymentIntent::create([
    'amount' => 5000, // Prix en centimes (50.00€)
    'currency' => 'eur',
    'payment_method_types' => ['card'],
]);

$client_secret = $payment_intent->client_secret;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation - <?= htmlspecialchars($atelier['titre']); ?></title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4"><?= htmlspecialchars($atelier['titre']); ?></h1>
        <p><strong>Date :</strong> <?= date("d/m/Y", strtotime($atelier['date'])); ?></p>
        <p><strong>Heure :</strong> <?= date("H:i", strtotime($atelier['heure_debut'])); ?> - <?= date("H:i", strtotime($atelier['heure_fin'])); ?></p>
        <p class="mt-4"><?= htmlspecialchars($atelier['description']); ?></p>

        <!-- Stripe Card Form -->
        <form id="payment-form" class="mt-6">
            <div id="card-element" class="p-3 border rounded bg-gray-50"></div>
            <button id="submit" class="mt-4 px-5 py-3 bg-blue-600 text-white rounded-md w-full">
                Réserver ma place (50,00€)
            </button>
            <p id="payment-message" class="mt-3 text-sm text-center"></p>
        </form>
    </div>

    <script>
        const stripe = Stripe("pk_test_51Q98K001jgFsFXMEM3vu9B1lai14BgLEjvSUjIjDmWb8ERdH1RvDIsEbxzvSetyJ11sdTPCOXk1Ke4MiiGVitzst00LJys7J8K");
        const clientSecret = "<?= $client_secret; ?>";

        // Create an instance of Elements
        const elements = stripe.elements();
        const cardElement = elements.create("card");
        cardElement.mount("#card-element");

        document.getElementById("payment-form").addEventListener("submit", async (event) => {
            event.preventDefault();

            document.getElementById("submit").innerText = "Traitement...";
            document.getElementById("submit").disabled = true;

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardElement }
            });

            if (error) {
                document.getElementById("payment-message").innerText = "Paiement échoué : " + error.message;
                document.getElementById("submit").innerText = "Réserver ma place (50,00€)";
                document.getElementById("submit").disabled = false;
            } else {
                document.getElementById("payment-message").innerText = "Paiement réussi ! 🎉";
                document.getElementById("submit").innerText = "Paiement confirmé";
                document.getElementById("submit").disabled = true;

                // Redirection après le paiement réussi
                setTimeout(() => {
                    window.location.href = "success_ecriture.php?id=<?= $atelier_id; ?>&payment_id=" + paymentIntent.id;
                }, 2000);
            }
        });
    </script>
</body>
</html>
