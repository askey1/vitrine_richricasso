<?php
// /C:/xampp/htdocs/vitrine_richricasso/models/Produit.php
// Modèle Produit : accès simple à la base pour un ProduitController

class Produit
{
    private static $pdo = null;
    private static $table = 'produits';

    // Configurez ici vos paramètres ou définissez des constantes DB_HOST, DB_NAME, DB_USER, DB_PASS
    private static function connect()
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
        $db   = defined('DB_NAME') ? DB_NAME : 'vitrine';
        $user = defined('DB_USER') ? DB_USER : 'root';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$pdo = new PDO($dsn, $user, $pass, $opt);
        } catch (PDOException $e) {
            throw new RuntimeException('DB connection failed: ' . $e->getMessage());
        }

        return self::$pdo;
    }

    // Récupérer tous les produits (optionnel pagination)
    public static function all($limit = null, $offset = null)
    {
        $pdo = self::connect();
        $sql = "SELECT * FROM " . self::$table . " ORDER BY id DESC";
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
            }
        }
        $stmt = $pdo->prepare($sql);
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Trouver un produit par id
    public static function find($id)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("SELECT * FROM " . self::$table . " WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Créer un produit. $data: assoc array (nom, description, prix, image, stock, category_id, ...)
    public static function create(array $data)
    {
        $pdo = self::connect();

        // champs autorisés — adaptez selon votre table
        $allowed = ['nom', 'description', 'prix', 'image', 'stock', 'category_id'];
        $fields = [];
        $placeholders = [];
        $values = [];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = $field;
                $placeholders[] = ':' . $field;
                $values[':' . $field] = $data[$field];
            }
        }

        if (empty($fields)) {
            throw new InvalidArgumentException('Aucun champ fourni pour la création.');
        }

        $sql = "INSERT INTO " . self::$table . " (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        return $pdo->lastInsertId();
    }

    // Mettre à jour un produit par id. $data : assoc array des champs à mettre à jour
    public static function update($id, array $data)
    {
        $pdo = self::connect();

        $allowed = ['nom', 'description', 'prix', 'image', 'stock', 'category_id'];
        $sets = [];
        $values = [':id' => $id];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $sets[] = "$field = :$field";
                $values[':' . $field] = $data[$field];
            }
        }

        if (empty($sets)) {
            throw new InvalidArgumentException('Aucun champ fourni pour la mise à jour.');
        }

        $sql = "UPDATE " . self::$table . " SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Supprimer un produit par id
    public static function delete($id)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare("DELETE FROM " . self::$table . " WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Recherche simple par nom/description
    public static function search($term, $limit = 20)
    {
        $pdo = self::connect();
        $sql = "SELECT * FROM " . self::$table . " WHERE nom LIKE :t OR description LIKE :t ORDER BY id DESC LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t', '%' . $term . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Compter les produits
    public static function count()
    {
        $pdo = self::connect();
        $stmt = $pdo->query("SELECT COUNT(*) as c FROM " . self::$table);
        $r = $stmt->fetch();
        return (int)($r['c'] ?? 0);
    }
}