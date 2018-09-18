<?php
namespace controllers;
use models\User;
use models\Order;
use Intervention\Image\ImageManagerStatic as Image;

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
    function faca(){
        view("user.setFace");
    }
    function upload(){
        
        $upload = \libs\Upload::make();
        $path = $upload->upload('image', 'avatar');

        $image = Image::make(ROOT."public/uploads/".$path);
        $image->crop((int)$_POST['w'],(int)$_POST['h'],(int)$_POST['x'],(int)$_POST['y']);
        $image->save(ROOT."public/uploads/".$path);

        $user = new User;
        $user->setface("/uploads/".$path);

        @unlink(ROOT."public".$_SESSION['face']);

        $_SESSION['face']="/uploads/".$path;
        message("设置成功",3,"/blog/index");
    }
    function batch(){
        view("user.batchUpload");
    }
    function batchUpload(){

        $uploadDir = ROOT."public/uploads";
        $data = date("Ymd");
        if(!is_dir($uploadDir."/".$data)){
            mkdir($uploadDir."/".$data);
        }

        foreach($_FILES['images']['name'] as $k=>$v){
            $ext = strrchr($v,".");
            $name = md5(time().rand(1,9999));
            $pathEnd = $uploadDir."/".$data."/".$name.$ext;
            
            move_uploaded_file($_FILES['images']['tmp_name'][$k],$pathEnd);
            
        }
        
    }
    function resource(){
        view("user.resource");
    }
    function uploadbig(){

        $img = $_FILES['img'];
        $i = $_POST['i'];
        $size = $_POST['size'];
        $count = $_POST['count'];
        $name = "big_".$_POST['img_name'];

        move_uploaded_file($img['tmp_name'],ROOT."tmp/".$i);

        $redis = \libs\Redis::getInstance();
        $uploadedCount = $redis->incr($name);
        
        if($count ==$uploadedCount){
            $fp = fopen(ROOT.'public/uploads/big/'.$name.'.png', 'a');
            for($i=0;$i<$count;$i++){
                fwrite($fp, file_get_contents(ROOT.'tmp/'.$i));
                unlink(ROOT."tmp/".$i);
            }
            fclose($fp);
            $redis->del($name);
        }
    }

    function gd(){
        
        $image->insert(ROOT."public/uploads/20180913/2eb751bab5cf35eeccd2f2b6f635cfc8.jpg",'centent');
        $image->save(ROOT."public/uploads/wirte.png");
    }
}
