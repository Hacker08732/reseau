<?php 
// Vérification de l'authentification de l'administrateur
/**vérification de l'authentification du  l'admis*/

session_start();
// Inclusion de la connexion à la base de données
include('../database.php');
// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user'])) {
    // Si non, retourne une erreur JSON et arrête le script
    echo json_encode(['success' => false, 
                      'message' => 'Non authentifié']);
    exit();
}

// Vérifie la présence des champs requis pour l'ajout d'un utilisateur
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['mot_de_passe']) && isset($_POST['photo'])) {
   $nom = strip_tags(($_POST['nom']));
   $prenom = strip_tags(($_POST['prenom']));
   $email = strip_tags(($_POST['email']));
   $mot_de_passe = password_hash($_POST['mot_de_passe'],PASSWORD_DEFAULT);
   
    // Vérifie et crée le dossier uploads s'il n'existe pas
    if (!is_dir('../uploads/')) {
            mkdir('../uploads/', 0777, true);
        }
    
         $nomTemporaire = $_FILES['image']['tmp_name']; 
         $nomFichier = basename($_FILES['image']['name']); 
         $cheminDestination = '../uploads/'.$nomFichier;// pour le stockage de l'image
         $cheminBDD = 'uploads/'.$nomFichier; // pour la base de données
            

        if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
            $user_id = $_SESSION['user']['id'];
           
            // Prépare et exécute la requête d'insertion de l'utilisateur
            $req = $pdo->prepare("INSERT INTO users(nom,prenom,email,mot_de_passe,photo) VALUES (:nom,:prenom,:email,:mot_de_passe,:photo)");
            $stmt = $req->execute([
                    "nom" => $nom,
                    "prenom" =>$prenom,
                    "email" =>$email,
                    "mot_de_passe" =>$mot_de_passe,
                    "photo" =>$cheminBDD
            ]);
}
}
?>