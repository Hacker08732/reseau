<?php 
// Démarre la session pour accéder aux variables de session
session_start(); 
include('../database.php');

// Récupère l'ID de l'utilisateur connecté
$me = $_SESSION['user_id'] ?? 0; 
// Récupère le mot-clé de recherche depuis l'URL
$motcle = $_GET['q'] ?? ''; 

try {
    // Prépare la requête pour rechercher les utilisateurs par nom d'utilisateur
    $stmt = $pdo->prepare(" SELECT id, nom, photo 
                        FROM users 
                        WHERE nom LIKE ? AND id != ? 
                        LIMIT 10 "); 
    // Exécute la requête avec le mot-clé et l'ID utilisateur
    $stmt->execute(["%" . $motcle . "%", $me]); 
    // Retourne la liste des utilisateurs trouvés au format JSON uniforme
    echo json_encode([
        'statut' => 'success',
        'utilisateurs' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la recherche : ' . $e->getMessage()
    ]);
}