<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }
require_once 'db.php';
include  'menu.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim(mysqli_real_escape_string($conn, $_POST['titre']));
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $technologie = trim(mysqli_real_escape_string($conn, $_POST['technologie']));
    $date_projet = mysqli_real_escape_string($conn, $_POST['date_projet']);
    $image_name  = '';

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = "Format d'image non autorisé.";
        } else {
            $image_name = 'proj_' . time() . '.' . $ext;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], "fichiers/$image_name")) {
                $error = "Erreur upload image.";
                $image_name = '';
            }
        }
    }

    if (!$error && $titre) {
        $sql = "INSERT INTO projets (titre, description, image, technologie, date_projet)
                VALUES ('$titre','$description','$image_name','$technologie','$date_projet')";
        if (mysqli_query($conn, $sql)) {
            $success = "Projet ajouté avec succès !";
        } else {
            $error = "Erreur lors de l'insertion : " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>➕ Ajouter un Projet</h1>
    </div>

    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?> <a href="projets.php">← Voir les projets</a></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre du projet *</label>
                <input type="text" name="titre" placeholder="ex: Portfolio Dynamique" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Décrivez votre projet..."></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Technologies utilisées</label>
                    <input type="text" name="technologie" placeholder="ex: PHP, MySQL, Bootstrap">
                </div>
                <div class="form-group">
                    <label>Date du projet</label>
                    <input type="date" name="date_projet">
                </div>
            </div>
            <div class="form-group">
                <label>Image du projet (JPG, PNG, WEBP)</label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div style="display:flex;gap:1rem">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="projets.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Panneau Admin</p></footer>
</body>
</html>
