<?php
require_once __DIR__ . '/../models/Database.php';

class Produit {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les produits avec filtres
    public function filter($type = null, $couleur_id = null, $prix_min = null, $prix_max = null) {
        $sql = "SELECT p.*, c.nom as couleur_nom, c.code_hex, c.code_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE 1=1";
        
        $params = [];
        
        if ($type) {
            $sql .= " AND p.type = :type";
            $params[':type'] = $type;
        }
        
        if ($couleur_id) {
            $sql .= " AND p.couleur_id = :couleur_id";
            $params[':couleur_id'] = $couleur_id;
        }
        
        if ($prix_min !== null) {
            $sql .= " AND p.prix >= :prix_min";
            $params[':prix_min'] = $prix_min;
        }
        
        if ($prix_max !== null) {
            $sql .= " AND p.prix <= :prix_max";
            $params[':prix_max'] = $prix_max;
        }
        
        $sql .= " ORDER BY p.nom ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les produits en vedette
    public function getFeatured() {
        $sql = "SELECT p.*, c.nom as couleur_nom, c.code_hex, c.code_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE p.en_vedette = 1
                ORDER BY p.date_ajout DESC
                LIMIT 6";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer un produit par ID avec toutes ses informations
    public function getById($id) {
        $sql = "SELECT p.*, c.nom as couleur_nom, c.code_hex, c.code_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE p.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($produit) {
            // Récupérer les images du produit
            $produit['images'] = $this->getImages($id);
            
            // Récupérer les tailles disponibles
            $produit['tailles'] = $this->getTailles($id);
        }
        
        return $produit;
    }
    
    // Récupérer les images d'un produit
    public function getImages($produit_id) {
        $sql = "SELECT * FROM images_produit 
                WHERE produit_id = :produit_id 
                ORDER BY ordre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':produit_id' => $produit_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les tailles disponibles pour un produit
    public function getTailles($produit_id) {
        $sql = "SELECT t.* FROM tailles t
                INNER JOIN produit_taille pt ON t.id = pt.taille_id
                WHERE pt.produit_id = :produit_id
                ORDER BY t.id ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':produit_id' => $produit_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer toutes les couleurs
    public function getCouleurs() {
        $sql = "SELECT * FROM couleurs ORDER BY nom ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer la plage de prix
    public function getPrixRange() {
        $sql = "SELECT MIN(prix) as prix_min, MAX(prix) as prix_max FROM produits";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les types de produits disponibles
    public function getTypes() {
        $sql = "SELECT DISTINCT type FROM produits ORDER BY type ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Rechercher des produits
    public function search($query) {
        $sql = "SELECT p.*, c.nom as couleur_nom, c.code_hex, c.code_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE p.nom LIKE :query 
                   OR p.description LIKE :query
                   OR p.type LIKE :query
                ORDER BY p.nom ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':query' => '%' . $query . '%']);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>