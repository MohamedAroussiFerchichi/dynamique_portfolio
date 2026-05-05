<?php
// Démarrer la session (utile si on veut utiliser des variables de session)
session_start();

// Inclure la configuration de connexion à la base de données
require_once 'db.php';

// Initialiser les variables pour afficher des messages à l'utilisateur
$success = '';
$error   = '';

// Vérifier si le formulaire a été soumis via la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Récupération et nettoyage des données soumises
    // mysqli_real_escape_string protège contre les injections SQL
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // 2. Validation des données
    if (strlen($username) < 3) {
        $error = "Le nom d'utilisateur doit comporter au moins 3 caractères.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit comporter au moins 6 caractères.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // 3. Vérification de l'existence du nom d'utilisateur
        // On cherche si le pseudo existe déjà dans la table admin
        $check = mysqli_query($conn, "SELECT id FROM admin WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Ce nom d'utilisateur est déjà pris.";
        } else {
            // 4. Création et sécurisation du compte
            // HASHAge du mot de passe : on ne stocke JAMAIS un mot de passe en clair dans la BDD
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // Préparation de la requête pour insérer le nouvel administrateur
            $sql    = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed')";
            
            // Exécution de la requête
            if (mysqli_query($conn, $sql)) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Erreur lors de la création du compte.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Admin – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:480px">
        <div class="auth-logo">🔑 Inscription</div>
        <p class="auth-subtitle">Créer un compte administrateur</p>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?>
                <br><a href="index.php" class="btn btn-primary btn-sm" style="margin-top:0.75rem">Se connecter</a>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="">
            <div class="form-group" style="text-align:left">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" required minlength="3" placeholder="ex: admin">
            </div>
            <div class="form-group" style="text-align:left">
                <label>Mot de passe</label>
                <input type="password" name="password" required minlength="6" placeholder="Minimum 6 caractères">
            </div>
            <div class="form-group" style="text-align:left">
                <label>Confirmer mot de passe</label>
                <input type="password" name="confirm" required minlength="6" placeholder="Répétez le mot de passe">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                ✅ Créer le compte
            </button>
        </form>
        <?php endif; ?>

        <hr class="divider">
        <p style="font-size:0.85rem;color:var(--text-muted)">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </p>
    </div>
</div>
</body>
</html>
