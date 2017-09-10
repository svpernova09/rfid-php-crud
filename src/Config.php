<?php
/**
 * Created by JetBrains PhpStorm.
 * User: halo
 * Date: 7/1/13
 * Time: 2:48 PM
 *
 */

namespace App;

use App\RfidClient;
use PDO;
use PDOException;

abstract class Config
{
    private static $ext_conn = null;
    private static $database_path;
    private static $database_name;

    public $client;
    public $log_path;
    public $log_name;
    public $rfid_client;
    public $debug;


    public function __construct()
    {
        include __DIR__.'/../config/config.php';

        $this->debug = 1;
        $this->log_path = $log_path;
        $this->log_name = $log_name;
        $this->client = new RfidClient($token);
    }

    private static function init()
    {
        include __DIR__.'/../config/config.php';

        try
        {
            self::$ext_conn = new PDO('sqlite:'.$database_path.$database_name);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public static function getDBConnection()
    {
        if (!self::$ext_conn) self::init();
        return self::$ext_conn;
    }
}

?>