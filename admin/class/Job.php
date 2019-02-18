<?php

class Job
{
    private const TABLE_NAME = 'jobs';

    private $job_id;
    private $name;

    static private $jobs;
    static private $ar_name_by_id;

    public function getId()
    {
        return $this->job_id;
    }

    public function getName()
    {
        return $this->name;
    }

    static function all()
    {
        $db = DB::link();
        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY job_id');

        if (!$result) {
            return [];
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function get_name_by_id()
    {
        if (!self::$ar_name_by_id) {
            self::$jobs = self::all();

            foreach (self::$jobs as $job) {
                self::$ar_name_by_id[$job['job_id']] = $job['name'];
            }
        }
        return self::$ar_name_by_id;
    }
}