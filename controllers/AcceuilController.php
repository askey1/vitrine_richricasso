<?php
require_once __DIR__ . '/../models/Produit.php';


class AccueilController {
    private $produitModel;

    public function construct() {
        $this->produitModel = new Produit();
    }

    public function index() {
        // Récupérer les produits en vedette
        $produits_vedette = $this->produitModel->getFeatured();

        // Charger la vue
        require_once __DIR__ . '/../views/accueil.php';
    }
}

?>