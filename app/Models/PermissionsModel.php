<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class PermissionsModel extends BaseModel{
    public function userHavePerm($userId, $perm) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Role_User WHERE role_id = ? AND user_id = ?");
        $stmt->execute([$perm, $userId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $hasPerm = $res["COUNT(*)"];
        return $hasPerm;
    }
}
