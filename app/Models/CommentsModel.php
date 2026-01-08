<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class CommentsModel extends BaseModel {

    public function getPendingComments() {
        $stmt = $this->db->query("SELECT * FROM Commentaires WHERE statut = 'En attente'");
        return $stmt->fetchAll();
    }
}
