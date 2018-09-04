<?php
namespace controllers;
use models\User;

class UserController{

    function register(){
        view("user.register");
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
            $user->adduser($data['email'],$data['password']);
            header('Location:/user/index');
        }else{
            die("错误");
        }
    }
}

