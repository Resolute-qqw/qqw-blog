<?php
define("ROOT",dirname(__FILE__)."/../");   #配置常量为根目录地址

#*****注册自动加载类函数****** 
function autoload($class){
    require_once ROOT . str_replace("\\","/",$class).".php";

}
spl_autoload_register('autoload');
#*****END****** 

function view($file,$data=[]){
    if($data){
        extract($data);
    }

    require_once ROOT.'views/'.str_replace(".","/",$file).'.html';
}

// var_dump($_SERVER);
function route(){
    $url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/' ;

    $defaultcontroller = 'IndexController';
    $defaultaction = 'index';
    
    if($url=='/'){
       return [
        $defaultcontroller,
        $defaultaction
       ];
    }else if(strpos($url,'/',1)!==false){
        $url = ltrim($url,'/');
        $route = explode('/',$url);
        $route[0] = ucfirst($route[0]).'Controller';
        return $route;
    }else{
        die("请求格式不正确");
    }

}
$route = route();

$controller = "controllers\\{$route[0]}";
$action = $route[1];

$_Con = new $controller;
$_Con->$action();
