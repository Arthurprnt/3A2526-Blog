<?php
namespace App\Models;

use PDO;
use App\Core\BaseModel;
use PDOException;

class AccountModel extends BaseModel {
    public function createUser($name, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO Utilisateurs (nom_utilisateur, email, mot_de_passe, est_actif) VALUES (?, ?, ?, 1)");
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function deleteUser($email) {
        $stmt = $this->db->prepare("DELETE FROM Utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
    }

    public function getUser($email) {
        $stmt = $this->db->prepare("SELECT * FROM Utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
