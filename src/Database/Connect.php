<?php

namespace App\Database;


class Connect {
    /**
     *
     * @var \PDO
     */
    protected static $instance;



    public static function getInstance()
    {
        if (empty(self::$instance)) {
            // NOrmalnie dane połączenia z DB nie będą w pliku
            $db_info = array(
                "db_host"    => 'mysql',
                "db_port"    => "3306",
                "db_user"    => 'adm2',
                "db_pass"    => 'adm2',
                "db_name"    => 'zadanko',
                "db_charset" => "UTF-8");

            try {
                self::$instance = new \PDO("mysql:host=".$db_info['db_host'].';port='.$db_info['db_port'].';dbname='.$db_info['db_name'],
                    $db_info['db_user'], $db_info['db_pass']);
                self::$instance->setAttribute(
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION);
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET CHARACTER SET utf8');
            } catch (\PDOException $error) {
                echo '<center>I can`t connect to database ...</center>';
                echo $error->getMessage();
                exit;
            }
        }
        return self::$instance;
    }

    public static function setCharsetEncoding()
    {
        if (self::$instance == null) {
            self::connect();
        }
        self::$instance->exec(
            "SET NAMES 'utf8';
            SET SESSION sql_mode = '';
            SET character_set_connection=utf8;
            SET character_set_client=utf8;
            SET character_set_results=utf8");
    }

     public static function connectDB()
    {
      $db = self::getInstance();
      self::setCharsetEncoding();
      return $db;
    }

    public function close() {
        self::$instance = null;
        return null;
    }
}
