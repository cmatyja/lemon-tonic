<?php

class User
{
    private const TABLE_NAME = 'users';

    private $user_id;
    private $lastname;
    private $firstname;
    private $password;
    private $birthday;
    private $email;
    private $sex;
    private $job_id;
    private $country_id;
    private $region_id;
    private $role;
    private $created_at;

    function create(): bool
    {
        $db = DB::link();
        $result = $db->exec(
            'INSERT INTO ' . self::TABLE_NAME
            . ' (lastname, firstname, password, birthday, email, sex, job_id, country_id, region_id, role)'
            . ' VALUES (' . $db->quote($this->lastname)
            . ',' . $db->quote($this->firstname)
            . ',' . $db->quote($this->password)
            . ',' . $db->quote($this->birthday)
            . ',' . $db->quote($this->email)
            . ',' . $db->quote($this->sex)
            . ',' . $db->quote($this->job_id)
            . ',' . $db->quote($this->country_id)
            . ',' . $db->quote($this->region_id)
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
        $user = self::readByID($ar_update['user_id']);
        $update_values = [];
        foreach ($ar_update as $key => $value) {
            if ($key === 'country' || $key === 'region') {
                $key .= '_id';

            } elseif ($key === 'work') {
                $key = 'job_id';
            } elseif ($key === 'birthday') {
                $user->{$key} = date('Y-m-d', strtotime($user->birthday));
            }
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
        if (empty($sql_string)) {
            return false;
        } else {
            return $db->exec('UPDATE ' . self::TABLE_NAME . ' SET ' . $sql_string . ' WHERE user_id=' . $ar_update['user_id']);
        }
    }

    function delete($key): bool
    {
        if (empty($this->user_id)) {
            return false;
        }
        $db = DB::link();
        $user_key = self::readByID($this->user_id);
        if (md5($user_key->created_at) === $key) {
            return $db->exec('DELETE FROM ' . self::TABLE_NAME . ' WHERE user_id=' . intval($this->user_id));
        }
        return false;
    }

    static function readByID($user_id): User
    {
        $db = DB::link();
        $query = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE user_id=' . $user_id);
        return $query->fetchObject(__CLASS__);
    }

    static function checkByEmail($email): bool
    {
        $db = DB::link();
        $nb = $db->query('SELECT user_id FROM ' . self::TABLE_NAME . ' WHERE email=' . $db->quote($email));
        return ($nb->rowCount() > 0);
    }

    static function all()
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY country_id');

        if (!$result) {
            return [];
        }
        return $result->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    static function verif_password($email, $password): bool
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE email=' . $db->quote($email));

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

    static function verif_admin(): bool
    {
        try {
            if (!session_start()) {
                throw new Exception('Impossible d\'ouvrir une session');
            }
            if (empty($_SESSION['admin']) || !$_SESSION['admin']) {
                throw new Exception('Vous n\'avez pas accès');
            }
            return true;
        } catch (Exception $ex) {
            Email::table_to_webmaster(['exception' => $ex]);
            return false;
        }
    }

    static function is_granted()
    {
        if (!self::verif_admin()) {
            header('location: login.php');
            exit();
        }
    }

    static function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        unset($_SESSION['admin']);
        unset($_SESSION['user']);
        session_destroy();
    }

    static function is_admin($user_email): bool
    {
        $db = new DB;
        $result = $db->query('SELECT role FROM ' . self::TABLE_NAME . ' WHERE email=' . $db->quote($user_email));
        $role = $result->fetch(PDO::FETCH_OBJ);
        if (intval($role->role) === 1) {
            return true;
        }
        return false;
    }

    static function email_valid($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    static function birthday_valid($date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }

    public
    function getUserId()
    {
        return $this->user_id;
    }

    public
    function setUserId($id): void
    {
        $this->user_id = $id;
    }

    public
    function getLastname()
    {
        return $this->lastname;
    }

    public
    function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    public
    function getFirstname()
    {
        return $this->firstname;
    }

    public
    function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /*
    public function getPassword()
    {
        return $this->password;
    }*/

    public
    function setPassword($password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public
    function getBirthday()
    {
        return $this->birthday;
    }

    public
    function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    public
    function getEmail()
    {
        return $this->email;
    }

    public
    function setEmail($email): void
    {
        $this->email = $email;
    }

    public
    function getSex()
    {
        return $this->sex;
    }

    public
    function setSex($sex): void
    {
        $this->sex = $sex;
    }

    public
    function getJobId()
    {
        return $this->job_id;
    }

    public
    function setJobId($job_id): void
    {
        $this->job_id = $job_id;
    }

    public
    function getCountryId()
    {
        return $this->country_id;
    }

    public
    function setCountryId($country_id): void
    {
        $this->country_id = $country_id;
    }

    public
    function getRegionId()
    {
        return $this->region_id;
    }

    public
    function setRegionId($region_id): void
    {
        $this->region_id = $region_id;
    }

    public
    function getRole()
    {
        return $this->role;
    }

    public
    function setRole($role): void
    {
        $this->role = $role;
    }

    public
    function getCreatedAt()
    {
        return $this->created_at;

    }
}