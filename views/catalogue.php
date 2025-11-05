<?php
$page_title = 'Catalogue - Rich Ricasso';
require_once __DIR__ . '/includes/header.php';

/* Valeurs sûres par défaut */
$produits   = (isset($produits) && is_array($produits)) ? $produits : [];
$couleurs   = (isset($couleurs) && is_array($couleurs)) ? $couleurs : [];
$prix_range = (isset($prix_range) && is_array($prix_range) && isset($prix_range['prix_min'],$prix_range['prix_max']))
            ? $prix_range : ['prix_min'=>0,'prix_max'=>500];
$types      = (isset($types) && is_array($types)) ? $types : [];

$sel_type     = $_GET['type']     ?? '';
$sel_couleur  = $_GET['couleur']  ?? '';
$sel_prix_min = $_GET['prix_min'] ?? '';
$sel_prix_max = $_GET['prix_max'] ?? '';

$count = is_countable($produits) ? count($produits) : 0;
?>

<section class="catalogue-section">
  <div class="container">
    <div class="catalogue-header">
      <h1>Notre Collection</h1>
      <p>Découvrez nos cravates et chemises en soie de luxe</p>
    </div>

    <!-- Filtres -->
    <div class="filters">
      <form method="GET" action="index.php" class="filters-form">
        <input type="hidden" name="page" value="catalogue">

        <!-- Type -->
        <div class="filter-group">
          <label for="f-type">Type</label>
          <select id="f-type" name="type">
            <option value="">Tous les produits</option>
            <?php if (!empty($types)) : ?>
              <?php foreach ($types as $t): ?>
                <?php $t_clean = htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); ?>
                <option value="<?= $t_clean ?>" <?= ($sel_type === $t ? 'selected' : '') ?>>
                  <?= ucfirst($t_clean) ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="cravate" <?= ($sel_type==='cravate' ? 'selected' : '') ?>>Cravates</option>
              <option value="chemise"  <?= ($sel_type==='chemise'  ? 'selected' : '') ?>>Chemises</option>
            <?php endif; ?>
          </select>
        </div>

        <!-- Couleur -->
        <div class="filter-group">
          <label for="f-color">Couleur</label>
          <select id="f-color" name="couleur">
            <option value="">Toutes les couleurs</option>
            <?php foreach ($couleurs as $couleur): ?>
              <?php
                $cId  = htmlspecialchars((string)($couleur['id'] ?? ''),  ENT_QUOTES, 'UTF-8');
                $cNom = htmlspecialchars((string)($couleur['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
              ?>
              <option value="<?= $cId ?>" <?= ($sel_couleur!=='' && $sel_couleur == ($couleur['id'] ?? null) ? 'selected' : '') ?>>
                <?= $cNom ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Prix -->
        <div class="filter-group">
          <label for="f-min">Prix minimum</label>
          <input id="f-min" type="number" name="prix_min" min="0" step="0.01"
                 placeholder="<?= (float)$prix_range['prix_min'] ?>"
                 value="<?= htmlspecialchars((string)$sel_prix_min, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="filter-group">
          <label for="f-max">Prix maximum</label>
          <input id="f-max" type="number" name="prix_max" min="0" step="0.01"
                 placeholder="<?= (float)$prix_range['prix_max'] ?>"
                 value="<?= htmlspecialchars((string)$sel_prix_max, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <button type="submit" class="btn-primary">Filtrer</button>
        <a href="index.php?page=catalogue" class="btn-reset">Réinitialiser</a>
      </form>
    </div>

    <!-- Résultats -->
    <div class="catalogue-results">
      <p class="results-count">
        <?= $count ?> produit<?= $count>1 ? 's' : '' ?> trouvé<?= $count>1 ? 's' : '' ?>
      </p>

      <?php if ($count > 0): ?>
        <div class="products-grid">
          <?php foreach ($produits as $p): ?>
            <?php
              $id   = htmlspecialchars((string)($p['id'] ?? ''), ENT_QUOTES, 'UTF-8');
              $nom  = htmlspecialchars((string)($p['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
              $type = htmlspecialchars((string)($p['type'] ?? ''), ENT_QUOTES, 'UTF-8');
              $prix = isset($p['prix']) ? (float)$p['prix'] : 0.0;

              $hex  = htmlspecialchars((string)($p['couleur_hex'] ?? '#999999'), ENT_QUOTES, 'UTF-8');
              $cNom = htmlspecialchars((string)($p['couleur_nom'] ?? '—'), ENT_QUOTES, 'UTF-8');

              $img  = isset($p['image_principale']) && $p['image_principale'] !== ''
                      ? 'assets/images/' . rawurlencode($p['image_principale'])
                      : '';
            ?>
            <div class="product-card">
              <a href="index.php?page=produit&id=<?= $id ?>">
                <div class="product-image" style="background: linear-gradient(135deg, <?= $hex ?>44, <?= $hex ?>88);">
                  <?php if ($img): ?>
                    <img src="<?= $img ?>" alt="<?= $nom ?>">
                  <?php else: ?>
                    <div class="product-placeholder">
                      <span style="color: <?= $hex ?>; font-size: 3rem; font-weight: bold;">
                        <?= strtoupper(htmlspecialchars(mb_substr($nom, 0, 2), ENT_QUOTES, 'UTF-8')) ?>
                      </span>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="product-info">
                  <span class="product-type" style="color: <?= $hex ?>;"><?= ucfirst($type) ?></span>
                  <h3><?= $nom ?></h3>
                  <p class="product-price"><?= number_format($prix, 2) ?> $</p>
                  <p class="product-color">
                    <span class="color-dot" style="background: <?= $hex ?>;"></span>
                    <?= $cNom ?>
                  </p>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-results">
          <p>Aucun produit ne correspond à vos critères de recherche.</p>
          <a href="index.php?page=catalogue" class="btn-primary">Voir tous les produits</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
