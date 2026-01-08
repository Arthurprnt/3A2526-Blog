<?php
namespace App\Core;

use PDO;
use App\Core\BaseController;
use App\Controllers\Logger;

class Permissions{
    private static ?self $instance = null;

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function userHavePerm($userId, $perm) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM Role_User WHERE role_id = ? AND user_id = ?");
        $stmt->execute([$perm, $userId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $hasPerm = $res["COUNT(*)"];
        return $hasPerm;
    }
}
