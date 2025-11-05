<?php
require_once __DIR__ . '/../models/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT id FROM abonnes WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            echo "Cette adresse e-mail est déjà inscrite!";
        } else {
            // Insert new subscriber
            $stmt = $pdo->prepare("INSERT INTO abonnes (email, date_inscription, actif) VALUES (?, NOW(), 1)");
            $stmt->execute([$email]);
            
            echo "Inscription réussie! Merci de vous être inscrit.";
            // Optionally redirect to a thank you page:
            // header("Location: ../index.php?success=subscribed");
            // exit();
        }
    } catch(PDOException $e) {
        error_log("Erreur subscription: " . $e->getMessage());
        echo "Erreur lors de l'inscription. Veuillez réessayer.";
    }
} else {
    // If accessed directly without POST, redirect back
    header("Location: ../index.php");
    exit();
}
?>