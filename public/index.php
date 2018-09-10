<?php
ini_set('session.save_handler', 'redis');   // 使用 redis 保存 SESSION
ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');  // 设置 redis 服务器的地址、端口、使用的数据库
session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(!isset($_POST['token'])){
        die('没有令牌不能搞事~( • ̀ω•́ )✧');
    }else if($_POST['token']!=$_SESSION['token']){
        die('没有令牌不能搞事~( • ̀ω•́ )✧');
    }
}

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
function filter($content){
    return htmlspecialchars($content);
}

function hpf($content){
    static $purifier = null;
    if($purifier==null){
        // 1. 生成配置对象
        $config = \HTMLPurifier_Config::createDefault();
        // 2. 配置
        // 设置编码
        $config->set('Core.Encoding', 'utf-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        // 设置缓存目录
        $config->set('Cache.SerializerPath', ROOT.'cache');
        // 设置允许的 HTML 标签
        $config->set('HTML.Allowed', 'div,b,strong,i,em,a[href|title],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],*[style|class],pre,hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td');
        // 设置允许的 CSS
        $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置是否自动添加 P 标签
        $config->set('AutoFormat.AutoParagraph', TRUE);
        // 设置是否删除空标签
        $config->set('AutoFormat.RemoveEmpty', true);
        // 3. 过滤
        // 创建对象
        $purifier = new \HTMLPurifier($config);  
    }
    // 过滤
    return $purifier->purify($content);
}
function csrf(){
    if(!isset($_SESSION['token'])){
        $_SESSION['token']=md5(rand(1000,99999).microtime());
    }
    return $_SESSION['token'];
}