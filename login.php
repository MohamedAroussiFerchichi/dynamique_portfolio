<?php
// Démarrage de la session pour maintenir l'état de connexion de l'utilisateur
session_start();

// Redirection vers le panneau d'administration si l'utilisateur est déjà connecté
if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Inclusion du fichier de connexion à la base de données
require_once 'db.php';
$error = '';

// Vérification si le formulaire de connexion a été soumis (requête POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurisation de l'entrée contre les injections SQL et retrait des espaces inutiles
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];

    // Recherche de l'utilisateur dans la base de données
    $sql  = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $res  = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($res);

    // Vérification de l'existence de l'utilisateur ET de la validité du mot de passe (comparaison avec le hash)
    if ($admin && password_verify($password, $admin['password'])) {
        // Enregistrement de l'utilisateur dans la session
        $_SESSION['admin'] = $admin['username'];
        header("Location: index.php");
        exit;
    } else {
        // Message d'erreur en cas d'échec
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

            <div style="text-align:center; margin-top:1rem;">
                <a href="index.php" style="color:var(--text-muted); font-size:0.9rem; text-decoration:none;">
                    👁️ Continuer en tant qu'invité
                </a>
            </div>
        </form>

        <hr class="divider">
        <p style="font-size:0.85rem;color:var(--text-muted)">
            Pas encore de compte ? <a href="inscription.php">Créer un compte admin</a>
        </p>
    </div>
</div>
</body>
</html>
