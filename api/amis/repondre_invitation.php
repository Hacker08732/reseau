<?php 
session_start(); 
include('../database.php');
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 
                      'message' => 'Non authentifié']);
    exit();
}

$donnees = json_decode(file_get_contents("php://input"), true); 
$requestId = $donnees['request_id'] ?? null; 
$decision = $donnees['response'] ?? 'refuse'; 
// 'accepted' ou 'declined' 
try 
{ 
    $stmt = $pdo->prepare("UPDATE amis SET status = ? WHERE id = ?"); 
    $stmt->execute([$decision, $requestId]);
    echo json_encode(["status" => "mise à jour"]); 
}
catch (PDOException $e) 
{ 
    echo json_encode(["erreur" => $e->getMessage()]); 
} 