<?php
require_once __DIR__ . '/../models/Database.php';

class Produit {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Catalogue avec filtres (tous optionnels).
     * Les gardes évitent d’ajouter des clauses avec des valeurs vides.
     * Les alias correspondent aux noms lus dans la vue.
     */
    public function filter($type = null, $couleur_id = null, $prix_min = null, $prix_max = null) {
        $sql = "SELECT p.*,
                       c.nom      AS couleur_nom,
                       c.code_hex AS couleur_hex,
                       c.code_rgb AS couleur_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE 1=1";
        $params = [];

        if ($type !== null && $type !== '') {
            $sql .= " AND p.type = :type";
            $params[':type'] = $type;
        }
        if ($couleur_id !== null && $couleur_id !== '') {
            $sql .= " AND p.couleur_id = :couleur_id";
            $params[':couleur_id'] = $couleur_id;
        }
        if ($prix_min !== null && $prix_min !== '' && is_numeric($prix_min)) {
            $sql .= " AND p.prix >= :prix_min";
            $params[':prix_min'] = (float)$prix_min;
        }
        if ($prix_max !== null && $prix_max !== '' && is_numeric($prix_max)) {
            $sql .= " AND p.prix <= :prix_max";
            $params[':prix_max'] = (float)$prix_max;
        }

        $sql .= " ORDER BY p.nom ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeatured() {
        $sql = "SELECT p.*,
                       c.nom      AS couleur_nom,
                       c.code_hex AS couleur_hex,
                       c.code_rgb AS couleur_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE p.en_vedette = 1
                ORDER BY p.date_ajout DESC
                LIMIT 6";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT p.*,
                       c.nom      AS couleur_nom,
                       c.code_hex AS couleur_hex,
                       c.code_rgb AS couleur_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produit) {
            $produit['images_secondaires'] = $this->getImages($id);
            $produit['tailles']            = $this->getTailles($id);
        }
        return $produit;
    }

    public function getImages($produit_id) {
        $sql = "SELECT * FROM images_produit
                WHERE produit_id = :pid
                ORDER BY ordre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $produit_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTailles($produit_id) {
        $sql = "SELECT t.* FROM tailles t
                INNER JOIN produit_taille pt ON t.id = pt.taille_id
                WHERE pt.produit_id = :pid
                ORDER BY t.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $produit_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCouleurs() {
        return $this->db->query("SELECT * FROM couleurs ORDER BY nom ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrixRange() {
        return $this->db->query("SELECT MIN(prix) AS prix_min, MAX(prix) AS prix_max FROM produits")
                        ->fetch(PDO::FETCH_ASSOC);
    }

    public function getTypes() {
        try {
            $stmt = $this->db->query("SELECT DISTINCT type FROM produits
                                      WHERE type IS NOT NULL AND type <> ''
                                      ORDER BY type ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (PDOException $e) {
            error_log('getTypes error: ' . $e->getMessage());
            return [];
        }
    }

    public function search($query) {
        $sql = "SELECT p.*,
                       c.nom      AS couleur_nom,
                       c.code_hex AS couleur_hex,
                       c.code_rgb AS couleur_rgb
                FROM produits p
                LEFT JOIN couleurs c ON p.couleur_id = c.id
                WHERE p.nom LIKE :q OR p.description LIKE :q OR p.type LIKE :q
                ORDER BY p.nom ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':q' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
