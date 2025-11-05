<?php
    if (isset($_POST["connexion-form"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        

        echo "Adresse Courriel : " . htmlspecialchars($email) . "<br>";
        echo "Mot de passe : " . htmlspecialchars($password) . "<br>";
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Rich Ricasso</title> 
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php include __DIR__ . '/includes/header.php'; ?>
<body>
    <main>
        <div class="connexion-container">
            <h2>Connexion</h2>
            <form action="#" method="post" class="connexion-form">
                <label for="email">Adresse courriel:</label>
                <input type="text" id="email" name="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" name="connexion-form">Se connecter</button>
            </form>
        </div>

        <div class="inscription-container">
            <h2>Vous n'êtes pas inscris?</h2>
                <a href="inscription.php" style="text-decoration: none;">
                <button type="button">S'inscrire</button>
            </a>
        </div>
    </main>
</body>
</html>