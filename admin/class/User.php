<?php

class User
{
    private const TABLE_NAME = 'user';

    private $user_id;
    private $lastname;
    private $fisrtname;
    private $email;
    private $login;
    private $password;
    private $role;

    function create(): bool
    {
        if (empty($this->login) || empty($this->password)) {
            return false;
        }
        $db = DB::link();
        $result = $db->exec(
            'INSERT INTO ' . self::TABLE_NAME
            . ' (email, login, password, role)'
            . ' VALUES (' . $db->quote($this->email)
            . ',' . $db->quote($this->login)
            . ',' . $db->quote(password_hash($this->password, PASSWORD_DEFAULT))
            . ',' . $db->quote($this->role)
            . ')'
        );

        if ($result) {
            $this->user_id = $db->lastInsertId();
        }
        return $result;
    }

    function update($ar_update): bool
    {
        $user = self::read($ar_update['user_id']);
        $update_values = [];
        foreach ($ar_update as $key => $value) {
            if ($user->{$key} !== $value) {
                $key === 'password' ? $update_values[$key] = password_hash($value, PASSWORD_DEFAULT) : $update_values[$key] = $value;
            }
        }

        $db = DB::link();
        $sql_string = '';
        foreach ($update_values as $k => $v) {
            $sql_string .= $k . '=' . (is_numeric($v) ? $v : htmlentities($db->quote($v))) . ',';
        }
        $sql_string = substr($sql_string, 0, -1);
        return $db->exec('UPDATE ' . self::TABLE_NAME . ' SET ' . $sql_string . ' WHERE user_id=' . $update_values['user_id']);
    }

    function delete(): bool
    {
        if (empty($this->user_id)) {
            return false;
        }
        $db = DB::link();
        return $db->exec('DELETE FROM ' . self::TABLE_NAME . ' WHERE user_id=' . intval($this->user_id));
    }

    static function read($user_id): User
    {
        $db = DB::link();
        $query = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE user_id=' . $user_id);
        return $query->fetchObject(__CLASS__);
    }

    static function all()
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME);

        if (!$result) {
            return [];
        }

        return $result->fetchObject('User');
    }

    static function verif_password($login, $password): bool
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE login=' . $db->quote($login));
        if (!$result) {
            return false;
        }
        $user = $result->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            return false;
        } else if (session_start()) {
            $_SESSION['user'] = $user;
        }
        return password_verify($password, $user->password);
    }

    static function verif_logged(): bool
    {
        try {
            if (!session_start()) {
                throw new Exception('Impossible d\'ouvrir une session');
            }
            if (empty($_SESSION['user'])) {
                throw new Exception('Aucun utilisateur dans la session');
            }
            if (empty($_SESSION['user']->user_id && is_numeric($_SESSION['user']->user_id))) {
                throw new Exception('User_id inexistant ou n\'est pas un nombre');
            }
            return true;
        } catch (Exception $ex) {
            Email::table_to_webmaster(['exception' => $ex]);
            return false;
        }
    }

    static function is_granted()
    {
        if (!self::verif_logged()) {
            header('location: login.php');
            exit();
        }
    }

    static function is_admin($user_login): bool
    {
        $db = new DB;
        $result = $db->query('SELECT role FROM ' . self::TABLE_NAME . ' WHERE login=' . $db->quote($user_login));
        $role = $result->fetch(PDO::FETCH_OBJ);
        if (intval($role->role) === 1) {
            return true;
        }
        return false;
    }

    static function username_valid($username): bool
    {
        $regex = '/^[A-Za-z0-9]_\-]+$/';
        return (preg_match($regex, $username) && empty(trim($username)));
    }

    static function email_valid($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}