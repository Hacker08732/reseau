<?php 
session_start(); 
include('../database.php');
$currentUser = $_SESSION['user_id'] ?? 0; 
try 
{ 
    $stmt = $pdo->prepare("SELECT id, nom, photo 
                              FROM users 
                              WHERE id != ?"); 
    $stmt->execute([$currentUser]); 
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    echo json_encode([
        'statut' => 'success',
        'utilisateurs' => $utilisateurs
    ]); 
}
catch (PDOException $e) 
{ 
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage()
    ]); 
}