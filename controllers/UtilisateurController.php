<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../config.php';

class UtilisateurController {
    private $utilisateurModel;
    
    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
    }
    
    // Afficher le formulaire d'inscription
    public function inscription() {
        require_once __DIR__ . '/../views/inscription.php';
    }
    
    // Traiter l'inscription
    public function traiterInscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mot_de_passe = $_POST['mot_de_passe'] ?? '';
            $confirmation = $_POST['confirmation'] ?? '';
            
            // Vérifier que les mots de passe correspondent
            if ($mot_de_passe !== $confirmation) {
                setFlashMessage('error', 'Les mots de passe ne correspondent pas.');
                redirect('/SiteEcom_RichRicasso/public/index.php?page=inscription');
            }
            
            // Inscrire l'utilisateur
            $result = $this->utilisateurModel->inscription($nom, $prenom, $email, $mot_de_passe);
            
            if ($result['success']) {
                setFlashMessage('success', $result['message']);
                redirect('/SiteEcom_RichRicasso/public/index.php?page=connexion');
            } else {
                setFlashMessage('error', $result['message']);
                redirect('/SiteEcom_RichRicasso/public/index.php?page=inscription');
            }
        }
    }
    
    // Afficher le formulaire de connexion
    public function connexion() {
        require_once __DIR__ . '/../views/connexion.php';
    }
    
    // Traiter la connexion
    public function traiterConnexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $mot_de_passe = $_POST['mot_de_passe'] ?? '';
            
            $result = $this->utilisateurModel->connexion($email, $mot_de_passe);
            
            if ($result['success']) {
                // Stocker les infos en session
                $_SESSION['user_id'] = $result['user']['id'];
                $_SESSION['user_nom'] = $result['user']['nom'];
                $_SESSION['user_prenom'] = $result['user']['prenom'];
                $_SESSION['user_email'] = $result['user']['email'];
                
                setFlashMessage('success', 'Connexion réussie !');
                redirect('/SiteEcom_RichRicasso/public/index.php?page=profil');
            } else {
                setFlashMessage('error', $result['message']);
                redirect('/SiteEcom_RichRicasso/public/index.php?page=connexion');
            }
        }
    }
    
    // Afficher le profil
    public function profil() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Vous devez être connecté pour accéder à votre profil.');
            redirect('/SiteEcom_RichRicasso/public/index.php?page=connexion');
        }
        
        $user = $this->utilisateurModel->getById($_SESSION['user_id']);
        require_once __DIR__ . '/../views/profil.php';
    }
    
    // Traiter la mise à jour du profil
    public function traiterUpdateProfil() {
        if (!isLoggedIn()) {
            redirect('/SiteEcom_RichRicasso/public/index.php?page=connexion');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            
            $result = $this->utilisateurModel->updateProfil($_SESSION['user_id'], $nom, $prenom, $email);
            
            if ($result['success']) {
                // Mettre à jour la session
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                $_SESSION['user_email'] = $email;
                
                setFlashMessage('success', $result['message']);
            } else {
                setFlashMessage('error', $result['message']);
            }
            
            redirect('/SiteEcom_RichRicasso/public/index.php?page=profil');
        }
    }
    
    // Déconnexion
    public function deconnexion() {
        logout();
        setFlashMessage('success', 'Vous avez été déconnecté.');
        redirect('/SiteEcom_RichRicasso/public/index.php');
    }
    
    // Traiter l'inscription à l'infolettre
    public function traiterInfolettre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            
            $result = Utilisateur::inscriptionInfolettre($email);
            
            echo json_encode($result);
            exit();
        }
    }
}
?>