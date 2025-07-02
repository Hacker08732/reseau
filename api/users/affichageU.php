<?php
// Vérification de l'authentification de l'administrateur
session_start();
// Inclusion de la connexion à la base de données
include('../database.php');
// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user'])) {
    // Si non, retourne une erreur JSON et arrête le script
    echo json_encode(['statut' => 'error', 
                      'message' => 'Non authentifié']);
    exit();
}

try {
    // Récupération de tous les utilisateurs depuis la base de données
    $req = $pdo->query("SELECT * FROM users");
    $users = $req->fetchAll(PDO::FETCH_ASSOC);
    // Réponse uniforme
    echo json_encode([
        'statut' => 'success',
        'utilisateurs' => $users
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage()
    ]);
}
?>

