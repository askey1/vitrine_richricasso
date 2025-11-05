<?php
require_once __DIR__ . '/../models/Produit.php';

class ProduitController {
    private $model;

    public function __construct($database) {
        $this->model = new Produit($database);
    }

    public function getAllProduit($filters = []) {
        return $this->model->getAll($filters);
    }

    public function creerProduit($data) {
        return $this->model->create($data);
    }

    public function modifierProduit($id, $data) {
        return $this->model->update($id, $data);
    }

    public function supprimerProduit($id) {
        return $this->model->delete($id);
    }

    public function catalogue() {
        try {
            $filters = array_filter([
                'type' => $_GET['categorie'] ?? null,
                'couleur' => $_GET['couleur'] ?? null,
                'prix_min' => $_GET['prix_min'] ?? null,
                'prix_max' => $_GET['prix_max'] ?? null
            ]);

            $produits = $this->model->getAll($filters);
            $page_title = 'Catalogue - Rich Ricasso';
            require_once __DIR__ . '/../views/catalogue.php';

        } catch (Exception $e) {
            error_log("Erreur catalogue: " . $e->getMessage());
            echo "Impossible de charger le catalogue.";
        }
    }
}
?>
