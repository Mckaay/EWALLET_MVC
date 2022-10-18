<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function save()
    {
        $sql = 'INSERT INTO users VALUES
        (NULL, :login, :password_hash, :email, :name)';

        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':login',$this->login,PDO::PARAM_STR);
        $stmt->bindValue(':password_hash',$password_hash,PDO::PARAM_STR);
        $stmt->bindValue(':email',$this->email,PDO::PARAM_STR);
        $stmt->bindValue(':name',$this->name,PDO::PARAM_STR);

        $stmt->execute();

    }
}
