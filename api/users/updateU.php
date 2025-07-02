<?php 
// Vérification de l'authentification de l'administrateur
/**vérification de l'authentification du  l'admis*/

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

// Depuis la balise a avec val = 'modifier' de afficher.php
if( isset($_GET["id"]) && !empty($_GET["id"]))
{
        $id = strip_tags($_GET["id"]);
        // Récupère les informations de l'utilisateur à modifier
        $req = $pdo->prepare("SELECT * FROM users WHERE id=:id");
        $req->execute(["id" => $id ]);
        $user = $req->fetch(PDO::FETCH_ASSOC); 
    
    
}else{
   // Erreur si l'ID est manquant ou invalide
   echo json_encode(['statut' => 'error', 
                     'message' => "ID d'utilisateur manquant ou invalide."]);
    exit();
}

// Traitement du formulaire de modification
if( isset( $_POST["id"] ) && isset( $_POST["nom"] ) && isset( $_POST["prenom"] ) && isset( $_POST["email"] )  && isset( $_POST["photo"] )){

    $id = strip_tags($_POST["id"]);
    $nom = strip_tags($_POST["nom"]);
    $prenom = strip_tags($_POST["prenom"]);
    $email = strip_tags($_POST["email"]);

    // Gestion de la photo
   if ($_FILES['photo']['error'] == 0 && $_FILES['photo']['size'] > 0) {
    // Gestion de l'upload de la nouvelle photo
    if (!is_dir('../uploads/')) {
        mkdir('../uploads/', 0777, true);
    }
    $nomTemporaire = $_FILES['photo']['tmp_name'];
    $nomFichier = basename($_FILES['photo']['name']);
    $cheminDestination = '../uploads/' . $nomFichier;
    $cheminBDD = 'uploads/' . $nomFichier;

    if (!move_uploaded_file($nomTemporaire, $cheminDestination)) {
        $cheminBDD = $photo['photo']; // Si l'upload échoue, on garde l'ancienne photo
    }
} else {
    $cheminBDD = $photo['photo']; // On garde l'ancienne photo
}

    try{
        $user_id = $_SESSION['user']['id'];
        // Prépare et exécute la requête de mise à jour de l'utilisateur
        $req = $pdo->prepare("UPDATE users SET nom=:nom, prenom=:prenom, email=:email, photo=:photo WHERE id=:id");
        $stmt = $req->execute([
                "id"=> $id,
                "nom"=> $nom, 
                "prenom" => $prenom, 
                "email"=>$email,
                "photo"=>$cheminBDD,
                "user_id"=>$user_id
            ]);

         if ($stmt) {
                // Succès de la modification
                echo json_encode(['statut' => 'success', 
                                  'message' => 'Utilisateur modifié']);
                } else {
                    // Erreur lors de la mise à jour
                    echo json_encode(['statut' => 'error', 
                                      'message' => "Erreur lors de l'enregistrement dans la base de données"]);
                }
    }catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(['statut' => 'error', 
                              'message' => $e->getMessage()]);
        }       
}
?>
