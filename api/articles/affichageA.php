<?php 
// Démarre la session pour accéder aux variables de session
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
// Récupération des annonces depuis la base de données
$req = $pdo->query("SELECT * FROM articles");
$images = $req->fetchAll(PDO::FETCH_ASSOC);
// Retourne la liste des articles au format JSON
echo json_encode(['statut' => 'success', 'articles' => $images]);
?>