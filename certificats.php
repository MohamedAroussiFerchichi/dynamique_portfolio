<?php
require_once 'db.php';
include  'menu.php';

$is_admin = isset($_SESSION['admin']);
$certs    = mysqli_query($conn, "SELECT * FROM certificats ORDER BY date_obtention DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificats – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header animate-in">
        <h1>Mes Certificats</h1>
        <p>Mes formations et certifications professionnelles</p>
    </div>

    <?php if ($is_admin): ?>
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.5rem">
        <a href="ajouter_certificat.php" class="btn btn-primary">➕ Ajouter un certificat</a>
    </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($certs) === 0): ?>
        <div class="empty-state">
            <div class="empty-icon">🏆</div>
            <p>Aucun certificat pour le moment.</p>
            <?php if ($is_admin): ?><a href="ajouter_certificat.php" class="btn btn-primary" style="margin-top:1rem">➕ Ajouter votre premier certificat</a><?php endif; ?>
        </div>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:1rem">
        <?php while ($c = mysqli_fetch_assoc($certs)): 
            $ext = strtolower(pathinfo($c['fichier'], PATHINFO_EXTENSION));
            $icon = ($ext === 'pdf') ? '📄' : '🖼️';
        ?>
        <div class="cert-card animate-in">
            <div class="cert-icon"><?= $icon ?></div>
            <div class="cert-info">
                <div class="cert-title"><?= htmlspecialchars($c['titre']) ?></div>
                <div class="cert-date">📅 <?= $c['date_obtention'] ? date('d/m/Y', strtotime($c['date_obtention'])) : 'Date non définie' ?></div>
            </div>
            <div class="cert-actions">
                <?php if ($c['fichier']): ?>
                    <a href="fichiers/<?= htmlspecialchars($c['fichier']) ?>" target="_blank" class="btn btn-success btn-sm">⬇️ Voir</a>
                <?php endif; ?>
                <?php if ($is_admin): ?>
                    <a href="modifier_certificat.php?id=<?= $c['id'] ?>" class="btn btn-secondary btn-sm">✏️</a>
                    <a href="supprimer_certificat.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer ce certificat ?')">🗑️</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Tous droits réservés</p></footer>
</body>
</html>
