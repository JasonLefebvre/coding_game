<?php
session_start();
require_once("../utils/ConnectToBDD.php");

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    try {
        // Delete from database
        $query = "DELETE FROM indisponibilites WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$data['id']]);

        // Return success response
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Return error response
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Return error if no ID provided
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}