<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'db.php';
include  'menu.php';

$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success = '';
$error   = '';

$projet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM projets WHERE id=$id"));
if (!$projet) { echo "<div class='container'><div class='alert alert-danger'>Projet introuvable.</div></div>"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim(mysqli_real_escape_string($conn, $_POST['titre']));
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $technologie = trim(mysqli_real_escape_string($conn, $_POST['technologie']));
    $date_projet = mysqli_real_escape_string($conn, $_POST['date_projet']);
    $image_name  = $projet['image']; // Keep old image by default

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = "Format d'image non autorisé.";
        } else {
            $new_img = 'proj_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], "fichiers/$new_img")) {
                $image_name = $new_img;
            } else {
                $error = "Erreur lors de l'upload de l'image.";
            }
        }
    }

    if (!$error) {
        $sql = "UPDATE projets SET titre='$titre', description='$description', image='$image_name',
                technologie='$technologie', date_projet='$date_projet' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $success = "Projet mis à jour avec succès !";
            $projet  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM projets WHERE id=$id"));
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Projet – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Modifier le Projet</h1>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="projets.php">← Retour aux projets</a></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre du projet *</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($projet['description']) ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Technologies utilisées *</label>
                    <input type="text" name="technologie" value="<?= htmlspecialchars($projet['technologie']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Date du projet *</label>
                    <input type="date" name="date_projet" value="<?= htmlspecialchars($projet['date_projet']) ?>" required>
                </div>
            </div>
            <?php if ($projet['image']): ?>
            <div style="margin-bottom:1rem">
                <label style="font-size:0.8rem;color:var(--text-muted)">Image actuelle :</label><br>
                <img src="fichiers/<?= htmlspecialchars($projet['image']) ?>" alt="Image actuelle" style="max-height:120px;border-radius:8px;margin-top:0.5rem">
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Nouvelle image (laisser vide pour garder l'actuelle)</label>
                <!-- Retrait du required ici pour permettre la mise à jour sans changer l'image -->
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div style="display:flex;gap:1rem">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="projets.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Panneau Admin</p></footer>
</body>
</html>
