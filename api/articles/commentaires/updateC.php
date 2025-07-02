<?php
// Démarre la session pour vérifier l'authentification
session_start();
include('../../database.php');

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user'])) {
    echo json_encode(['statut' => 'error', 'message' => 'Non authentifié']);
    exit();
}

// Vérifie la présence des paramètres requis
if (isset($_POST['id'], $_POST['contenu']) && !empty($_POST['contenu'])) {
    $id = strip_tags($_GET["id"]);
    $contenu = strip_tags($_POST['contenu']);

    try {
        // Prépare et exécute la requête de mise à jour du commentaire
        $stmt = $pdo->prepare("UPDATE commentaires SET contenu = :contenu WHERE id = :id");
        $stmt->execute(['contenu' => $contenu, 'id' => $id]);
        echo json_encode(['statut' => 'success', 'message' => 'Commentaire modifié']);
    } catch (PDOException $e) {
        echo json_encode(['statut' => 'error', 'message' => 'Erreur lors de la modification : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['statut' => 'error', 'message' => 'Champs manquants']);
}
