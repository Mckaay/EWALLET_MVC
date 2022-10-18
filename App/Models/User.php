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

    public $errors = [];

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function save()
    {

        $this->validate();

        if(empty($this->errors)){
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users VALUES
            (NULL, :login, :password_hash, :email, :name)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
    
            $stmt->bindValue(':login',$this->login,PDO::PARAM_STR);
            $stmt->bindValue(':password_hash',$password_hash,PDO::PARAM_STR);
            $stmt->bindValue(':email',$this->email,PDO::PARAM_STR);
            $stmt->bindValue(':name',$this->name,PDO::PARAM_STR);
    
            return $stmt->execute();
        }
        return false;
    }

    public function validate()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors['invalidEmailAddress'] = 'Invalid email address!';
        }
        if (static::emailExists($this->email)) {
            $this->errors['emailAlreadyTaken'] = 'Email already taken!';
        }
        if (strlen($this->password) < 6) {
            $this->errors['lengthPasswordError'] = 'Password need at least 6 characters';
        }
        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors['atLeastOneLetterInPassword'] = 'Password must contain at least one letter';
        }
        if (ctype_alnum($this->password) === false) {
            $this->errors['specialCharactersInPassword'] = 'Special characters not allowed!';
        }
        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors['atLeastOneNumberInPassword'] = 'Password must contain at least one number';
        }
        if (!ctype_alnum($this->login)) {
            $this->errors['specialCharactersInLogin'] = 'Login can only consists of letters and numbers';
        }
        if (preg_match('/\d+/i', $this->name) !== 0 || ctype_alnum($this->name) === false) {
            $this->errors['onlyLettersInName'] = 'Name can only consists of letters';
        }
    }

    public static function emailExists($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch() !== false;
    }

}
