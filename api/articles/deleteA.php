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
// Vérifie que l'identifiant de l'article est bien fourni
if( isset($_GET["id"]) && !empty($_GET["id"]))
{
    $id = strip_tags($_GET["id"]);
    // Prépare et exécute la requête de suppression de l'article
    $req = $pdo->prepare("DELETE FROM articles WHERE id=:id");
    $stmt = $req->execute(['id'=> $id]);
        if ($stmt) {
            // Succès de la suppression
            echo json_encode(['statut' => 'success', 
                              'message' => 'Article supprimé']);
        } else {
            // Erreur lors de la suppression
            echo json_encode(['statut' => 'error', 
                             'message' => "Erreur lors de la suppression dans la base de données"]);
            }
}else{
    // Erreur si l'ID est manquant ou invalide
    echo json_encode(['statut' => 'error', 
                      'message' => "ID d'article manquant ou invalide."]);
}
?>