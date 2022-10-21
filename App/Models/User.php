<?php

namespace App\Models;

use PDO;
use App\Token;
use App\Mail;
use \Core\View;


/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function save()
    {

        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token ->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (id, login, password, email, name, activation_hash) VALUES
            (NULL, :login, :password_hash, :email, :name, :activation_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);


            $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function validate()
    {

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors['invalidEmailAddress'] = 'Invalid email address!';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors['emailAlreadyTaken'] = 'Email already taken!';
        }
        if (static::usernameExists($this->login, $this->id ?? null)) {
            $this->errors['loginExists'] = 'Login already taken!';
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

    public static function emailExists($email, $ignore_id = null)
    {

        $user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    public static function usernameExists($username, $ignore_id = null)
    {
        $user = static::findByUsername($username);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    public static function findByUsername($username)
    {
        $sql = 'SELECT * FROM users WHERE login = :username';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public function rememberLogin()
    {

        $token = new Token();
        $hashed_token = $token->getHash();

        $this->remember_token = $token->getValue();

        $this->expire_timestamp = time() + 60 * 60 * 24 * 30;


        $sql = 'INSERT INTO remembered_logins VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expire_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();
            }
        }
    }

    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expire_timestamp = time() + 60 * 60 * 2;

        $sql = 'Update users
            SET password_reset_hash = :token_hash,
            password_reset_expiry = :expires_at
            WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expire_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $message = View::getTemplate('Password/resetemail.html', ['url' => $url]);

        Mail::send($this->email, 'Password recovery', $message);
    }

    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {

            if (strtotime($user->password_reset_expiry) > time()) {
                return $user;
            }
        }
    }

    public function resetPassword($password)
    {
        $this->password = $password;

        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expiry = NULL
                    WHERE id = :id';

            $db = static::getDB();

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/register/activate/' . $this->activation_token;

        $message = View::getTemplate('Register/activationemail.html', ['url' => $url]);

        Mail::send($this->email, 'Activate account', $message);
    }
}
