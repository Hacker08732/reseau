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

if(isset($_GET["id"]) && !empty($_GET["id"])) 
{
    $id = strip_tags($_GET["id"]);
    $req = $pdo->prepare("SELECT * FROM articles WHERE id=:id");
    $req->execute(["id" => $id ]);
    $image = $req->fetch(PDO::FETCH_ASSOC);
    

}else{
   echo json_encode(['statut' => 'error', 
                     'message' => "ID d'article manquant ou invalide."]);
    exit();
}

//Traitement du formulaire de modification
if(isset( $_POST["description"] ) && isset( $_FILES["image"]) ){

    $description = strip_tags($_POST["description"]);

    // Gestion de l'image
   if ($_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
    // Gestion de l'upload de la nouvelle image
    if (!is_dir('../uploads/')) {
        mkdir('../uploads/', 0777, true);
    }
    $nomTemporaire = $_FILES['image']['tmp_name'];
    $nomFichier = basename($_FILES['image']['name']);
    $cheminDestination = '../uploads/' . $nomFichier;
    $cheminBDD = 'uploads/' . $nomFichier;

    if (!move_uploaded_file($nomTemporaire, $cheminDestination)) {
        $cheminBDD = $image['image']; // Si l'upload échoue, on garde l'ancienne image
    }
} else {
    $cheminBDD = $image['image']; // On garde l'ancienne image
}

    try{
        $user_id = $_SESSION['user']['id'];
        $req = $pdo->prepare("UPDATE articles SET description=:description, image=:image, user_id=:user_id WHERE id=:id");
        $stmt = $req->execute([
                "id"=> $id,
                "description" => $description, 
                "image"=>$cheminBDD ,
                "user_id"=>$user_id
            ]);

         if ($stmt) {
                echo json_encode(['statut' => 'success', 
                                  'message' => 'Article modifié']);
                } else {
                    echo json_encode(['statut' => 'error', 
                                      'message' => "Erreur lors de l'enregistrement dans la base de données"]);
                }
    }catch (PDOException $e) {
            echo json_encode(['statut' => 'error', 
                              'message' => $e->getMessage()]);
        }
    

        
}
?>


