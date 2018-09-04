<?php
namespace libs;
class Redis{
    private static $redis = null;
    private function __clone(){}
    private function __construct(){}
    static function getInstance(){
        $config = config('redis');
        if(self::$redis===null){
            self::$redis = new \Predis\Client([
                'scheme' => $config['scheme'],
                'host'   => $config['host'],
                'port'   => $config['port'],
            ]);
        }
        return self::$redis;
    }
}