<?php
// 1. On démarre/récupère la session courante pour pouvoir agir dessus
session_start();

// 2. On détruit toutes les données de la session (l'utilisateur est déconnecté)
session_destroy();

// 3. On redirige l'utilisateur vers la page de connexion
header("Location: login.php");

// 4. On arrête l'exécution du script par sécurité après une redirection
exit;
?>