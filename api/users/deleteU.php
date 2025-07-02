<?php
// Vérification de l'authentification de l'administrateur
require("../database.php");
// Vérifie que l'identifiant de l'utilisateur est bien fourni dans l'URL
if( isset($_GET["id"]) )
{
    if(!empty($_GET["id"]))
    {
        $id = strip_tags($_GET["id"]);
        // Prépare et exécute la requête de suppression de l'utilisateur
        $req = $pdo->prepare("DELETE FROM users WHERE id=:id");
        $stmt = $req->execute(['id'=> $id]);
        
        if ($stmt) {
            // Succès de la suppression
            echo json_encode(['success' => true, 
                              'message' => 'Article supprimé']);
        } else {
            // Erreur lors de la suppression
            echo json_encode(['success' => false, 
                             'message' => "Erreur lors de la suppression dans la base de données"]);
        }
    }else{
    // Erreur si l'ID est manquant ou invalide
    echo json_encode(['success' => false, 
                      'message' => 'ID d\'article manquant ou invalide.']);
}
}
?>