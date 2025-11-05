<?php
// === CHARGEMENT DES FICHIERS NÉCESSAIRES ===

// Modèles
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Produit.php';

// Contrôleurs
require_once __DIR__ . '/../controllers/AccueilController.php';
require_once __DIR__ . '/../controllers/ProduitController.php';
require_once __DIR__ . '/../controllers/UtilisateurController.php';


// === CONNEXION À LA BASE DE DONNÉES ===
$database = Database::getInstance();


$page = $_GET['page'] ?? 'accueil';

switch ($page) {
    case 'accueil':
        $controller = new AccueilController($database);
        $controller->index();
        break;

    case 'catalogue':
        $controller = new ProduitController($database);
        $controller->catalogue();
        break;

    case 'connexion':
        $controller = new UtilisateurController($database);
        $controller->connexion();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        break;
}
