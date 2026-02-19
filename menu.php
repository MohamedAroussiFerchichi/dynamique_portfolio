<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
$is_admin = isset($_SESSION['admin']);
?>
<nav>
    <div class="nav-inner">
        <a href="accueil.php" class="nav-brand">⚡ Portfolio</a>
        <ul class="nav-links">
            <li><a href="accueil.php"   class="<?= $current=='accueil.php'    ? 'active':'' ?>">🏠 Accueil</a></li>
            <li><a href="apropos.php"   class="<?= $current=='apropos.php'    ? 'active':'' ?>">👤 À propos</a></li>
            <li><a href="projets.php"   class="<?= $current=='projets.php'    ? 'active':'' ?>">🚀 Projets</a></li>
            <li><a href="certificats.php" class="<?= $current=='certificats.php' ? 'active':'' ?>">🏆 Certificats</a></li>
            <li><a href="contact.php"   class="<?= $current=='contact.php'    ? 'active':'' ?>">✉️ Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if ($is_admin): ?>
                <span style="font-size:0.8rem;color:var(--text-muted)">👋 <?= htmlspecialchars($_SESSION['admin']) ?></span>
                <a href="logout.php" class="btn btn-secondary btn-sm">Déconnexion</a>
            <?php else: ?>
                <a href="index.php" class="btn btn-primary btn-sm">🔐 Admin</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
