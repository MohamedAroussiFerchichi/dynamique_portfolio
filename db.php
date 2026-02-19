<?php
// Paramètres de connexion à la base de données
$host     = "localhost";
$user     = "root";
$password = "";      // Mot de passe WAMP par défaut (vide)
$database = "portfolio";

// Connexion
$conn = mysqli_connect($host, $user, $password, $database);

// Vérification
if (!$conn) {
    die("<div style='font-family:sans-serif;padding:20px;background:#1a1a2e;color:#e94560;'>
        <h2>❌ Erreur de connexion à la base de données</h2>
        <p>" . mysqli_connect_error() . "</p>
        <p>Vérifiez que WAMP est démarré et que la base <strong>portfolio</strong> existe.</p>
    </div>");
}

mysqli_set_charset($conn, "utf8mb4");
?>
