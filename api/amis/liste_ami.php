<?php 
session_start();
include('../database.php');
$currentUser = $_SESSION['user_id'] ?? 0; 

try 
{
    $stmt = $pdo->prepare(" SELECT u.id, u.username, u.photo
                            FROM users u 
                            JOIN amis a ON ( (a.sender_id = ? AND a.receiver_id = u.id) OR (a.receiver_id = ? AND a.sender_id = u.id) ) WHERE a.status = 'accepted' "); 
    $stmt->execute([$currentUser, $currentUser]); 
    echo json_encode(['statut' => 'success', 
                      'amis' => $stmt->fetchAll(PDO::FETCH_ASSOC)]); 
} 
catch (PDOException $e) { 
    echo json_encode(['statut' => 'error', 
                      'message' => $e->getMessage()]); 
}