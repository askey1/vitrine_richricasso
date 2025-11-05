<?php
// Include the Database and Utilisateur classes
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Utilisateur.php';

$message = '';
$messageType = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription-form'])) {
    // Get and sanitize form data
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $message = "Tous les champs sont obligatoires";
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format d'adresse e-mail invalide";
        $messageType = 'error';
    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas";
        $messageType = 'error';
    } elseif (strlen($password) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères";
        $messageType = 'error';
    } else {
        try {
            // Get database connection
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            // Create Utilisateur instance
            $utilisateur = new Utilisateur($pdo);
            $utilisateur->email = $email;
            
            // Check if email already exists
            if ($utilisateur->emailExists()) {
                $message = "Cette adresse e-mail est déjà utilisée";
                $messageType = 'error';
            } else {
                // Set user properties
                $utilisateur->nom = $nom;
                $utilisateur->prenom = $prenom;
                $utilisateur->mot_de_passe = $password;
                
                // Create user
                if ($utilisateur->create()) {
                    $message = "Inscription réussie! Vous pouvez maintenant vous connecter.";
                    $messageType = 'success';
                    
                    // Clear form fields on success
                    $nom = $prenom = $email = '';
                } else {
                    $message = "Erreur lors de l'inscription. Veuillez réessayer.";
                    $messageType = 'error';
                }
            }
        } catch(Exception $e) {
            error_log("Erreur inscription: " . $e->getMessage());
            $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .message {
            padding: 15px;
            border-radius: 4px;
            margin: 20px auto;
            max-width: 500px;
            font-weight: bold;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <form action="inscription.php" method="post" class="inscription-form">
        <h2>Inscription</h2>
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo isset($prenom) ? htmlspecialchars($prenom) : ''; ?>" required>

        <label for="email">Adresse courriel :</label>
        <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirmation du mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" name="inscription-form">S'inscrire</button>
    </form>
</body>
</html>