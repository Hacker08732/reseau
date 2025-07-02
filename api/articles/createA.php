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

// Vérifie que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie la présence des champs requis et l'absence d'erreur sur l'image
    if (isset($_POST['description']) && isset($_FILES['image']) && ($_FILES['image']['error'] === 0)) {
        $description = strip_tags(($_POST['description']));

        // Vérifie et crée le dossier uploads s'il n'existe pas
        if (!is_dir('../uploads/')) {
            mkdir('../uploads/', 0777, true);
        }
    
         $nomTemporaire = $_FILES['image']['tmp_name']; 
         $nomFichier = basename($_FILES['image']['name']); 
         $cheminDestination = '../uploads/'.$nomFichier; // pour le stockage de l'image
         $cheminBDD = 'uploads/'.$nomFichier; // pour la base de données
            

        // Déplace l'image uploadée dans le dossier prévu
        if (move_uploaded_file($nomTemporaire, $cheminDestination)) {
            $user_id = $_SESSION['user']['id'];
            // Prépare et exécute l'insertion de l'article
            $req = $pdo->prepare("INSERT INTO articles(description,image,user_id ) VALUES (:description,:image,:user_id)");
            $stmt = $req->execute([
                "description" =>$description,
                "image" =>$cheminBDD ,
                "user_id"=>$user_id 
            ]);
                
                if ($stmt) {
                // Succès de la création
                echo json_encode(['statut' => 'success', 
                                  'message' => 'Article créé']);
                } else {
                    // Erreur lors de l'insertion en base
                    echo json_encode(['statut' => 'error', 
                                      'message' => "Erreur lors de l'enregistrement dans la base de données"]);
                }
            } else {
                // Erreur lors de l'upload de l'image
                echo json_encode(['statut' => 'error', 
                                  'message' => "Erreur lors de l'envoi de l'image"]);
            }
        } else {
            // Erreur si des champs sont manquants
            echo json_encode(['statut' => 'error', 
                              'message' => 'Tous les champs sont obligatoires.']);
        }
}else {    
    // Erreur si la méthode n'est pas POST
    echo json_encode(['statut' => 'error', 
                      'message' => 'Méthode non autorisée']);
    }
?>