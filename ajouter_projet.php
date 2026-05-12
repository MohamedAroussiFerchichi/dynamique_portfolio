<?php
// 1. Initialisation de la session
session_start();

// 2. Sécurité : Vérifier si l'utilisateur est bien connecté en tant qu'admin.
// S'il ne l'est pas, on le redirige immédiatement vers la page de connexion.
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// 3. Inclusion de la base de données et de la barre de navigation
require_once 'db.php';
include  'menu.php';

$success = '';
$error   = '';

// 4. Traitement du formulaire s'il a été soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Nettoyage et sécurisation des données texte contre les injections SQL
    $titre       = trim(mysqli_real_escape_string($conn, $_POST['titre']));
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $technologie = trim(mysqli_real_escape_string($conn, $_POST['technologie']));
    $date_projet = mysqli_real_escape_string($conn, $_POST['date_projet']);
    
    $image_name  = '';

    // 5. Gestion de l'upload de l'image
    // On vérifie si l'utilisateur a bien sélectionné un fichier
    if (!empty($_FILES['image']['name'])) {
        
        // Liste restreinte des extensions de fichiers autorisées pour des raisons de sécurité
        $allowed = ['jpg','jpeg','png','gif','webp'];

        // Extraction et mise en minuscule de l'extension du fichier uploadé (ex: .JPG devient .jpg)
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        // Vérifier si l'extension extraite fait partie de notre liste autorisée
        if (!in_array($ext, $allowed)) {
            $error = "Format d'image non autorisé.";
        } else {
            // Création d'un nom de fichier unique basé sur le timestamp actuel (ex: proj_16234567.png)
            // Cela évite d'écraser une image existante si deux fichiers ont le même nom.
            $image_name = 'proj_' . time() . '.' . $ext;
            
            // move_uploaded_file() déplace le fichier du dossier temporaire du serveur vers notre dossier "fichiers"
            // Le '!' vérifie si le déplacement a échoué.
            if (!move_uploaded_file($_FILES['image']['tmp_name'], "fichiers/$image_name")) {
                $error = "Erreur upload image.";
                $image_name = ''; // On réinitialise le nom pour ne pas enregistrer un lien cassé en BDD
            }
        }
    }

    // 6. Insertion dans la base de données
    // Si aucune erreur n'est survenue lors de l'upload ET qu'un titre a été fourni
    if (!$error && $titre) {
        // Préparation de la requête SQL d'insertion
        $sql = "INSERT INTO projets (titre, description, image, technologie, date_projet)
                VALUES ('$titre','$description','$image_name','$technologie','$date_projet')";
                
        // Exécution de la requête
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
        <h1>Ajouter un Projet</h1>
    </div>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="projets.php">← Voir les projets</a></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="form-card">
        <!-- 
        IMPORTANT : enctype="multipart/form-data" est OBLIGATOIRE 
        pour que le navigateur puisse envoyer des fichiers (images) au serveur PHP. 
        -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre du projet *</label>
                <!-- required rend le champ obligatoire côté navigateur -->
                <input type="text" name="titre" placeholder="ex: Portfolio Dynamique" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Décrivez votre projet..."></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Technologies utilisées *</label>
                    <input type="text" name="technologie" placeholder="ex: PHP, MySQL, Bootstrap" required>
                </div>
                <div class="form-group">
                    <label>Date du projet *</label>
                    <input type="date" name="date_projet" required>
                </div>
            </div>
            <div class="form-group">
                <label>Image du projet (JPG, PNG, WEBP) *</label>
                <!-- accept limite les types de fichiers sélectionnables par l'utilisateur dans l'explorateur -->
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" required>
            </div>
            <div style="display:flex;gap:1rem">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <!-- Le bouton annuler redirige simplement vers la liste des projets -->
                <a href="projets.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<footer><p>© <?= date('Y') ?> Portfolio – Panneau Admin</p></footer>
</body>
</html>