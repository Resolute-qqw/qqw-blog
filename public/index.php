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