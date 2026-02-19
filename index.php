<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: accueil.php");
    exit;
}

require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];

    $sql  = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $res  = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($res);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: accueil.php");
        exit;
    } else {
        $error = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – Portfolio Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">⚡ Portfolio</div>
        <p class="auth-subtitle">Connectez-vous pour accéder au panneau d'administration</p>

        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group" style="text-align:left">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" placeholder="ex: admin" required autocomplete="username">
            </div>
            <div class="form-group" style="text-align:left">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem">
                🔐 Se connecter
            </button>
        </form>

        <hr class="divider">
        <p style="font-size:0.85rem;color:var(--text-muted)">
            Pas encore de compte ? <a href="inscription.php">Créer un compte admin</a>
        </p>
    </div>
</div>
</body>
</html>
