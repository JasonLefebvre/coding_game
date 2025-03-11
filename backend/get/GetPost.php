<?php
// Désactiver l'affichage des erreurs dans la sortie
ini_set('display_errors', 0);
error_reporting(0);

// Vider tout buffer de sortie existant
while (ob_get_level()) {
    ob_end_clean();
}

// En-têtes pour empêcher la mise en cache et spécifier le type de contenu
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once("../utils/ConnectToBDD.php");

try {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        
        $query = "SELECT id, titre, contenu, date_publie FROM post WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            // Nettoyer les données
            $post['titre'] = trim($post['titre']);
            $post['contenu'] = trim($post['contenu']);
            
            echo json_encode($post, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Post non trouvé']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID non fourni']);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
    exit;
} 