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

    public function postComment($postid, $userName, $userEmail, $commentaire) {
        $stmt = $this->db->prepare("INSERT INTO Commentaires (article_id, nom_auteur, email_auteur, contenu, statut) VALUES (?, ?, ?, ?, 'En attente')");
        $stmt->execute([$postid, $userName, $userEmail, $commentaire]);
    }

    public function approveComment($id) {
        $stmt = $this->db->prepare("UPDATE Commentaires SET statut = 'Approuvé' WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteComment($id) {
        $stmt = $this->db->prepare("DELETE FROM Commentaires WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getArticleComments($postId) {
        $stmt = $this->db->prepare("SELECT * FROM Commentaires WHERE article_id = ? AND statut = 'Approuvé' ORDER BY date_commentaire DESC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
