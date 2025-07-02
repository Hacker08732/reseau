<?php 
// Démarre la session pour accéder aux variables de session
session_start(); 
// Inclusion de la connexion à la base de données
include('../database.php');
// Récupère l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'] ?? 0; 

try{
    // Prépare la requête pour récupérer les utilisateurs avec qui il y a une conversation
    $stmt = $pdo->prepare(" SELECT DISTINCT users.id, users.nom, users.prenom, users.photo 
                         FROM users 
                         JOIN messages ON ( (messages.sender_id = :userId AND messages.receiver_id = users.id) OR (messages.receiver_id = :userId AND messages.sender_id = users.id) ) 
                         WHERE users.id != :userId "); 

    // Exécute la requête avec l'ID utilisateur
    $stmt->execute(['userId' => $userId]); 
    // Retourne la liste des conversations au format JSON uniforme
    echo json_encode([
        'statut' => 'success',
        'conversations' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]); 
} 
catch (PDOException $e) 
{ 
    // Gestion des erreurs de base de données
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la récupération des conversations : ' . $e->getMessage()
    ]); 
}