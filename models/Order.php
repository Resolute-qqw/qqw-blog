<?php
namespace models;
use PDO;

class Order extends Base{
    function create($money){
        
        $flake = new \libs\Snowflake(1023);

        $stmt=self::$pdo->prepare("INSERT INTO orders(user_id,sn,money) VALUES(?,?,?)");
        $stmt->execute([
            $_SESSION['id'],
            $flake->nextId(),
            $money
        ]);
      
    }

    function orderslist(){
        
        if(!isset($_SESSION['id'])){
            $where = 'false';
        }else{
            $where = 'user_id='.$_SESSION['id'];
        }
        
        # ********排序S********** 
        $odby = 'created_at';
        $odway = 'desc';
        # ********排序E********** 

        # ********翻页S**********
        $perpage=10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $pageon = ($page-1)*$perpage;
    
        $stmt = self::$pdo->prepare("SELECT COUNT(*) FROM orders where $where");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);
    
        $pageCount = ceil($count/$perpage);
    
        $pagebtns = '';
    
        for($i=1;$i<=$pageCount;$i++){
            $cstr = getUrl(['page']);
            $class = ($i==$page)? 'active' : '';
            $pagebtns .= "<a href='?{$cstr}page=$i' class={$class} apage> $i </a>";
        }
        # ********翻页E***********
    
        $stmt = self::$pdo->prepare("SELECT * FROM orders where $where ORDER BY $odby $odway limit $pageon,$perpage");
        $stmt->execute();
        
        $data = $stmt->fetchall(PDO::FETCH_ASSOC);

        return [
            'data'=>$data,
            'pagebtns'=>$pagebtns
        ];
    }

    function findBysn($sn){
        $stmt = self::$pdo->prepare("SELECT * FROM orders WHERE sn=?");
        $stmt->execute([$sn]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    function upOrders($sn){
        $stmt = self::$pdo->prepare("UPDATE orders SET status=1,pay_time=now() where sn=?");
        return $stmt->execute([$sn]);
    }
}