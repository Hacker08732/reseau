<?php
// Démarre la session pour accéder aux variables de session
session_start(); 
// Inclusion de la connexion à la base de données
include('../database.php'); 
// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'] ?? 0;

try {
    // Récupère l'ID du destinataire et le texte du message depuis le POST
    $destinataire = $_POST['receiver_id'] ?? 0; 
    $texte = $_POST['message'] ?? ''; 
    $image = '';

    // Si une image est envoyée, on la traite et on la déplace dans le dossier uploads
    if (!empty($_FILES['image']['name'])) {
        $nomFichier = uniqid() . '_' . $_FILES['image']['name']; 
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $nomFichier); 
        $image = "uploads/" . $nomFichier; 
    }

    // Prépare et exécute la requête d'insertion du message
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message, image) VALUES (:sender_id, :receiver_id, :message, :image)"); 
    $stmt->execute([
        'sender_id' => $user_id,
        'receiver_id' => $destinataire,
        'message' => $texte,
        'image' => $image
    ]);
    // Retourne un message de succès au format uniforme
    echo json_encode([
        'statut' => 'success',
        'message' => 'Message envoyé avec succès.'
    ]);
} catch (PDOException $e) { 
    // Gestion des erreurs de base de données
    echo json_encode([
        'statut' => 'error',
        'message' => 'Erreur lors de l\'envoi du message : ' . $e->getMessage()
    ]); 
}
