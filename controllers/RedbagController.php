<?php
namespace controllers;

class RedbagController
{
    function init(){
        $redis = \libs\Redis::getInstance();
        $redis->set('Redbag_Stock',20);
        $key ='redbag_'.date('Ymd');
        $redis->sadd($key,-1);
        $redis->expire($key,3900);
    }

    function makeOrder(){
        $redis = \libs\Redis::getInstance();
        $redbag = new \models\Redbag;
        ini_set('default_socket_timeout', -1);

        echo "进程开启";

        while(1){
            $data = $redis->brpop('redbag',0);

            $redbag->add($data[1]);
            echo "添加成功!";
        }
    }

    function rob_view(){
        view("redbag.rob");
    }

    function rob(){
        
        if(!isset($_SESSION['id'])){
            echo json_encode([
                'status_code'=>'401',
                'message'=>"未登录~"
            ]);
            exit;
        }

        if(date('H')<9 || date('H')>20){
            echo json_encode([
                'status_code'=>'401',
                'message'=>"抢红包的时间没到~"
            ]);
            exit;
        }

        $redis = \libs\Redis::getInstance();
        $key ='redbag_'.date('Ymd');
        $status = $redis->sismember($key,$_SESSION['id']);
        if($status){
            echo json_encode([
                'status_code'=>'401',
                'message'=>"已经抢过红包了~"
            ]);
            exit;
        }

        $stock = $redis->decr('Redbag_Stock');
        if($stock<=0){
            echo json_encode([
                'status_code'=>'401',
                'message'=>"红包已经被抢完了~"
            ]);
            exit;
        }

        $redis->lpush("redbag",$_SESSION['id']);
        $redis->sadd($key,$_SESSION['id']);
        echo json_encode([
            'status_code'=>'200',
            'message'=>"抢到红包了~"
        ]);
        exit;
    }
}