<?php
namespace App\Controllers;

use PDO;
use App\Core\BaseController;
use App\Core\SessionManager;
use App\Models\AccountModel;
use App\Models\PostModel;
use App\Models\PermissionsModel;
use App\Models\CommentsModel;
use App\Controllers\Logger;

class LoginController extends BaseController {
    private AccountModel $accountModel;
    private CommentsModel $commModel;
    private PostModel $postModel;
    private PermissionsModel $permModel;

    public function __construct() {
        parent::__construct(); 
        $this->accountModel = new AccountModel();
        $this->commModel = new CommentsModel();
        $this->postModel = new PostModel();
        $this->permModel = new PermissionsModel();
    }

    /**
     * Affiche la page "Connexion". (NOUVEAU)
     */
    public function connexion(): void {
        $errors = [];
        $success_message = $this->session->get('connexion_success_message');
        $this->session->remove('connexion_success_message'); // Message flash

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Nettoyage et Validation
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "L'adresse email est invalide.";
            if (empty($password)) $errors['password'] = "Le mot de passe est requis.";

            if (empty($errors)) {
                $account = $this->accountModel->getUser($email);

                if ($account && password_verify($password, $account['mot_de_passe'])) {
                    $session = SessionManager::getInstance();
                    $session->set("connecte", "true");
                    $session->set("user", $account);

                    $this->logger->info("Nouvelle connexion au compte: $email.");
                
                    // 3. Redirection (Post/Redirect/Get pattern)
                    $this->session->set('dashboard_success_message', 'Connexion effectuée avec succès !');
                    header('Location: /3A2526-Blog/dashboard');
                    exit;
                } else {
                    $this->logger->warning("La tentative de connexion au compte $email a échouée.");
                    $this->session->set('connexion_success_message', 'Erreur lors de la connexion (mauvais email ou mot de passe).');
                    header('Location: /3A2526-Blog/connexion');
                }
            } else {
                $this->logger->info("Erreur lors de la connexion à votre compte.");
            }
        }

        $this->render('connexion.twig', [
            'page_title' => 'Se connecter',
            'errors' => $errors,
            'success_message' => $success_message,
            'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
        ]);
    }

    /**
     * Affiche la page "Deconnexion". (NOUVEAU)
     */
    public function deconnexion(): void {
        $errors = [];
        $success_message = $this->session->get('deconnexion_success_message');
        $this->session->remove('deconnexion_success_message'); // Message flash

        $session = SessionManager::getInstance();
        $session->set("connecte", "false");
        $session->set("user", "");

        $this->render('deconnexion.twig', [
            'page_title' => 'Déconnexion',
            'errors' => $errors,
            'success_message' => $success_message,
            'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
        ]);
    }

    /**
     * Affiche la page "Signup". (NOUVEAU)
     */
    public function signup(): void {
        $errors = [];
        $success_message = $this->session->get('signup_success_message');
        $this->session->remove('signup_success_message'); // Message flash

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Nettoyage et Validation
            $name = trim($_POST['name'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $this->logger->info("Début de la procédure de création de compte");

            if (empty($name)) $errors['name'] = "Le nom est requis.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "L'adresse email est invalide.";
            if (empty($password)) $errors['password'] = "Le mot de passe est requis.";

            if (empty($errors)) {                
                // 3. Redirection (Post/Redirect/Get pattern)
                try {
                    // Création de l'utilisateur dans la bd
                    $this->accountModel->createUser($name, $email, $password);

                    // Récupère l'id de l'utilisateur qui vient d'être créé
                    $account = $this->accountModel->getUser($email);

                    $session = SessionManager::getInstance();
                    $session->set("connecte", "true");
                    $session->set("user", $account);

                    $userId = $account["id"];

                    // Ajoute le rôle par défaut au nouvel utilisateur
                    $this->permModel->addPermToUser($userId, 3);

                    $this->logger->info("Nouveau compte créé avec l'email: $email.");
                    $this->session->set('dashboard_success_message', 'Création du compte effectuée avec succès !');
                    header('Location: /3A2526-Blog/dashboard');
                    exit;
                } catch (PDOException $e) {
                    die("Erreur SQL : " . $e->getMessage());
                }
            } else {
                $this->logger->info("Erreur lors de la création du compte.");
            }
        }

        $this->render('signup.twig', [
            'page_title' => 'Création de compte',
            'errors' => $errors,
            'success_message' => $success_message,
            'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
        ]);
    }

    /**
     * Affiche la page "Dashboard". (NOUVEAU)
     */
    public function dashboard(): void {
        $errors = [];
        $success_message = $this->session->get('dashboard_success_message');
        $this->session->remove('dashboard_success_message'); // Message flash

        $session = SessionManager::getInstance();

        if ($session->get("connecte") === "false") {
            // On invite l'utilisateur à se connecter
            header('Location: /3A2526-Blog/connexion');
        } else {
            // On affiche les données liées à l'utilisateur
            $user = $session->get("user");
            $isAdmin = $this->permModel->userHavePerm($user["id"], 1);
            
            $this->render('dashboard.twig', [
                'page_title' => 'Dashboard utilisateur',
                'user' => $user,
                'errors' => $errors,
                'isAdmin' => $isAdmin,
                'success_message' => $success_message,
                'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
            ]);
        }
    }

    /**
     * Affiche la page "delete_account". (NOUVEAU)
     */
    public function delete_account(): void {
        $errors = [];
        $success_message = $this->session->get('delete_account_success_message');
        $this->session->remove('delete_account_success_message'); // Message flash

        $session = SessionManager::getInstance();

        if ($session->get("connecte") === "false") {
            // L'utilisateut ne peut pas supp son compte si pas connecté
            header('Location: /3A2526-Blog/');
        } else {
            $user = $session->get('user');
            $this->accountModel->deleteUser($user['email']);

            $this->render('delete_account.twig', [
                'page_title' => 'Suppression du compte',
                'errors' => $errors,
                'success_message' => $success_message,
                'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
            ]);
        }
    }

    /**
     * Affiche la page "admin". (NOUVEAU)
     */
    public function admin(): void {
        $errors = [];
        $success_message = $this->session->get('admin_success_message');
        $this->session->remove('admin_success_message'); // Message flash

        $session = SessionManager::getInstance();

        if ($session->get("connecte") === "false") {
            // L'utilisateut ne peut pas créer de post si pas connecté
            header('Location: /3A2526-Blog/');
        } else {

            // Validation ou suppression des commentaires
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $titre = $_POST['titre'] ?? '';
                $id = $_POST['id'] ?? '';
                
                if($titre == "valider") {
                    $this->commModel->approveComment($id);
                } else if($titre == "supprimer") {
                    $this->commModel->deleteComment($id);
                }
                
            }

            $user = $session->get("user");
            $isAdmin = $this->permModel->userHavePerm($user["id"], 1);

            $comments = $this->commModel->getPendingComments();

            if ($isAdmin == 0) {
                header('Location: /3A2526-Blog/');
            } else {
                // Récupère lle nombre de post
                $nbPosts = $this->postModel->getPostNumber();

                $this->render('admin_dashboard.twig', [
                    'page_title' => 'Panel admin:',
                    'nb_posts' => $nbPosts["COUNT(*)"],
                    'comments' => $comments,
                    'errors' => $errors,
                    'success_message' => $success_message,
                    'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
                ]);
            }
        }
    }
}
