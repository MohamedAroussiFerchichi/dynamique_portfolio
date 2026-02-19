<?php
require_once 'db.php';
include  'menu.php';

$info    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM infos_personnelles LIMIT 1"));
$is_admin = isset($_SESSION['admin']);
$success = '';
$error   = '';

// Gestion de l'upload CV (admin uniquement)
if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_cv'])) {
    if (!empty($_FILES['cv']['name'])) {
        $allowed  = ['pdf','doc','docx'];
        $ext      = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = "Format non autorisé. Utilisez PDF, DOC ou DOCX.";
        } else {
            $filename = 'cv_' . time() . '.' . $ext;
            $dest     = "fichiers/" . $filename;
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $dest)) {
                if ($info) {
                    mysqli_query($conn, "UPDATE infos_personnelles SET cv='$filename' WHERE id=" . (int)$info['id']);
                } else {
                    mysqli_query($conn, "INSERT INTO infos_personnelles (cv) VALUES ('$filename')");
                }
                $success = "CV mis à jour avec succès !";
                $info    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM infos_personnelles LIMIT 1"));
            } else {
                $error = "Erreur lors de l'upload. Vérifiez les permissions du dossier /fichiers/.";
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
    <title>À propos – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header animate-in">
        <h1>À propos de moi</h1>
        <p>Découvrez mon parcours, mes compétences et mes expériences</p>
    </div>

    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 2fr;gap:2.5rem;align-items:start">

        <!-- Colonne gauche -->
        <div>
            <div class="info-block" style="text-align:center;margin-bottom:1.5rem">
                <?php if ($info && $info['photo']): ?>
                    <img src="fichiers/<?= htmlspecialchars($info['photo']) ?>" alt="Photo" style="width:160px;height:160px;object-fit:cover;border-radius:50%;border:3px solid var(--accent);margin-bottom:1rem">
                <?php else: ?>
                    <div style="width:160px;height:160px;background:var(--accent-grad);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:4rem;margin:0 auto 1rem">👤</div>
                <?php endif; ?>
                <h2 style="font-size:1.2rem;font-weight:700"><?= $info ? htmlspecialchars($info['prenom'].' '.$info['nom']) : 'Votre Nom' ?></h2>
                <p style="color:var(--text-muted);font-size:0.85rem;margin-top:0.25rem">Développeur Full Stack</p>
            </div>

            <!-- CV Download -->
            <?php if ($info && $info['cv']): ?>
            <div class="info-block" style="text-align:center">
                <p style="color:var(--text-secondary);font-size:0.85rem;margin-bottom:1rem">📄 Mon CV est disponible en téléchargement</p>
                <a href="fichiers/<?= htmlspecialchars($info['cv']) ?>" download class="btn btn-primary" style="width:100%;justify-content:center">
                    ⬇️ Télécharger mon CV
                </a>
            </div>
            <?php endif; ?>

            <!-- Social -->
            <div class="info-block" style="margin-top:1.5rem">
                <h3>🔗 Liens</h3>
                <?php if ($info && $info['linkedin']): ?><a href="<?= htmlspecialchars($info['linkedin']) ?>" target="_blank" class="social-btn" style="width:100%;justify-content:center;margin-bottom:0.5rem">💼 LinkedIn</a><?php endif; ?>
                <?php if ($info && $info['github']): ?><a href="<?= htmlspecialchars($info['github']) ?>" target="_blank" class="social-btn" style="width:100%;justify-content:center;margin-bottom:0.5rem">🐙 GitHub</a><?php endif; ?>
                <?php if ($info && $info['site_perso']): ?><a href="<?= htmlspecialchars($info['site_perso']) ?>" target="_blank" class="social-btn" style="width:100%;justify-content:center">🌐 Site Web</a><?php endif; ?>
            </div>
        </div>

        <!-- Colonne droite -->
        <div>
            <!-- Description -->
            <div class="info-block animate-in">
                <h3>✨ À propos</h3>
                <p style="color:var(--text-secondary);line-height:1.8">
                    <?= $info && $info['description'] ? nl2br(htmlspecialchars($info['description'])) : 'Description à compléter depuis la base de données.' ?>
                </p>
            </div>

            <!-- Compétences -->
            <div class="info-block animate-in">
                <h3>🛠️ Compétences techniques</h3>
                <div class="skills-grid">
                    <?php
                    $skills = ['PHP','MySQL','HTML5','CSS3','JavaScript','Bootstrap','Laravel','Git','Linux','REST API','WAMP','React'];
                    foreach ($skills as $s): ?>
                        <div class="skill-chip"><?= $s ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Formation & Expérience -->
            <div class="info-block animate-in">
                <h3>🎓 Formation & Expérience</h3>
                <div style="position:relative;padding-left:1.5rem;border-left:2px solid var(--border)">
                    <div style="margin-bottom:1.5rem">
                        <div style="color:var(--text-muted);font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em">2023 – Présent</div>
                        <div style="font-weight:600;margin:0.25rem 0">Développeur Full Stack</div>
                        <div style="color:var(--text-secondary);font-size:0.88rem">Projets personnels & freelance</div>
                    </div>
                    <div>
                        <div style="color:var(--text-muted);font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em">2019 – 2023</div>
                        <div style="font-weight:600;margin:0.25rem 0">Licence Informatique</div>
                        <div style="color:var(--text-secondary);font-size:0.88rem">Université – Spécialité Génie Logiciel</div>
                    </div>
                </div>
            </div>

            <!-- Admin: Upload CV -->
            <?php if ($is_admin): ?>
            <div class="info-block" style="border-color:rgba(108,99,255,0.4)">
                <h3>⚙️ Admin – Mettre à jour le CV</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Fichier CV (PDF, DOC, DOCX)</label>
                        <input type="file" name="cv" accept=".pdf,.doc,.docx" required>
                    </div>
                    <button type="submit" name="upload_cv" class="btn btn-primary">⬆️ Uploader le CV</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Tous droits réservés</p></footer>
</body>
</html>
