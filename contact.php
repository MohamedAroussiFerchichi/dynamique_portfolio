<?php
require_once 'db.php';
include  'menu.php';

// Vérifier si l'utilisateur est un administrateur connecté
$is_admin = isset($_SESSION['admin']);
$success = '';
$error   = '';

// 1. TRAITEMENT DU FORMULAIRE (Uniquement pour les visiteurs)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_admin) {
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

// 2. RÉCUPÉRATION DES MESSAGES (Uniquement pour l'administrateur)
$messages = null;
if ($is_admin) {
    // On récupère tous les contacts du plus récent au plus ancien
    $messages = mysqli_query($conn, "SELECT * FROM contact ORDER BY date_envoi DESC");
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
        <!-- Titre dynamique selon l'utilisateur -->
        <?php if ($is_admin): ?>
            <h1>Boîte de Réception</h1>
            <p>Messages reçus depuis votre formulaire de contact</p>
        <?php else: ?>
            <h1>Me Contacter</h1>
            <p>Une idée de projet ? Une question ? N'hésitez pas !</p>
        <?php endif; ?>
    </div>

    <!-- On élargit un peu le conteneur pour la vue admin -->
    <div style="max-width:<?= $is_admin ? '900px' : '700px' ?>;margin:0 auto">
        
        <?php if ($is_admin): ?>
            
            <!-- ========================================== -->
            <!-- VUE ADMINISTRATEUR : LISTE DES MESSAGES    -->
            <!-- ========================================== -->
            <?php if (mysqli_num_rows($messages) === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p>Aucun message reçu pour le moment.</p>
                </div>
            <?php else: ?>
                <!-- Utilisation de single column grid pour les messages -->
                <div class="cards-grid" style="grid-template-columns: 1fr;"> 
                    <?php while ($msg = mysqli_fetch_assoc($messages)): ?>
                        <div class="card animate-in" style="padding:1.5rem; text-align:left;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; border-bottom:1px solid #333; padding-bottom:1rem;">
                                <div>
                                    <h3 style="margin:0; font-size:1.2rem; color:var(--text-primary)"><?= htmlspecialchars($msg['nom']) ?></h3>
                                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" style="color:var(--primary-color); font-size:0.9rem; text-decoration:none;">
                                        ✉️ <?= htmlspecialchars($msg['email']) ?>
                                    </a>
                                </div>
                                <div style="color:var(--text-muted); font-size:0.85rem; background:rgba(255,255,255,0.05); padding:0.3rem 0.6rem; border-radius:4px;">
                                    📅 <?= date('d/m/Y - H:i', strtotime($msg['date_envoi'])) ?>
                                </div>
                            </div>
                            <div style="color:var(--text-secondary); line-height:1.6; white-space:pre-wrap;"><?= htmlspecialchars($msg['message']) ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            
            <!-- ========================================== -->
            <!-- VUE VISITEUR : FORMULAIRE DE CONTACT       -->
            <!-- ========================================== -->
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

        <?php endif; ?>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Tous droits réservés</p></footer>
</body>
</html>
