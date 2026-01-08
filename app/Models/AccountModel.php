<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class AccountModel extends BaseModel {
    public function getUser($email) {
        $stmt = $this->db->prepare("SELECT * FROM Utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
