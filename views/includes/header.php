<header>
    <nav class="navbar">
        <ul class="nav-list">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="catalogue.php">Catalogue</a></li>

            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href="inscription.php" class="signup-btn">S’inscrire</a></li>
            <?php endif; ?>

            <?php
            $logoLink = isset($_SESSION['user']) ? 'profil.php' : 'connexion.php';
            ?>
            <li class="profile-icon">
                <a href="<?= $logoLink ?>">
                    <img src="https://cdn-icons-png.flaticon.com/512/2182/2182890.png"
                         alt="Logo de Rich Ricasso"
                         style="height:40px;">
                </a>
            </li>
        </ul>
    </nav>
</header>
