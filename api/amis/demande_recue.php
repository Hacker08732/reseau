<?php
session_start(); 
include('../database.php');
$currentUser = $_SESSION['user_id'] ?? 0; 

try 
{ 
 $stmt = $pdo->prepare(" SELECT amis.id, users.nom,users.prenom, users.photo
                           FROM amis 
                           JOIN users  ON users.id = amis.sender_id 
                           WHERE amis.receiver_id = ? AND amis.status = 'en attente' "); 
 $stmt->execute([$currentUser]); 
 $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC); 
 echo json_encode([
     'statut' => 'success',
     'demandes' => $demandes
 ]); 
} catch (PDOException $e) { 
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la récupération des demandes : ' . $e->getMessage()
    ]); 
}