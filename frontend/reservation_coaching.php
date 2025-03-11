<?php
require("../backend/utils/ConnectToBDD.php");
error_log("GET Parameters: " . json_encode($_GET));
require '../stripe/init.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Coaching introuvable.");
}

$coaching_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT titre, description FROM coaching WHERE id = :id");
$stmt->execute(['id' => $coaching_id]);
$coaching = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coaching) {
    die("Coaching introuvable.");
}

$horaires = ["08:00", "09:00", "10:00", "14:00", "15:00", "16:00"];

\Stripe\Stripe::setApiKey('sk_test_51Q98K001jgFsFXMETPpgvXUboCcKrzpoL1WXF6cnIKt5MGkTvrLB9uI39ziCnt9rNUxB54DYCzWMAOGXyxwP2c2X00yLr8oWTz');
$paymentIntent = \Stripe\PaymentIntent::create(['amount' => 5000, 'currency' => 'eur']);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation - <?= htmlspecialchars($coaching['titre']); ?></title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($coaching['titre']); ?></h1>
        <p><?= htmlspecialchars($coaching['description']); ?></p>

        <h2>Sélectionnez une date :</h2>
        <input type="date" id="selected-date" value="<?= date('Y-m-d') ?>" required>

        <h2>Sélectionnez un horaire :</h2>
        <div id="horaire-container"></div>

        <form id="payment-form">
            <div id="card-element"></div>
            <button type="submit" id="submit" disabled>Réserver ma place (50,00€)</button>
        </form>
        <div id="payment-message"></div>
    </div>

    <script>
        const horaires = <?= json_encode($horaires); ?>;
        const stripe = Stripe("pk_test_51Q98K001jgFsFXMEM3vu9B1lai14BgLEjvSUjIjDmWb8ERdH1RvDIsEbxzvSetyJ11sdTPCOXk1Ke4MiiGVitzst00LJys7J8K");
        const elements = stripe.elements();
        const card = elements.create("card");
        card.mount("#card-element");

        let selectedHour = "";
        let selectedDate = document.getElementById("selected-date").value;

       
        async function loadHoraires(date) {
    console.log("Chargement des horaires pour :", date);

    try {
        const response = await fetch(`get_reserved_slots.php?date=${date}`);
        
        // Vérifier si la requête HTTP a réussi
        if (!response.ok) {
            throw new Error(`Erreur serveur : ${response.status}`);
        }

        // Lire la réponse et logguer le JSON reçu
        const reserved = await response.json();
        console.log("Réponse brute reçue de l'API:", reserved);

        // Vérifier si la réponse est bien un tableau
        if (!Array.isArray(reserved)) {
            console.error("Données invalides reçues (pas un tableau).");
            document.getElementById("horaire-container").innerHTML = "<p>Erreur : données invalides.</p>";
            return;
        }

        const container = document.getElementById("horaire-container");
        container.innerHTML = "";

        // Normalisation des heures réservées pour éviter les erreurs de comparaison
        const reservedFormatted = reserved.map(h => h.trim());

        console.log("Horaires disponibles initiaux :", horaires);
        console.log("Horaires réservés formatés :", reservedFormatted);

        let hasAvailableSlot = false;

        horaires.forEach(heure => {
            const formattedHeure = heure.trim();

            console.log(`Vérification : ${formattedHeure} est réservé ?`, reservedFormatted.includes(formattedHeure));

            if (!reservedFormatted.includes(formattedHeure)) {
                const [h, m] = formattedHeure.split(":");
                const fin = (parseInt(h, 10) + 1).toString().padStart(2, '0') + ":" + m;

                const btn = document.createElement("button");
                btn.className = "horaire-btn";
                btn.dataset.hour = formattedHeure;
                btn.innerText = `${formattedHeure} - ${fin}`;
                btn.onclick = () => {
                    document.querySelectorAll(".horaire-btn").forEach(b => b.classList.remove("selected"));
                    btn.classList.add("selected");
                    selectedHour = formattedHeure;
                    checkFormReady();
                };
                console.log("Ajout du bouton :", btn.innerText);
                container.appendChild(btn);
                hasAvailableSlot = true;
            }
        });

        if (!hasAvailableSlot) {
            container.innerHTML = "<p>Aucun créneau disponible pour cette date.</p>";
        }
    } catch (error) {
        console.error("Erreur lors du chargement des horaires :", error);
        document.getElementById("horaire-container").innerHTML = "<p>Erreur lors du chargement des créneaux.</p>";
    }
}







        document.getElementById("selected-date").addEventListener("change", (e) => {
            selectedDate = e.target.value;
            selectedHour = "";
            loadHoraires(selectedDate);
            checkFormReady();
        });

        function checkFormReady() {
            document.getElementById("submit").disabled = !(selectedHour && selectedDate);
        }

        document.getElementById("payment-form").addEventListener("submit", async function(event) {
            event.preventDefault();
            const {paymentIntent, error} = await stripe.confirmCardPayment(
                "<?= $paymentIntent->client_secret ?>", {
                    payment_method: {card: card}
                }
            );

            if (error) {
                document.getElementById("payment-message").textContent = error.message;
            } else if (paymentIntent.status === "succeeded") {
                window.location.href = `success_coaching.php?id=<?= $coaching_id ?>&payment_id=${paymentIntent.id}&date=${selectedDate}&hour=${selectedHour}`;
            }
        });

        // Initial loading
        loadHoraires(selectedDate);
    </script>
</body>
</html>