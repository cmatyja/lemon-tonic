<?php

class Country
{
    private const TABLE_NAME = 'countries';

    private $country_id;
    private $name_FR;

    static private $countries;
    static private $ar_name_by_id;

    public function getId()
    {
        return $this->country_id;
    }

    public function getNameFR()
    {
        return $this->name_FR;
    }


    static function all()
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY name_FR');

        if (!$result) {
            return [];
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function get_name_by_id()
    {
        if (!self::$ar_name_by_id) {
            self::$countries = self::all();

            foreach (self::$countries as $country) {
                self::$ar_name_by_id[$country['country_id']] = $country['name_FR'];
            }
        }
        return self::$ar_name_by_id;
    }
}