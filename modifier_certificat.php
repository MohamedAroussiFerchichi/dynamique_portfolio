<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }
require_once 'db.php';
include  'menu.php';

$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success = '';
$error   = '';

$cert = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM certificats WHERE id=$id"));
if (!$cert) {
    echo "<div class='container'><div class='alert alert-danger'>Certificat introuvable.</div></div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim(mysqli_real_escape_string($conn, $_POST['titre']));
    $date  = mysqli_real_escape_string($conn, $_POST['date_obtention']);
    $fichier_name = $cert['fichier']; // Keep existing file by default

    if (!empty($_FILES['fichier']['name'])) {
        $allowed = ['pdf','jpg','jpeg','png','gif','webp'];
        $ext     = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = "Format non autorisé.";
        } else {
            $new_file = 'cert_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], "fichiers/$new_file")) {
                $fichier_name = $new_file;
            } else {
                $error = "Erreur lors de l'upload du fichier.";
            }
        }
    }

    if (!$error) {
        $sql = "UPDATE certificats SET titre='$titre', fichier='$fichier_name', date_obtention='$date' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $success = "Certificat mis à jour avec succès !";
            $cert    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM certificats WHERE id=$id"));
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
    <title>Modifier le Certificat – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>✏️ Modifier le Certificat</h1>
    </div>

    <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?> <a href="certificats.php">← Retour aux certificats</a></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="form-card">
        <h2>Modifier le certificat</h2>
        <p class="subtitle">Mettez à jour les informations du certificat</p>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre du certificat *</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($cert['titre']) ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Date d'obtention</label>
                    <input type="date" name="date_obtention" value="<?= htmlspecialchars($cert['date_obtention']) ?>">
                </div>
                <div class="form-group">
                    <label>Nouveau fichier (laisser vide pour garder l'actuel)</label>
                    <input type="file" name="fichier" accept=".pdf,.jpg,.jpeg,.png,.gif,.webp">
                </div>
            </div>
            <?php if ($cert['fichier']): ?>
            <div class="alert alert-info" style="font-size:0.85rem">
                📎 Fichier actuel : <strong><?= htmlspecialchars($cert['fichier']) ?></strong>
                <a href="fichiers/<?= htmlspecialchars($cert['fichier']) ?>" target="_blank" style="margin-left:0.5rem">Voir</a>
            </div>
            <?php endif; ?>
            <div style="display:flex;gap:1rem">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="certificats.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Panneau Admin</p></footer>
</body>
</html>
