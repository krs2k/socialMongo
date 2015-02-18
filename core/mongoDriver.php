<?php
class MongoDriver
{
    static $instance;
    static $dbHost;
    static $dbName;
    static $dbUser;
    static $dbPass;
    public $db;
    public $con;

    public static function getInstance()
    {
        if(self::$instance === null)
        {
            $instance = new MongoDriver();
            
            if (!self::$dbUser)
                $instance->con = new MongoClient("mongodb://". MongoDriver::$dbHost);
            else
                $instance->con = new MongoClient("mongodb://". MongoDriver::$dbUser .":". MongoDriver::$dbPass ."@". MongoDriver::$dbHost);
            
            $instance->db = $instance->con->selectDB( MongoDriver::$dbName );

            self::$instance = $instance;
        }
        return self::$instance;
    }
    function __destruct()
    {
        $this->con->close();
    }
}
?>