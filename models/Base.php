<?php
namespace models;
use PDO;

class Base{
    public static $pdo=null;
    function __construct(){
        $config = config('db');
        if(self::$pdo===null){
            self::$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}",$config['user'],$config['pass']);
            self::$pdo->exec("set names {$config['charset']}");
        }
    }

    function startTrans(){
        self::$pdo->exec('start transaction');
    }
    function commit(){
        self::$pdo->exec("commit");
    }
    function rollback(){
        self::$pdo->exec("rollback");
    }
}