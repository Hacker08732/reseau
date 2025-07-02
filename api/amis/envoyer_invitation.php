<?php
session_start(); 
include('../database.php');
$currentUser = $_SESSION['user_id'] ?? 0; 
$donnees = json_decode(file_get_contents("php://input"), true); 
$destinataire = $donnees['receiver_id'] ?? null; 

try 
{
    // Vérifie si une invitation existe déjà 
    $check = $pdo->prepare("SELECT * 
                             FROM amis
                             WHERE sender_id = :sender_id AND receiver_id = :receiver_id"); 
    $check->execute(['sender_id' => $currentUser, 'receiver_id' => $destinataire]); 
    if ($check->rowCount() === 0 && $destinataire != $currentUser) {
        $stmt = $pdo->prepare("INSERT INTO amis (sender_id, receiver_id) VALUES (:sender_id, :receiver_id)"); 
        $stmt->execute(['sender_id' => $currentUser, 'receiver_id' => $destinataire]);
        echo json_encode([
            'statut' => 'success',
            'message' => 'Invitation envoyée avec succès.'
        ]);
    } else {
        echo json_encode([
            'statut' => 'error',
            'message' => 'Invitation déjà existante ou destinataire invalide.'
        ]);
    }
} 
catch (PDOException $e) 
{
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de l\'envoi de l\'invitation : ' . $e->getMessage()
    ]); 
}