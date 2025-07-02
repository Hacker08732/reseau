<?php
// Noublie pas de te renseigner sur ca stp
// session et inclusion de la base de données
session_start();
include('../database.php');
// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user'])) {
    // Si non, retourne une erreur JSON et arrête le script
    echo json_encode(['statut' => 'error', 
                      'message' => 'Non authentifié']);
    exit();
}

// Récupère les données envoyées en JSON
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];
$article_id = $data['article_id'];
$type = $data['type']; 

// Vérifie la présence des paramètres nécessaires
if (!$user_id || !$article_id || !$type) {
    echo json_encode(['statut' => 'error', 
                      'message' => 'Paramètres manquants']);
    exit();
}
try{
// Vérifie si un like existe déjà pour cet utilisateur et cet article
$stmt = $pdo->prepare("SELECT * 
                       FROM likes 
                       WHERE user_id = ? AND article_id = ?");
$stmt->execute(['user_id' => $user_id, 'article_id' => $article_id]);
$req = $stmt->fetch();

if ($req) {
    // Si le like existe, on le supprime (toggle)
    $pdo->prepare("DELETE FROM likes WHERE id = ?")->execute([$req['id']]);
    echo json_encode([
        'statut' =>'success',
        'message' => 'Like retiré'
    ]);
    exit();
} else {
    // Sinon, on ajoute le like
    $stmt = $pdo->prepare("INSERT INTO likes (article_id, user_id, type) VALUES (:article_id, :user_id, :type)");
    $stmt->execute([ 'article_id' => $article_id,'user_id'=> $user_id,'type'=> $type]);
     echo json_encode([
        'statut' =>'success',
        'message' => 'Like ajouté'
    ]);
    exit();
}
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
    exit();
}