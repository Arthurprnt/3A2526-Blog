<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class PostModel extends BaseModel {

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
