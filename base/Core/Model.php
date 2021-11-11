<?php


namespace Core;

class Model
{
    public function __construct()
    {
        self::loadLibs();
        self::db();
    }

    private static function db()
    {
        $config = require(ROOT . "/config/db.php");

        if ($config['enable']){
            \R::setup( 'mysql:host='. $config['host'] .';port='. $config['port'] .';dbname='. $config['db'],
                $config['username'], $config['password']);

            if (!\R::testConnection())
                die('Error db connect');
        }
    }

    private function loadLibs()
    {
        $config = require_once(ROOT . "/config/App.php");

        foreach ($config["libs"] as $lib) {
            require_once(BASE . "/libs/" . $lib . ".php");
        }
    }
}