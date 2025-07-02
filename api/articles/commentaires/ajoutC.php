<?php
// Démarre la session pour accéder aux variables de session
session_start();
// Inclusion de la connexion à la base de données
include('../../database.php');

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user'])) {
    // Si non, retourne une erreur JSON et arrête le script
    echo json_encode(['statut' => 'error', 
                      'message' => 'Non authentifié']);
    exit();
}

// Vérifie que les champs nécessaires sont présents et non vides
if (isset($_POST['article_id'], $_POST['contenu']) && !empty($_POST['contenu'])) {
    $article_id = $_POST['article_id'];
    $user_id = $_SESSION['user']['id'];
    // Nettoie le contenu du commentaire pour éviter le HTML
    $contenu = strip_tags($_POST['contenu']);
    try {
        // Prépare et exécute la requête d'insertion du commentaire
        $stmt = $pdo->prepare("INSERT INTO commentaires (article_id, user_id, contenu) VALUES (:article_id, :user_id, :contenu)");
        $stmt->execute([
            'article_id' => $article_id,
            'user_id' => $user_id,
            'contenu' => $contenu
        ]);
        // Retourne un message de succès
        echo json_encode([
            'statut' => 'success',
            'message' => 'Commentaire ajouté',
        ]);
    } catch (PDOException $e) {
        // Retourne une erreur si l'insertion échoue
        echo json_encode([
            'statut' => 'error',
            'message' => "Erreur lors de l'ajout du commentaire"
        ]);
    }
} else {
    // Retourne une erreur si des champs sont manquants
    echo json_encode(['statut' => 'error', 'message' => 'Champs manquants']);
}
