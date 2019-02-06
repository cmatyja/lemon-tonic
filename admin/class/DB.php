<?php

class DB extends PDO
{
    private const BD_TYPE = "mysql";
    private const BD_NAME = "lemon-interactive";
    private const BD_HOST = 'localhost';
    private const USER_NAME = 'root';
    private const USER_PASSWORD = 'root';
    private static $instance = false;

    function __construct()
    {
        parent::__construct(self::BD_TYPE
            . ':dbname=' . self::BD_NAME
            . ';host=' . self::BD_HOST,
            self::USER_NAME,
            self::USER_PASSWORD,[
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"',
                PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_CLASS
            ]);
    }

    static function link()
    {
        if (!self::$instance) {
            self::$instance = new DB;
        }
        return self::$instance;
    }

    function query(...$arguments)
    {
        try {
            $result = parent::query(...$arguments);
            if (!$result) {
                throw new Exception('BAD SQL');
            }
            return $result;
        } catch (Exception $ex) {
            mail('c.matyja@gmail.com'
                , 'BUG SQL projet Lemon-Interactive'
                , var_export($ex, true));
            return false;
        }
    }

    function exec($sql)
    {
        try {
            $result = parent::exec($sql);
            if (!$result) {
                throw new Exception('BAD SQL');
            }
            return $result;
        } catch (Exception $ex) {
            mail('c.matyja@gmail.com'
                , 'BUG SQL projet Lemon-Interactive'
                , var_export($ex, true));
            return false;
        }
    }
}