<?php

/**
 * Class MongoDriver
 */
class MongoDriver
{
    /**
     * @var
     */
    static $instance;
    /**
     * @var
     */
    static $dbHost;
    /**
     * @var
     */
    static $dbName;
    /**
     * @var
     */
    static $dbUser;
    /**
     * @var
     */
    static $dbPass;
    /**
     * @var
     */
    public $db;
    /**
     * @var
     */
    public $con;

    /**
     * @return MongoDriver
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            $instance = new MongoDriver();

            if (!self::$dbUser) {
                $instance->con = new MongoClient("mongodb://" . MongoDriver::$dbHost);
            } else {
                $instance->con = new MongoClient("mongodb://" . MongoDriver::$dbUser . ":" . MongoDriver::$dbPass . "@" . MongoDriver::$dbHost);
            }
            if ($instance->con) {
                $instance->db = $instance->con->selectDB(MongoDriver::$dbName);
            } else {
                die("database error");
            }

            self::$instance = $instance;
        }

        return self::$instance;
    }

    /**
     *
     */
    function __destruct()
    {
        $this->con->close();
    }
}
