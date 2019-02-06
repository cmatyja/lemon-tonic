<?php

class Country
{
    private const TABLE_NAME = 'countries';

    private $id;
    private $name_FR;

    public function getId()
    {
        return $this->id;
    }

    public function getNameFR()
    {
        return $this->name_FR;
    }


    static function AllCountries()
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY name_FR');

        if (!$result) {
            return [];
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}