<?php
namespace controllers;
use models\User;
use models\Order;

class UserController{

    function register(){
        view("user.register");
    }
    function login(){
        view("user.login");
    }
    function charge(){
        view("user.charge");
    }
    function store(){
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $user = new User;
        $stmt = $user ->adduser($email,$password);
    }
    function active(){
        $code = $_GET['code'];

        $redis = \libs\Redis::getInstance();
        $key = "email_user:{$code}";
        
        $data = $redis->get($key);
        if($data){
            $redis->del($key);
             
            
            $data = json_decode($data,TRUE);
            
            $user = new \models\User;
            $stmt = $user->adduser($data['email'],$data['password']);
            
            jump('/blog/index');
        }else{
            die("错误");
        }
    }
    function land(){
        $user = $_POST['user'];
        $pwd = md5($_POST['password']);
        
        $users = new User;
        $stmt = $users->tologin($user,$pwd);
        if($stmt){
            message("登陆成功啦~~",3,'/blog/index');
        }else{
            message("登陆失败,账号或密码错误",3,'/user/login');
        }
    }
    function logout(){
        $_SESSION = [];
        header("Location:/blog/index");
    }

    function docharge(){
        $money = $_POST['money'];
        
        $order = new Order;
        $order->create($money);
        message("充值订单打印成功,请及时确认提交!",3,'/user/orders');
    }

    function orders(){
        
        $orders = new Order;
        $data = $orders->orderslist();
        
        view("user.order",$data);
    }

    function updateMoney(){
        $users = new User;
        echo $users->upMoney();
    }
}
