<?php
require_once DIR . '/../models/Produit.php';

class ProduitController {
    private $produitModel;

    public function construct() {
        $this->produitModel = new Produit();
    }

    // Afficher le catalogue
    public function catalogue() {
        // Récupérer les filtres depuis l'URL
        $type = $_GET['type'] ?? null;
        $couleur_id = $_GET['couleur'] ?? null;
        $prix_min = $_GET['prix_min'] ?? null;
        $prix_max = $_GET['prix_max'] ?? null;

        // Filtrer les produits
        $produits = $this->produitModel->filter($type, $couleur_id, $prix_min, $prix_max);

        // Récupérer les couleurs pour les filtres
        $couleurs = $this->produitModel->getCouleurs();

        // Récupérer la plage de prix
        $prix_range = $this->produitModel->getPrixRange();

        // Charger la vue
        require_once DIR . '/../views/catalogue.php';
    }

    // Afficher un produit spécifique
    public function detail($id) {
        $produit = $this->produitModel->getById($id);

        if (!$produit) {
            // Produit non trouvé, rediriger vers le catalogue
            header('Location: /SiteEcom_RichRicasso/public/index.php?page=catalogue');
            exit();
        }

        // Charger la vue
        require_once DIR . '/../views/produit.php';
    }
}
?>