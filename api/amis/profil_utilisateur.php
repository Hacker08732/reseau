<?php 
session_start(); 
include('../database.php');
if (!isset($_SESSION['user'])) {
    echo json_encode(['statut' => 'error', 
                      'message' => 'Non authentifié']);
    exit();
}

try
{ 
    $stmt = $pdo->prepare("SELECT id, nom, email, photo 
                           FROM users 
                           WHERE id = ?"); 
    $stmt->execute([$_GET['id']]); 
    $profil = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode([
        'statut' => 'success',
        'profil' => $profil
    ]); 
} 
catch (PDOException $e) {
     echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la récupération du profil : ' . $e->getMessage()
     ]); 
}