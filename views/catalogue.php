<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="catalogue-container">
    <div class="page-header">
        <h1>🛍️ Notre Catalogue</h1>
        <p>Découvrez notre sélection de produits de qualité</p>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <div class="filters-header">
            <h2>🔍 Filtrer les produits</h2>
        </div>
        
        <form method="GET" action="" class="filter-form">
            <input type="hidden" name="page" value="catalogue">
            
            <div class="filter-group">
                <label for="search">Recherche</label>
                <input type="text" id="search" name="search" placeholder="Nom du produit..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="">Tous les types</option>
                    <?php foreach ($types as $type_option): ?>
                        <option value="<?= htmlspecialchars($type_option) ?>" 
                                <?= ($type === $type_option) ? 'selected' : '' ?>>
                            <?= ucfirst(htmlspecialchars($type_option)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="couleur">Couleur</label>
                <select id="couleur" name="couleur">
                    <option value="">Toutes les couleurs</option>
                    <?php foreach ($couleurs as $couleur): ?>
                        <option value="<?= $couleur['id'] ?>" 
                                <?= ($couleur_id == $couleur['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($couleur['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="prix_min">Prix minimum (CAD)</label>
                <input type="number" id="prix_min" name="prix_min" step="0.01" 
                       placeholder="<?= $prix_range['prix_min'] ?>" 
                       value="<?= htmlspecialchars($_GET['prix_min'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label for="prix_max">Prix maximum (CAD)</label>
                <input type="number" id="prix_max" name="prix_max" step="0.01" 
                       placeholder="<?= $prix_range['prix_max'] ?>" 
                       value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>">
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                <a href="?page=catalogue" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>

    <!-- Résultats -->
    <div class="results-header">
        <div class="results-count">
            <strong><?= count($produits) ?></strong> produit<?= count($produits) > 1 ? 's' : '' ?> trouvé<?= count($produits) > 1 ? 's' : '' ?>
        </div>
    </div>

    <!-- Grille de produits -->
    <?php if (count($produits) > 0): ?>
        <div class="products-grid">
            <?php foreach ($produits as $produit): ?>
                <div class="product-card" onclick="window.location.href='?page=produit&id=<?= $produit['id'] ?>'">
                    <div class="product-image-container">
                        <img src="/SiteEcom_RichRicasso/assets/images/<?= htmlspecialchars($produit['image_principale']) ?>" 
                             alt="<?= htmlspecialchars($produit['nom']) ?>" 
                             class="product-image"
                             onerror="this.src='/SiteEcom_RichRicasso/assets/images/placeholder.jpg'">
                        
                        <div class="product-badges">
                            <?php if ($produit['en_vedette']): ?>
                                <span class="badge badge-featured">⭐ Vedette</span>
                            <?php endif; ?>
                            
                            <?php if ($produit['stock'] > 0): ?>
                                <span class="badge badge-stock">✓ En stock (<?= $produit['stock'] ?>)</span>
                            <?php else: ?>
                                <span class="badge badge-out">✗ Rupture</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="product-info">
                        <span class="product-type"><?= htmlspecialchars($produit['type']) ?></span>
                        
                        <h3 class="product-name"><?= htmlspecialchars($produit['nom']) ?></h3>
                        
                        <p class="product-description"><?= htmlspecialchars($produit['description']) ?></p>
                        
                        <?php if ($produit['couleur_nom']): ?>
                            <div class="product-color">
                                <div class="color-swatch" style="background-color: <?= htmlspecialchars($produit['code_hex']) ?>"></div>
                                <span class="color-name"><?= htmlspecialchars($produit['couleur_nom']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-footer">
                            <span class="product-price"><?= number_format($produit['prix'], 2) ?> $</span>
                            <button class="btn-view">Voir détails →</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div style="font-size: 4em; margin-bottom: 20px;">🔍</div>
            <h2>Aucun produit trouvé</h2>
            <p>Essayez de modifier vos critères de recherche</p>
            <a href="?page=catalogue" class="btn btn-primary" style="margin-top: 20px; display: inline-block;">Voir tous les produits</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>