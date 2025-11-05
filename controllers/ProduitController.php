<?php
require_once __DIR__ . '/../models/Produit.php';

class ProduitController {
    private $produitModel;

    public function __construct() {
        $this->produitModel = new Produit();
    }

    // Afficher le catalogue
    public function catalogue() {
        // Récupérer les filtres depuis l'URL
        $type = $_GET['type'] ?? null;
        $couleur_id = $_GET['couleur'] ?? null;
        $prix_min = $_GET['prix_min'] ?? null;
        $prix_max = $_GET['prix_max'] ?? null;
        $search = $_GET['search'] ?? null;

        // Filtrer les produits
        if ($search) {
            $produits = $this->produitModel->search($search);
        } else {
            $produits = $this->produitModel->filter($type, $couleur_id, $prix_min, $prix_max);
        }

        // Récupérer les couleurs pour les filtres
        $couleurs = $this->produitModel->getCouleurs();

        // Récupérer les types de produits
        $types = $this->produitModel->getTypes();

        // Récupérer la plage de prix
        $prix_range = $this->produitModel->getPrixRange();

        // Charger la vue
        require_once __DIR__ . '/../views/catalogue.php';
    }

    // Afficher un produit spécifique
    public function detail() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /SiteEcom_RichRicasso/public/index.php?page=catalogue');
            exit();
        }
        
        $produit = $this->produitModel->getById($id);

        if (!$produit) {
            // Produit non trouvé, rediriger vers le catalogue
            header('Location: /SiteEcom_RichRicasso/public/index.php?page=catalogue');
            exit();
        }

        // Charger la vue
        require_once __DIR__ . '/../views/produit.php';
    }
}
?>