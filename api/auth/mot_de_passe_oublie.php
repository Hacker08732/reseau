<?php
include("database.php");
$afficherForm = true;
$message = "";
$prenom = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = $_POST['email'];
    $req = $pdo->prepare("SELECT id, prenom FROM users WHERE email = :email");
    $req->execute(['email' => $email]);
    $user = $req->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $afficherForm = false;
        $prenom = $user['prenom'];
    } else {
        $message = "Cet email n'existe pas.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réinitialisation de votre mot de passe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <style>
    body {
      background: #1877f2;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .logo-facebook {
      font-size: 3rem;
      color: #1877f2;
      font-weight: bold;
      letter-spacing: -2px;
      margin-bottom: 10px;
      text-align: center;
    }
    .btn-facebook {
      background-color: #1877f2;
      border: none;
    }
    .btn-facebook:hover {
      background-color: #145db2;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4 mt-5">
          <?php if ($afficherForm): ?>
            <h4 class="mb-3 text-center">Réinitialisation de votre mot de passe</h4>
            <form action="" method="post" autocomplete="off">
              <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" name="email" id="email" class="form-control rounded-pill" placeholder="Entrez votre email" required>
              </div>
              <?php if ($message): ?>
                <div class="alert alert-danger py-2"><?= strip_tags($message) ?></div>
              <?php endif; ?>
              <div class="d-grid mb-2">
                <button type="submit" class="btn btn-facebook rounded-pill fw-bold">Vérifier</button>
              </div>
            </form>
            <div class="text-center mt-3">
              <span class="text-muted small">Pas encore de compte ?</span>
              <a href="register.php" class="small text-primary">Créer un compte</a>
            </div>
            <div class="text-center mt-2">
              <a href="loginC.php" class="btn btn-outline-primary rounded-pill">Retour à la connexion</a>
            </div>
          <?php else: ?>
            <h4 class="mb-3 text-center">Bonjour <?= strip_tags($prenom) ?>,</h4>
            <p class="text-center">Vous avez demandé à réinitialiser votre mot de passe.</p>
            <div class="d-grid mb-3">
              <a href="nouveau_mot_de_passe.php?email=<?= urlencode($email) ?>" class="btn btn-facebook rounded-pill fw-bold">
                Réinitialiser mon mot de passe
              </a>
            </div>
            <p class="text-center text-muted small">Si vous n'avez pas fait cette demande, ignorez cet e-mail.</p>
            <p class="text-center text-muted small mb-4">Merci, <br>L’équipe MonService</p>
            <div class="text-center">
              <a href="loginC.php" class="btn btn-danger rounded-pill">Ignorer</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>