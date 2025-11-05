<?php
class Produit {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /*
     * CREATE - Ajouter un nouveau produit
     * tableau : nom, description, prix, couleurid, type, image_principale, stock, en_vedette
     * return bool - true si l'insertion réussie, false sinon
     */
    public function create($data) {
        $sql = "INSERT INTO produits (nom, description, prix, couleurid, type, image_principale, stock, en_vedette, date_ajout)
                VALUES (:nom, :description, :prix, :couleurid, :type, :image_principale, :stock, :en_vedette, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':description' => $data['description'],
            ':prix' => $data['prix'],
            ':couleurid' => $data['couleurid'],
            ':type' => $data['type'],
            ':image_principale' => $data['image_principale'],
            ':stock' => $data['stock'],
            ':en_vedette' => $data['en_vedette'] ?? 0
        ]);
    }
    /*
     * READ - Lire tous les produits avec filtres optionnels
     * Filtres disponibles: type, couleur, taille, prix_min, prix_max
     * return Liste des produits
     */
    public function getAll($filters = [])
 {
        try {
            $sql = "SELECT p.*, c.nom as couleur_nom 
                    FROM produits p 
                    LEFT JOIN couleurs c ON p.couleurid = c.id 
                    WHERE 1=1";
            
            $params = [];

            // Filtre par type (cravate/chemise)
            if (!empty($filters['type'])) {
                $sql .= " AND p.type = :type";
                $params[':type'] = $filters['type'];
            }

            // Filtre par couleur
            if (!empty($filters['couleur'])) {
                $sql .= " AND p.couleurid = :couleur";
                $params[':couleur'] = $filters['couleur'];
            }

            // Filtre par taille (pour chemises uniquement)
            if (!empty($filters['taille'])) {
                $sql .= " AND EXISTS (
                    SELECT 1 FROM produit_tailles pt 
                    WHERE pt.produitid = p.id AND pt.taille = :taille
                )";
                $params[':taille'] = $filters['taille'];
            }

            // Filtre par gamme de prix
            if (!empty($filters['prix_min'])) {
                $sql .= " AND p.prix >= :prix_min";
                $params[':prix_min'] = $filters['prix_min'];
            }
            if (!empty($filters['prix_max'])) {
                $sql .= " AND p.prix <= :prix_max";
                $params[':prix_max'] = $filters['prix_max'];
            }

            $sql .= " ORDER BY p.en_vedette DESC, p.date_ajout DESC";

            $stmt = $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lecture produits: " . $e->getMessage());
            return [];
        }
    }

    /*
     * READ - Lire un produit par ID
     * return Produit ou null
     */
    public function getUserById() {
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->db->query($sql);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /*
     * UPDATE - Mettre à jour un produit existant
     * tableau avec les clés correspondantes aux colonnes
     * return bool - true si la mise à jour réussie, false sinon
     */
    public function update($id, $data) {
        $sql = "UPDATE produits SET nom = :nom, description = :description, prix = :prix, couleurid = :couleurid, 
                type = :type, image_principale = :image_principale, stock = :stock, en_vedette = :en_vedette
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_merge($data, [':id' => $id]));
    }

    /*
     * DELETE - Supprimer un produit
     * return bool - true si suppression réussie, false sinon
     * 
     * PAS NÉCESSAIRE DANS LE CADRE DU LABO, MAIS INCLUS POUR COMPLÉTER
     */
    public function delete($id) {
        $sql = "DELETE FROM produits WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
