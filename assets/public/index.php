<?php
session_start();

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../controllers/AccueilController.php';
require_once __DIR__ . '/../controllers/ProduitController.php';
require_once __DIR__ . '/../controllers/UtilisateurController.php';

// Récupérer la page demandée
$page = $_GET['page'] ?? 'accueil';

// Router
switch ($page) {
    case 'accueil':
        $controller = new AccueilController();
        $controller->index();
        break;
        
    case 'catalogue':
        $controller = new ProduitController();
        $controller->catalogue();
        break;
        
    case 'produit':
        $controller = new ProduitController();
        $controller->detail();
        break;
        
    case 'inscription':
        $controller = new UtilisateurController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->traiterInscription();
        } else {
            $controller->inscription();
        }
        break;
        
    case 'connexion':
        $controller = new UtilisateurController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->traiterConnexion();
        } else {
            $controller->connexion();
        }
        break;
        
    case 'profil':
        $controller = new UtilisateurController();
        $controller->profil();
        break;
        
    case 'update_profil':
        $controller = new UtilisateurController();
        $controller->traiterUpdateProfil();
        break;
        
    case 'deconnexion':
        $controller = new UtilisateurController();
        $controller->deconnexion();
        break;
        
    default:
        // Page 404
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}
?>