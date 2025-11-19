<?php
namespace App\Models;

use App\Core\BaseModel;
use PDOException;

class PostModel extends BaseModel {

    /**
     * Récupère tous les articles de blog.
     */
    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM posts ORDER BY created_at DESC");
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
            $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->logger->error("Erreur lors de la récupération du post ID $id", $e);
            return false;
        }
    }
}
