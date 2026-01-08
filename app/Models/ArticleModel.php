<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class ArticleModel extends BaseModel {

    public function createArticle($userId, $titre, $slug, $contenu, $visibilite) {
        $stmt = $this->db->prepare("INSERT INTO Articles (utilisateur_id, titre, slug, contenu, statut) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $titre, $slug, $contenu, $visibilite]);
    }

    public function updateArticle($titre, $contenu, $visibilite, $id) {
        $stmt = $this->db->prepare("UPDATE Articles SET titre = ?, contenu = ?, statut = ?, date_mise_a_jour = NOW() WHERE id = ?");
        $stmt->execute([$titre, $contenu, $visibilite, $id]);
    }

}
