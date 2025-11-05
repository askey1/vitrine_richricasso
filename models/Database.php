<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Utiliser les constantes si elles existent, sinon valeurs par défaut
        $host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $dbname = defined('DB_NAME') ? DB_NAME : 'rich_ricasso_database';
        $username = defined('DB_USER') ? DB_USER : 'root';
        $password = defined('DB_PASS') ? DB_PASS : '';
        
        try {
            $this->connection = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            error_log("Connexion Database réussie");
        } catch(PDOException $e) {
            error_log("Erreur connexion Database: " . $e->getMessage());
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Empêcher le clonage de l'instance
    private function __clone() {}
    
    // Empêcher la désérialisation de l'instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>