<?php
// Inclusion de la connexion à la base de données
include("../database.php");

// Vérifie que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des champs requis
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = strip_tags($_POST['email']) ;
        $password = $_POST['password'];

        try {
            // Prépare et exécute la requête pour récupérer l'utilisateur par email
            $req = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $req->execute(['email' => $email]);
            $user = $req->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // Utilisateur non trouvé
                echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
            } elseif (password_verify($password, $user['mot_de_passe'])) {
                // Mot de passe correct, connexion réussie
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user['id'],
                        'nom' => $user['nom'],
                        'prenom' => $user['prenom'],
                        'email' => $user['email']
                    ]
                ]);
            } else {
                // Mot de passe incorrect
                echo json_encode(['success' => false, 
                                  'message' => 'Mot de passe incorrect']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(['success' => false, 
                              'message' => $e->getMessage()]);
        }
    } else {
        // Erreur si des champs sont manquants
        echo json_encode(['success' => false,
                          'message' => 'Champs manquants']);
    }
} else {
    // Erreur si la méthode n'est pas POST
    echo json_encode(['success' => false, 
                      'message' => 'Methode non autorisee']);
}