<?php
namespace App\Controllers;

use PDO;
use App\Controllers\Logger;
use App\Core\BaseController;
use App\Core\Database;
use App\Core\SessionManager;
use App\Core\Permissions;
use App\Models\PostModel;

class PostController extends BaseController {
    private PostModel $postModel;

    public function __construct() {
        parent::__construct(); 
        $this->postModel = new PostModel();
    }

    /**
     * Affiche un article spécifique par son ID.
     */
    public function show(int $id): void {
        $errors = [];
        $success_message = $this->session->get('contact_success_message');
        $this->session->remove('contact_success_message'); // Message flash
        $post = $this->postModel->findById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentaire = $_POST['commentaire'] ?? '';

            if (empty($commentaire)) $errors['commentaire'] = "Vous ne pouvez pas envoyer un commentaire vide.";

            if (empty($erros)) {
                $session = SessionManager::getInstance();
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("INSERT INTO Commentaires (article_id, nom_auteur, email_auteur, contenu, statut) VALUES (?, ?, ?, ?, 'En attente')");
                $stmt->execute([$post->id, $session->get("user")["nom_utilisateur"], $session->get("user")["email"], $commentaire]);

            } else {
                $this->logger->info("Tentative d'envoie de commentaire vide.");
            }
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM Commentaires WHERE article_id = ? AND statut = 'Approuvé' ORDER BY date_commentaire DESC");
        $stmt->execute([$post->id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$post) {
            // Si l'article n'existe pas, on redirige vers la 404
            (new HomeController())->error404();
            return;
        }

        $session = SessionManager::getInstance();
        $user = $session->get('user');
        $canEdit = (Permissions::getInstance()->userHavePerm($user["id"], 1)) + (Permissions::getInstance()->userHavePerm($user["id"], 2)) + ($post->id === $user["id"]);


        $this->render('post_show.twig', [
            'page_title' => $post->titre,
            'post' => $post,
            'canEdit' => $canEdit,
            'comments' => $comments
        ]);
    }

    
    /**
     * Affiche la page "creer". (NOUVEAU)
     */
    public function creer(): void {
        $errors = [];
        $success_message = $this->session->get('creer_success_message');
        $this->session->remove('creer_success_message'); // Message flash

        $session = SessionManager::getInstance();

        if ($session->get("connecte") === "false") {
            // L'utilisateut ne peut pas créer de post si pas connecté
            header('Location: /3A2526-Blog/');
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // 1. Nettoyage et Validation
                $titre = $_POST['titre'] ?? '';
                $contenu = $_POST['contenu'] ?? '';
                $visibilite = $_POST['visibilite'] ?? '';
                
                $this->logger->info($titre);

                if (empty($titre)) $errors['titre'] = "Le post doit avoir un titre.";
                if (empty($contenu)) $errors['contenu'] = "Le post doit avoir un contenu.";
                if (empty($visibilite)) $errors['visibilite'] = "Choisissez la visibilité de votre nouveau post.";

                if (empty($errors)) {
                    $slug = $this->convertTitleToURL($titre);

                    $db = Database::getInstance()->getConnection();
                    $stmt = $db->prepare("INSERT INTO Articles (utilisateur_id, titre, slug, contenu, statut) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$session->get("user")["id"], $titre, $slug, $contenu, $visibilite]);
                    header('Location: /3A2526-Blog/');

                } else {
                    $this->logger->info("Erreur lors d'une tentative d'enregistrement de post.");
                }
            }

            $this->render('creer.twig', [
                'page_title' => 'Nouveau post:',
                'errors' => $errors,
                'success_message' => $success_message,
                'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
            ]);
        }
    }

    /**
     * Affiche la page "myposts". (NOUVEAU)
     */
    public function myposts(): void {
        $session = SessionManager::getInstance();
        if ($session->get("connecte") === "false") {
            header('Location: /3A2526-Blog/');
        } else {
            $publics_posts = $this->postModel->findPublicsBy($session->get('user')["id"]);
            $privates_posts = $this->postModel->findPrivatesBy($session->get('user')["id"]);

            $this->render('my_post.twig', [
                'page_title' => 'Mes posts:',
                'public_posts' => $publics_posts,
                'private_posts' => $privates_posts
            ]);
        }
    }

    /**
     * Affiche la page "edit". (NOUVEAU)
     */
    public function edit(): void {
        $errors = [];
        $success_message = $this->session->get('creer_success_message');
        $this->session->remove('creer_success_message'); // Message flash

        $session = SessionManager::getInstance();

        if ($session->get("connecte") === "false") {
            // L'utilisateut ne peut pas créer de post si pas connecté
            header('Location: /3A2526-Blog/');
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // 1. Nettoyage et Validation
                $id = $_POST['id'] ?? '';
                $titre = $_POST['titre'] ?? '';
                $contenu = $_POST['contenu'] ?? '';
                $visibilite = $_POST['visibilite'] ?? '';
                $isEnvoie = $_POST['envoie'] ?? '';
                
                $this->logger->info($titre);

                if (empty($titre)) $errors['titre'] = "Le post doit avoir un titre.";
                if (empty($contenu)) $errors['contenu'] = "Le post doit avoir un contenu.";
                if (empty($visibilite)) $errors['visibilite'] = "Choisissez la visibilité de votre nouveau post.";
                if ($isEnvoie === "false") $errors['envoie'] = "L'envoie n'a pas été demandé";

                if (empty($errors)) {
                    $slug = $this->convertTitleToURL($titre);

                    $db = Database::getInstance()->getConnection();
                    $stmt = $db->prepare("UPDATE Articles SET titre = ?, contenu = ?, statut = ?, date_mise_a_jour = NOW() WHERE id = ?");
                    $stmt->execute([$titre, $contenu, $visibiliten, $id]);
                    header('Location: /3A2526-Blog/');

                } else {
                    $this->logger->info("Erreur lors d'une tentative d'enregistrement de post.");
                }
            } else {
                header('Location: /3A2526-Blog/');
            }

            $this->render('creer.twig', [
                'page_title' => 'Editer le post:',
                'errors' => $errors,
                'success_message' => $success_message,
                'old_input' => $_POST ?? [] // Garder les valeurs précédentes en cas d'erreur
            ]);
        }
    }
}
