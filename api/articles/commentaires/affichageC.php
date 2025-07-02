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

// Récupère l'identifiant de l'article depuis l'URL (GET)
$article_id = $_GET['article_id'];
// Prépare la requête pour récupérer les commentaires de l'article avec les infos utilisateur
$stmt = $pdo->prepare("SELECT commentaires.*, users.nom, users.prenom, users.photo 
                       FROM commentaires 
                       JOIN users ON users.id = commentaires.user_id 
                       WHERE article_id = :article_id 
                       ORDER BY created_at ASC");
// Exécute la requête avec l'ID de l'article
$stmt->execute(['article_id' => $article_id]);
// Récupère tous les commentaires sous forme de tableau associatif
$commentaire = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Retourne le résultat au format JSON avec un statut de succès
echo json_encode(['statut' => 'success', 'commentaires' => $commentaire]);



