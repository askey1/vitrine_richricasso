<?php
// Inclure la configuration
require_once __DIR__ . '/../config.php';

// Récupérer la page demandée
$page = $_GET['page'] ?? 'accueil';

// Routage simplifié
switch ($page) {
    // PAGE D'ACCUEIL
    case 'accueil':
    case 'home':
    case '':
        require_once __DIR__ . '/../controllers/AccueilController.php';
        $controller = new AccueilController();
        $controller->index();
        break;
    
    // CATALOGUE
    case 'catalogue':
        require_once __DIR__ . '/../controllers/ProduitController.php';
        $controller = new ProduitController();
        $controller->catalogue();
        break;
    
    // DÉTAIL PRODUIT
    case 'produit':
        require_once __DIR__ . '/../controllers/ProduitController.php';
        $controller = new ProduitController();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->detail($id);
        } else {
            header('Location: /SiteEcom_RichRicasso/public/index.php?page=catalogue');
            exit();
        }
        break;
    
    // INSCRIPTION
    case 'inscription':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->inscription();
        break;
    
    case 'traiter_inscription':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->traiterInscription();
        break;
    
    // CONNEXION
    case 'connexion':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->connexion();
        break;
    
    case 'traiter_connexion':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->traiterConnexion();
        break;
    
    // PROFIL
    case 'profil':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->profil();
        break;
    
    case 'traiter_profil':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->traiterUpdateProfil();
        break;
    
    // DÉCONNEXION
    case 'deconnexion':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->deconnexion();
        break;
    
    // NEWSLETTER
    case 'infolettre':
        require_once __DIR__ . '/../controllers/UtilisateurController.php';
        $controller = new UtilisateurController();
        $controller->traiterInfolettre();
        break;
    
    // PAGE 404
    default:
        http_response_code(404);
        $page_title = 'Page non trouvée - Rich Ricasso';
        require_once __DIR__ . '/../views/includes/header.php';
        ?>
        <div class="container">
            <div class="error-page">
                <h1 style="font-size: 5rem; color: #ff71ce;">404</h1>
                <h2>Page non trouvée</h2>
                <p>La page que vous recherchez n'existe pas.</p>
                <a href="/vitrine_richricasso/public/index.php" style="display: inline-block; margin-top: 1rem; padding: 0.8rem 2rem; background: #ff71ce; color: #1a1a2e; text-decoration: none; border-radius: 5px;">Retour à l'accueil</a>
            </div>
        </div>
        <?php
        require_once __DIR__ . '/../views/includes/footer.php';
        break;
}
?>