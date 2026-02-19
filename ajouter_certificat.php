<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }
require_once 'db.php';
include  'menu.php';

$success = '';
$error   = '';

// Exemple code from cahier de charges – enrichi avec sécurité
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim(mysqli_real_escape_string($conn, $_POST['titre']));
    $date  = mysqli_real_escape_string($conn, $_POST['date_obtention']);

    if (empty($titre)) {
        $error = "Le titre est obligatoire.";
    } elseif (empty($_FILES['fichier']['name'])) {
        $error = "Veuillez sélectionner un fichier (PDF ou image).";
    } else {
        $allowed   = ['pdf','jpg','jpeg','png','gif','webp'];
        $fichier   = $_FILES['fichier']['name'];
        $tmp_name  = $_FILES['fichier']['tmp_name'];
        $ext       = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Format non autorisé. Utilisez PDF, JPG, PNG ou WEBP.";
        } else {
            $safe_name  = 'cert_' . time() . '.' . $ext;
            $destination = "fichiers/" . $safe_name;

            if (move_uploaded_file($tmp_name, $destination)) {
                $sql = "INSERT INTO certificats (titre, fichier, date_obtention)
                        VALUES ('$titre', '$safe_name', '$date')";
                if (mysqli_query($conn, $sql)) {
                    $success = "Certificat ajouté avec succès !";
                } else {
                    $error = "Erreur base de données : " . mysqli_error($conn);
                }
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
    <title>Ajouter un Certificat – Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>🏆 Ajouter un Certificat</h1>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?>
            <a href="certificats.php" style="margin-left:1rem">← Voir les certificats</a>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <h2>Nouveau Certificat</h2>
        <p class="subtitle">Ajoutez un certificat ou une formation à votre portfolio</p>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre du certificat *</label>
                <input type="text" name="titre" placeholder="ex: AWS Certified Developer" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Date d'obtention</label>
                    <input type="date" name="date_obtention">
                </div>
                <div class="form-group">
                    <label>Fichier (PDF ou Image) *</label>
                    <input type="file" name="fichier" accept=".pdf,.jpg,.jpeg,.png,.gif,.webp" required>
                </div>
            </div>
            <div style="display:flex;gap:1rem;margin-top:0.5rem">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="certificats.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Panneau Admin</p></footer>
</body>
</html>
