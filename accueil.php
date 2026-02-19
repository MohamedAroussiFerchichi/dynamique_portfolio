<?php
require_once 'db.php';
include  'menu.php';

// Fetch personal info
$info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM infos_personnelles LIMIT 1"));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil – Portfolio</title>
    <meta name="description" content="Portfolio personnel dynamique – Développeur Full Stack">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <section class="hero">

        <div class="hero-text animate-in">
            <div class="hero-badge">✨ Disponible pour des projets</div>
            <h1>
                Bonjour, je suis<br>
                <span class="gradient-text">
                    <?= $info ? htmlspecialchars($info['prenom'] . ' ' . $info['nom']) : 'Votre Nom' ?>
                </span>
            </h1>
            <p class="hero-desc">
                <?= $info && $info['description']
                    ? htmlspecialchars($info['description'])
                    : 'Développeur passionné par la création de solutions numériques innovantes.' ?>
            </p>
            <div class="hero-actions">
                <a href="projets.php" class="btn btn-primary">🚀 Voir mes projets</a>
                <a href="contact.php" class="btn btn-secondary">✉️ Me contacter</a>
            </div>

            <!-- Liens sociaux -->
            <div class="social-links">
                <?php if ($info && $info['linkedin']): ?>
                <a href="<?= htmlspecialchars($info['linkedin']) ?>" target="_blank" class="social-btn">
                    <span class="icon">💼</span> LinkedIn
                </a>
                <?php endif; ?>
                <?php if ($info && $info['github']): ?>
                <a href="<?= htmlspecialchars($info['github']) ?>" target="_blank" class="social-btn">
                    <span class="icon">🐙</span> GitHub
                </a>
                <?php endif; ?>
                <?php if ($info && $info['site_perso']): ?>
                <a href="<?= htmlspecialchars($info['site_perso']) ?>" target="_blank" class="social-btn">
                    <span class="icon">🌐</span> Site Web
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-image animate-in">
            <?php if ($info && $info['photo']): ?>
                <img src="fichiers/<?= htmlspecialchars($info['photo']) ?>" alt="Photo de profil">
            <?php else: ?>
                <div style="width:300px;height:300px;background:var(--accent-grad);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:6rem;box-shadow:var(--shadow)">
                    👤
                </div>
            <?php endif; ?>
        </div>

    </section>

    <!-- Stats rapides -->
    <?php
    $nb_projets = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM projets"));
    $nb_certs   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM certificats"));
    ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.5rem;margin:2rem 0 3rem">
        <div class="info-block" style="text-align:center">
            <div style="font-size:2.5rem;font-weight:800;background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"><?= $nb_projets ?></div>
            <div style="color:var(--text-secondary);font-size:0.9rem;margin-top:0.5rem">Projets réalisés</div>
        </div>
        <div class="info-block" style="text-align:center">
            <div style="font-size:2.5rem;font-weight:800;background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"><?= $nb_certs ?></div>
            <div style="color:var(--text-secondary);font-size:0.9rem;margin-top:0.5rem">Certificats obtenus</div>
        </div>
        <div class="info-block" style="text-align:center">
            <div style="font-size:2.5rem;font-weight:800;background:var(--accent-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">∞</div>
            <div style="color:var(--text-secondary);font-size:0.9rem;margin-top:0.5rem">Passion pour le code</div>
        </div>
    </div>

</div>

<footer>
    <p>© <?= date('Y') ?> <?= $info ? htmlspecialchars($info['prenom'].' '.$info['nom']) : 'Portfolio' ?> – Tous droits réservés</p>
</footer>

</body>
</html>
