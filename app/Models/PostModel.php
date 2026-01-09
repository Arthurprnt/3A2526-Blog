<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class PostModel extends BaseModel {

    public function createArticle($userId, $titre, $slug, $contenu, $visibilite) {
        $stmt = $this->db->prepare("INSERT INTO Articles (utilisateur_id, titre, slug, contenu, statut) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $titre, $slug, $contenu, $visibilite]);
    }

    public function updateArticle($titre, $contenu, $visibilite, $id) {
        $stmt = $this->db->prepare("UPDATE Articles SET titre = ?, contenu = ?, statut = ?, date_mise_a_jour = NOW() WHERE id = ?");
        $stmt->execute([$titre, $contenu, $visibilite, $id]);
    }

    /**
     * Récupère tous les articles de blog.
     */
    public function getPostNumber(): array {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM Articles");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error("Erreur lors de la récupération de tous les posts", $e);
            return [];
        }
    }

    /**
     * Récupère tous les articles de blog.
     */
    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM Articles ORDER BY date_creation DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logger->error("Erreur lors de la récupération de tous les posts", $e);
            return [];
        }
    }

    /**
     * Récupère un article par son ID.
     */
    public function findById(int $id): object|false {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Articles WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logger->error("Erreur lors de la récupération du post ID $id", $e);
            return false;
        }
    }

    public function findPublicsBy($userId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Articles WHERE utilisateur_id = ? AND statut = 'Publié' ORDER BY date_creation DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logger->error("Erreur lors de la récupération de tous les posts", $e);
            return [];
        }
    }

    public function findPrivatesBy($userId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Articles WHERE utilisateur_id = ? AND statut = 'Brouillon' ORDER BY date_creation DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logger->error("Erreur lors de la récupération de tous les posts", $e);
            return [];
        }
    }
}
