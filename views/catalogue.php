<?php
    $produits = $produitObj->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue - Rich Ricasso</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <div class="catalogue">
        <h2>Catalogue des Produits</h2>
        <div class="products-grid">
            <?php foreach ($produits as $produit):
                echo "Nom du produit : " . $produit['nom'] . "<br>";
                echo "Prix : " . $produit['prix'] . "<br>";
                echo "Description : " . $produit['description'] . "<br>";
                echo "Couleur : " . $produit['couleur_nom'] . "<br>";
                echo "Stock : " . $produit['stock'] . "<br>";
                echo "-----------------------<br>";
            endforeach; ?>
        </div>
    </div>
</body>
</html>