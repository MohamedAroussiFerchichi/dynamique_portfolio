<?php
// 1. Démarrer ou reprendre la session pour avoir accès aux variables globales comme $_SESSION
session_start();

// 2. Extraire le nom du fichier actuel de l'URL (par exemple : "projets.php")
// Cela nous permet de savoir sur quelle page l'utilisateur se trouve actuellement.
$current = basename($_SERVER['PHP_SELF']);

// 3. Vérifier si la variable de session 'admin' existe. 
// Si oui, l'utilisateur est connecté et $is_admin vaudra true.
$is_admin = isset($_SESSION['admin']);
?>
<nav>
    <div class="nav-inner">
        <a href="accueil.php" class="nav-brand">⚡ Portfolio</a>
        
        <ul class="nav-links">
            <!-- 
            L'opérateur ternaire en PHP ( condition ? vrai : faux ) est utilisé ici.
            On vérifie si la page actuelle ($current) correspond au lien. 
            Si c'est le cas, on ajoute la classe CSS 'active' pour mettre le bouton en surbrillance.
            -->
            <li><a href="accueil.php"   class="<?= $current=='accueil.php'    ? 'active':'' ?>">🏠 Accueil</a></li>
            <li><a href="apropos.php"   class="<?= $current=='apropos.php'    ? 'active':'' ?>">👤 À propos</a></li>
            <li><a href="projets.php"   class="<?= $current=='projets.php'    ? 'active':'' ?>">🚀 Projets</a></li>
            <li><a href="certificats.php" class="<?= $current=='certificats.php' ? 'active':'' ?>">🏆 Certificats</a></li>
            <li><a href="contact.php"   class="<?= $current=='contact.php'    ? 'active':'' ?>">✉️ Contact</a></li>
        </ul>

        <div class="nav-actions">
            <?php if ($is_admin): ?>
                <!-- 
                Si un admin est identifié, on affiche un message de bienvenue.
                IMPORTANT - SÉCURITÉ : htmlspecialchars() est crucial ici. 
                Il convertit les caractères spéciaux (comme < ou >) en texte inoffensif.
                S'il n'était pas là, un hacker pourrait créer un nom d'utilisateur contenant 
                du code JavaScript malveillant (ex: <script>...</script>). C'est ce qu'on appelle 
                une attaque XSS (Cross-Site Scripting). htmlspecialchars() désamorce ce code.
                -->
                <span style="font-size:0.8rem;color:var(--text-muted)">👋 <?= htmlspecialchars($_SESSION['admin']) ?></span>
                <a href="logout.php" class="btn btn-secondary btn-sm">Déconnexion</a>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté, on affiche le bouton pour aller à la page de login de l'administration -->
                <a href="index.php" class="btn btn-primary btn-sm">🔐 Admin</a>
            <?php endif; ?>
        </div>
    </div>
</nav>