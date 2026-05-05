<?php
// 1. Inclusion de la connexion à la base de données et du menu de navigation
// (Le menu inclut déjà session_start() donc on n'a pas besoin de le remettre ici)
require_once 'db.php';
include  'menu.php';

// 2. Vérifier si l'utilisateur est un administrateur connecté
$is_admin = isset($_SESSION['admin']);

// 3. Récupérer tous les projets depuis la base de données
// "ORDER BY date_projet DESC" permet d'afficher les projets du plus récent au plus ancien
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

    <!-- 4. Affichage conditionnel : Le bouton "Ajouter" n'est visible que pour l'admin -->
    <?php if ($is_admin): ?>
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.5rem">
        <a href="ajouter_projet.php" class="btn btn-primary">➕ Ajouter un projet</a>
    </div>
    <?php endif; ?>

    <!-- 5. Vérifier s'il y a des résultats dans la base de données -->
    <?php if (mysqli_num_rows($projets) === 0): ?>
        <!-- Si le nombre de lignes retournées est 0, on affiche un message d'état vide -->
        <div class="empty-state">
            <div class="empty-icon">🚀</div>
            <p>Aucun projet pour le moment.</p>
            <?php if ($is_admin): ?><a href="ajouter_projet.php" class="btn btn-primary" style="margin-top:1rem">➕ Ajouter votre premier projet</a><?php endif; ?>
        </div>
    <?php else: ?>
    <!-- 6. S'il y a des projets, on crée grid pour les afficher -->
    <div class="cards-grid">
        <!-- 
          mysqli_fetch_assoc() extrait la ligne suivante des résultats sous forme de tableau associatif.
          La boucle while continue tant qu'il reste des projets à extraire.
        -->
        <?php while ($p = mysqli_fetch_assoc($projets)): ?>
        <div class="card animate-in">
            <!-- Affichage de l'image (si elle a été uploadée) ou d'un placeholder par défaut -->
            <?php if ($p['image']): ?>
                <img class="card-img" src="fichiers/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['titre']) ?>">
            <?php else: ?>
                <div class="card-img-placeholder">🚀</div>
            <?php endif; ?>

            <div class="card-body">
                <!-- Affichage des balises de technologies (htmlspecialchars protège contre les failles XSS) -->
                <?php if ($p['technologie']): ?>
                    <span class="card-tag"><?= htmlspecialchars($p['technologie']) ?></span>
                <?php endif; ?>
                
                <div class="card-title"><?= htmlspecialchars($p['titre']) ?></div>
                <div class="card-desc"><?= htmlspecialchars($p['description']) ?></div>
                
                <!-- Formatage de la date (conversion du format SQL YYYY-MM-DD en DD/MM/YYYY) -->
                <?php if ($p['date_projet']): ?>
                <div class="card-meta">
                    📅 <?= date('d/m/Y', strtotime($p['date_projet'])) ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- 7. Boutons d'action (Modifier / Supprimer) visibles uniquement pour l'administrateur -->
            <?php if ($is_admin): ?>
            <div class="card-actions">
                <!-- On passe l'identifiant (id) du projet dans l'URL (méthode GET) pour savoir quel projet modifier/supprimer -->
                <a href="modifier_projet.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm">✏️ Modifier</a>
                
                <!-- L'attribut onclick demande une confirmation JavaScript avant de supprimer -->
                <a href="supprimer_projet.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Supprimer ce projet ?')">🗑️ Supprimer</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>
<!--  date('Y') génère automatiquement current year pour que le copyright reste toujours à jour -->
<footer><p>© <?= date('Y') ?> Portfolio – Tous droits réservés</p></footer>
</body>
</html>