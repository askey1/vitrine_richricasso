<?php
require_once __DIR__ . '/../models/Produit.php';

class ProduitController {
    private $produitModel;

    public function __construct() {                 // IMPORTANT: double underscore
        $this->produitModel = new Produit();
    }

    // Afficher le catalogue
    public function catalogue() {
        $type       = $_GET['type']     ?? null;
        $couleur_id = $_GET['couleur']  ?? null;
        $prix_min   = $_GET['prix_min'] ?? null;
        $prix_max   = $_GET['prix_max'] ?? null;

        // Récupération avec gardes et valeurs par défaut
        $produits   = $this->produitModel->filter($type, $couleur_id, $prix_min, $prix_max) ?: [];
        $couleurs   = $this->produitModel->getCouleurs() ?: [];
        $prix_range = $this->produitModel->getPrixRange() ?: ['prix_min'=>0,'prix_max'=>500];
        $types      = $this->produitModel->getTypes() ?: [];

        require_once __DIR__ . '/../views/catalogue.php';
    }

    // Afficher un produit (page détail, si tu l’utilises)
    public function detail($id) {
        $produit = $this->produitModel->getById($id);
        if (!$produit) {
            header('Location: index.php?page=catalogue');
            exit();
        }
        require_once __DIR__ . '/../views/produit.php';
    }
}
