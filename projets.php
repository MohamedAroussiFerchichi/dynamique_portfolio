<?php
require_once 'db.php';
include  'menu.php';

$is_admin = isset($_SESSION['admin']);
$projets  = mysqli_query($conn, "SELECT * FROM projets ORDER BY date_projet DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header animate-in">
        <h1>Mes Projets</h1>
        <p>Une sélection de mes réalisations et projets personnels</p>
    </div>

    <?php if ($is_admin): ?>
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.5rem">
        <a href="ajouter_projet.php" class="btn btn-primary">➕ Ajouter un projet</a>
    </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($projets) === 0): ?>
        <div class="empty-state">
            <div class="empty-icon">🚀</div>
            <p>Aucun projet pour le moment.</p>
            <?php if ($is_admin): ?><a href="ajouter_projet.php" class="btn btn-primary" style="margin-top:1rem">➕ Ajouter votre premier projet</a><?php endif; ?>
        </div>
    <?php else: ?>
    <div class="cards-grid">
        <?php while ($p = mysqli_fetch_assoc($projets)): ?>
        <div class="card animate-in">
            <?php if ($p['image']): ?>
                <img class="card-img" src="fichiers/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['titre']) ?>">
            <?php else: ?>
                <div class="card-img-placeholder">🚀</div>
            <?php endif; ?>
            <div class="card-body">
                <?php if ($p['technologie']): ?>
                    <span class="card-tag"><?= htmlspecialchars($p['technologie']) ?></span>
                <?php endif; ?>
                <div class="card-title"><?= htmlspecialchars($p['titre']) ?></div>
                <div class="card-desc"><?= htmlspecialchars($p['description']) ?></div>
                <?php if ($p['date_projet']): ?>
                <div class="card-meta">
                    📅 <?= date('d/m/Y', strtotime($p['date_projet'])) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($is_admin): ?>
            <div class="card-actions">
                <a href="modifier_projet.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm">✏️ Modifier</a>
                <a href="supprimer_projet.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Supprimer ce projet ?')">🗑️ Supprimer</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Tous droits réservés</p></footer>
</body>
</html>
