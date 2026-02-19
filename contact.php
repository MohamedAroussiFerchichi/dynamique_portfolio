<?php
require_once 'db.php';
include  'menu.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim(mysqli_real_escape_string($conn, $_POST['nom']));
    $email   = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $message = trim(mysqli_real_escape_string($conn, $_POST['message']));

    if (empty($nom) || empty($email) || empty($message)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {
        $sql = "INSERT INTO contact (nom, email, message) VALUES ('$nom', '$email', '$message')";
        if (mysqli_query($conn, $sql)) {
            $success = "Votre message a été envoyé avec succès ! Je vous répondrai dans les plus brefs délais.";
        } else {
            $error = "Erreur lors de l'envoi. Veuillez réessayer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header animate-in">
        <h1>Me Contacter</h1>
        <p>Une idée de projet ? Une question ? N'hésitez pas !</p>
    </div>

    <div style="max-width:700px;margin:0 auto">
        <?php if ($success): ?>
            <div class="alert alert-success" style="font-size:1rem;padding:1.25rem">
                ✅ <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <div class="form-card animate-in">
            <h2>✉️ Envoyer un message</h2>
            <p class="subtitle">Je vous répondrai dès que possible</p>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="nom" placeholder="Votre nom" required
                               value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Adresse email</label>
                        <input type="email" name="email" placeholder="votre@email.com" required
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" placeholder="Décrivez votre projet ou votre demande..." required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    🚀 Envoyer le message
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Infos rapides -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-top:2rem">
            <div class="info-block" style="text-align:center">
                <div style="font-size:2rem;margin-bottom:0.5rem">📧</div>
                <div style="font-size:0.85rem;color:var(--text-secondary)">Email disponible sur LinkedIn</div>
            </div>
            <div class="info-block" style="text-align:center">
                <div style="font-size:2rem;margin-bottom:0.5rem">⚡</div>
                <div style="font-size:0.85rem;color:var(--text-secondary)">Réponse sous 24–48h</div>
            </div>
            <div class="info-block" style="text-align:center">
                <div style="font-size:2rem;margin-bottom:0.5rem">🤝</div>
                <div style="font-size:0.85rem;color:var(--text-secondary)">Freelance & missions courtes</div>
            </div>
        </div>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Tous droits réservés</p></footer>
</body>
</html>
