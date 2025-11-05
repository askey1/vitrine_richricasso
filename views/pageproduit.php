<?php
$page_title = htmlspecialchars($produit['nom']) . ' - Rich Ricasso';
require_once __DIR__ . '/includes/header.php';
?>

<section class="produit-detail">
    <div class="container">
        <div class="produit-grid">
            <!-- Images du produit -->
            <div class="produit-images">
                <div class="main-image" style="background: linear-gradient(135deg, <?php echo $produit['couleur_hex']; ?>44, <?php echo $produit['couleur_hex']; ?>88);">
                    <?php if ($produit['image_principale']): ?>
                        <img src="/vitrine_richricasso/assets/images/<?php echo $produit['image_principale']; ?>" 
                             alt="<?php echo htmlspecialchars($produit['nom']); ?>"
                             id="mainImage">
                    <?php else: ?>
                        <div class="product-placeholder">
                            <span style="color: <?php echo $produit['couleur_hex']; ?>; font-size: 5rem; font-weight: bold;">
                                <?php echo strtoupper(substr($produit['nom'], 0, 2)); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Images secondaires -->
                <?php if (!empty($produit['images_secondaires'])): ?>
                <div class="secondary-images">
                    <!-- Image principale comme miniature -->
                    <?php if ($produit['image_principale']): ?>
                    <div class="thumb active" onclick="changeImage('/vitrine_richricasso/assets/images/<?php echo $produit['image_principale']; ?>')">
                        <img src="/vitrine_richricasso/assets/images/<?php echo $produit['image_principale']; ?>" alt="Image 1">
                    </div>
                    <?php endif; ?>
                    
                    <!-- Images secondaires comme miniatures -->
                    <?php foreach ($produit['images_secondaires'] as $image): ?>
                    <div class="thumb" onclick="changeImage('/vitrine_richricasso/assets/images/<?php echo $image['chemin_image']; ?>')">
                        <img src="/vitrine_richricasso/assets/images/<?php echo $image['chemin_image']; ?>" alt="Image">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Informations du produit -->
            <div class="produit-info">
                <span class="produit-type" style="color: <?php echo $produit['couleur_hex']; ?>;">
                    <?php echo ucfirst($produit['type']); ?>
                </span>
                
                <h1><?php echo htmlspecialchars($produit['nom']); ?></h1>
                
                <div class="produit-price">
                    <?php echo number_format($produit['prix'], 2); ?> $
                </div>

                <div class="produit-color">
                    <span class="color-label">Couleur :</span>
                    <span class="color-dot" style="background: <?php echo $produit['couleur_hex']; ?>;"></span>
                    <span><?php echo $produit['couleur_nom']; ?></span>
                </div>

                <!-- Tailles disponibles -->
                <?php if (!empty($produit['tailles'])): ?>
                <div class="produit-tailles">
                    <label>Taille :</label>
                    <div class="tailles-list">
                        <?php foreach ($produit['tailles'] as $taille): ?>
                        <span class="taille-option"><?php echo $taille['taille']; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="produit-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($produit['description'])); ?></p>
                </div>

                <div class="produit-stock">
                    <?php if ($produit['stock'] > 0): ?>
                        <span class="in-stock">✓ En stock (<?php echo $produit['stock']; ?> disponible<?php echo $produit['stock'] > 1 ? 's' : ''; ?>)</span>
                    <?php else: ?>
                        <span class="out-of-stock">✗ Rupture de stock</span>
                    <?php endif; ?>
                </div>

                <div class="produit-actions">
                    <a href="/vitrine_richricasso/public/index.php?page=catalogue" class="btn-secondary">
                        ← Retour au catalogue
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Fonction pour changer l'image principale
function changeImage(imageSrc) {
    const mainImg = document.getElementById('mainImage');
    if (mainImg) {
        mainImg.style.opacity = '0';
        setTimeout(function() {
            mainImg.src = imageSrc;
            mainImg.style.opacity = '1';
        }, 300);
    }
    
    // Mettre à jour les miniatures actives
    document.querySelectorAll('.thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>