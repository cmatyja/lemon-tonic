<?php

class Region
{

    private const TABLE_NAME = 'regions';

    private $region_id;
    private $name;

    static private $regions;
    static private $ar_name_by_id;

    public function getId()
    {
        return $this->region_id;
    }

    public function getName()
    {
        return $this->name;
    }


    static public function all()
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY name');

        if (!$result) {
            return [];
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function get_name_by_id()
    {
        if (!self::$ar_name_by_id) {
            self::$regions = self::all();

            foreach (self::$regions as $region) {
                self::$ar_name_by_id[$region['region_id']] = $region['name'];
            }
        }
        return self::$ar_name_by_id;
    }
}