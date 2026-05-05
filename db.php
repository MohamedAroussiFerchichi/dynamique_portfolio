<?php
// ==========================================
// 1. Paramètres de connexion au serveur MySQL
// ==========================================
$host     = "localhost"; // Le serveur de base de données (ici, la machine locale)
$user     = "root";      // Le nom d'utilisateur par défaut de WAMP/XAMPP
$password = "";          // Mot de passe par défaut de WAMP (laissé vide)
$database = "portfolio"; // Le nom de la base de données créée dans portfolio.sql

// ==========================================
// 2. Établissement de la connexion
// ==========================================
// mysqli_connect() tente d'ouvrir une connexion avec les paramètres ci-dessus.
// Le résultat (un objet de connexion ou false) est stocké dans $conn.
$conn = mysqli_connect($host, $user, $password, $database);

// ==========================================
// 3. Vérification des erreurs
// ==========================================
// Si la connexion a échoué ($conn est faux)
if (!$conn) {
    // La fonction die() arrête complètement l'exécution du script PHP
    // et affiche le message d'erreur HTML ci-dessous.
    // mysqli_connect_error() affiche la raison exacte de l'échec.
    die("<div style='font-family:sans-serif;padding:20px;background:#1a1a2e;color:#e94560;'>
        <h2> Erreur de connexion à la base de données</h2>
        <p>" . mysqli_connect_error() . "</p>
        <p>Vérifiez que WAMP est démarré et que la base <strong>portfolio</strong> existe.</p>
    </div>");
}

// ==========================================
// 4. Configuration de l'encodage
// ==========================================
// Définit l'encodage des échanges PHP <-> MySQL en utf8mb4.
// Cela garantit que les accents (é, à) et les emojis sont correctement traités.
mysqli_set_charset($conn, "utf8mb4");
?>