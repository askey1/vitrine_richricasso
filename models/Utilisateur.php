<?php

class Utilisateur {
    private $conn;
    private $table_name = "utilisateurs";
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $date_creation;
    public $derniere_connexion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all users
    public function getAll() {
        $query = "SELECT id, nom, prenom, email, mot_de_passe, date_creation, derniere_connexion
                  FROM " . $this->table_name . " 
                  ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get user by ID
    public function getById() {
        $query = "SELECT id, nom, prenom, email, mot_de_passe, date_creation, derniere_connexion
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->email = $row['email'];
            $this->mot_de_passe = $row['mot_de_passe'];
            $this->date_creation = $row['date_creation'];
            $this->derniere_connexion = $row['derniere_connexion'];
            return true;
        }
        return false;
    }

    // Get user by email
    public function getByEmail() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->email = $row['email'];
            $this->mot_de_passe = $row['mot_de_passe'];
            $this->date_creation = $row['date_creation'];
            $this->derniere_connexion = $row['derniere_connexion'];
            return true;
        }
        return false;
    }

    // Create new user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nom=:nom, prenom=:prenom, email=:email, mot_de_passe=:mot_de_passe, 
                      date_creation=NOW()";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Hash password
        $password_hash = password_hash($this->mot_de_passe, PASSWORD_BCRYPT);
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mot_de_passe", $password_hash);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Update user
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nom=:nom, prenom=:prenom, email=:email
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Update password
    public function updatePassword() {
        $query = "UPDATE " . $this->table_name . " 
                  SET mot_de_passe=:mot_de_passe 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $password_hash = password_hash($this->mot_de_passe, PASSWORD_BCRYPT);
        
        $stmt->bindParam(":mot_de_passe", $password_hash);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }
   // Check if email exists
    // Check if email exists
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Login user
    public function login() {
        if($this->getByEmail()) {
            if(password_verify($this->mot_de_passe, $this->mot_de_passe)) {
                return true;
            }
        }
        return false;
    }
}
?>
