<?php
namespace controllers;
use libs\Mail;

class MailController{
    public function send(){

        $redis = \libs\Redis::getInstance();
            
        $mail = new Mail;

        ini_set('default_socket_timeout', -1);

        echo "队列启动..等待中..\r\n";
        while(true){
            $data = $redis->brpop('email', 0);
            $userdata = json_decode($data[1],true);
            
            $mail->send($userdata['title'],$userdata['content'],$userdata['from']);
            
            echo "发送成功~~继续等待操作中。。。。\r\n";
            $log = new \libs\Log('email');
            $log->log("执行发送邮件任务....\r\n");
        }

    }

}
