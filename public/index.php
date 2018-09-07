<?php
ini_set('session.save_handler', 'redis');   // 使用 redis 保存 SESSION
ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');  // 设置 redis 服务器的地址、端口、使用的数据库
session_start();

define("ROOT",dirname(__FILE__)."/../");   #配置常量为根目录地址
require(ROOT."vendor/autoload.php");
#*****注册自动加载类函数****** 
function autoload($class){
    require_once ROOT . str_replace("\\","/",$class).".php";

}
spl_autoload_register('autoload');
#*****END****** 
function config($name){
    static $config=null;
    if($config===null){
        $config = require ROOT.'config.php';
    }
    return $config[$name];
}
function view($file,$data=[]){
    if($data){
        extract($data);
    }

    require ROOT.'views/'.str_replace(".","/",$file).'.html';
}

// var_dump($_SERVER);
// 添加路由 ：解析 URL 浏览器上 blog/index  CLI中就是 blog index

if(php_sapi_name() == 'cli')
{
    $controller = ucfirst($argv[1]) . 'Controller';
    $action = $argv[2];
}
else
{
    if( isset($_SERVER['PATH_INFO']) )
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        // 根据 / 转成数组
        $pathInfo = explode('/', $pathInfo);

        // 得到控制器名和方法名 ：
        $controller = ucfirst($pathInfo[1]) . 'Controller';
        $action = $pathInfo[2];
    }
    else
    {
        // 默认控制器和方法
        $controller = 'IndexController';
        $action = 'index';
    }
}


// 为控制器添加命名空间
$fullController = 'controllers\\'.$controller;


$_Con = new $fullController;
$_Con->$action();

function getUrl($c=[]){
    foreach($c as $v){
        unset($_GET[$v]);
    }
    $cstr = '';
    foreach($_GET as $k=>$v){
        $cstr .= "$k=$v&";    
    }
    return $cstr;
}

function jump($url){
    header('Location:'.$url);
}
function back(){
    jump($_SERVER['HTTP_REFERER']);
}

function message($message,$type,$url,$time=5){
    if($type==1){
        echo "
        <script>
            alert('$message');
            location.href='{$url}'
        </script>";
    }
    else if($type==2){
        view("common.success",[
            'message'=>$message,
            'url'=>$url,
            'time'=>$time
        ]);
    }
    else if($type==3){
        $_SESSION['_message_']=$message;
        jump($url);
    }
   
}