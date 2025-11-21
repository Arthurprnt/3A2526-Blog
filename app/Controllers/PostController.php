<?php
namespace App\Controllers;

use PDO;
use App\Controllers\Logger;
use App\Core\BaseController;
use App\Core\Database;
use App\Core\SessionManager;
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

        $this->render('post_show.twig', [
            'page_title' => $post->titre,
            'post' => $post,
            'comments' => $comments
        ]);
    }
}
