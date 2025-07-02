<?php 
// Démarre la session pour accéder aux variables de session
session_start(); 
// Inclusion de la connexion à la base de données
include('../database.php'); 
try {
    // Récupère l'ID de l'utilisateur courant et celui de l'autre utilisateur
    $me = $_SESSION['user_id'] ?? 0; 
    $autre = $_GET['utilisateur_id'] ?? 0; 
    // Prépare la requête pour récupérer les messages échangés entre les deux utilisateurs
    $stmt = $pdo->prepare(" SELECT * 
                            FROM messages
                            WHERE (sender_id = :me AND receiver_id = :autre) OR (sender_id = :autre AND receiver_id = :me) 
                            ORDER BY created_at ASC "); 

    // Exécute la requête avec les deux IDs
    $stmt->execute(['me' => $me, 'autre' => $autre]); 
    // Retourne la liste des messages au format JSON uniforme
    echo json_encode([
        'statut' => 'success',
        'messages' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (PDOException $e) 
{ 
    // Gestion des erreurs de base de données
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la récupération des messages : ' . $e->getMessage()
    ]); 
}