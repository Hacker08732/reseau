<?php
// Script de déconnexion utilisateur
session_start(); // Démarre la session PHP
session_unset();  // Vide toutes les variables de session
session_destroy(); // Détruit la session côté serveur
?>
<script>
    sessionStorage.clear(); // Vide toutes les données du sessionStorage côté navigateur
    window.location.href = 'login.php'; // Redirige vers la page de connexion
</script>
<?php
// Termine le script PHP après la déconnexion
exit();
?>