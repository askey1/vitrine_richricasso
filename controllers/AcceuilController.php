<?php
require_once DIR . '/../models/Produit.php';


class AccueilController {
    private $produitModel;

    public function construct() {
        $this->produitModel = new Produit();
    }

    public function index() {
        // Récupérer les produits en vedette
        $produits_vedette = $this->produitModel->getFeatured();

        // Charger la vue
        require_once DIR__ . '/../views/accueil.php';
    }
}

?>