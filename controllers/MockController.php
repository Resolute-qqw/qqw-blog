<?php
namespace controllers;
use PDO;

class MockController{

    function blogs(){
        
        $pdo = new PDO("mysql:host=localhost;dbname=threelianxi",'root','142560');
        $pdo->exec('set names utf8');

        $pdo->exec('TRUNCATE blogs');
        
       for($i=0;$i<100;$i++){
            $title = $this->getChar(rand(8,20));
            $content = $this->getChar(rand(15,30));
            $is_show = rand(1,2);
            $display = rand(1,5000);
            $date = date("Y-m-d H:i:s",rand(1233333399,1535592288));
            $user_id = rand(1,10);
            $pdo->exec("INSERT INTO blogs (title,content,is_show,display,created_at,updated_at,user_id) VALUES('$title','$content','$is_show','$display','$date','$date','$user_id')");
            
        }
    }
    function users(){
        $pdo = new PDO("mysql:host=localhost;dbname=threelianxi",'root','142560');
        $pdo->exec('set names utf8');

        $pdo->exec('TRUNCATE users');

        for($i=0;$i<5;$i++){
            $email = rand(10000,999999999)."@qq.com";
            $password = md5('142560');
            
            $pdo->exec("INSERT INTO users (email,password) VALUES('$email','$password')");
            
        }
    }

    private function getChar($num)  // $num为生成汉字的数量
    {
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }
}
