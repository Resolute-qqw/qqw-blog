<?php
namespace models;
use PDO;

class Blog{
    
    public $pdo;
    function __construct(){
        $this->pdo = new PDO("mysql:host=localhost;dbname=threelianxi",'root','142560');
        $this->pdo->exec('set names utf8');
    }

    function blogslist(){
        $where = 1;
        $value = [];
    
        if(isset($_GET['keyword']) && $_GET['keyword']){
            $where .= " AND (title LIKE ? OR content LIKE ?) ";
            $value[] = '%'.$_GET['keyword'].'%';
            $value[] = '%'.$_GET['keyword'].'%';
        }
        // 日期
        if(isset($_GET['start_date']) && ($_GET['start_date'])){
            $where .= " AND created_at >=?  ";
            $value[] = $_GET['start_date'];
        }
        if(isset($_GET['end_date']) && $_GET['end_date']){
            $where .= " AND created_at <=?  ";
            $value[] = $_GET['end_date'];
        }
        if(isset($_GET['is_show']) && ($_GET['is_show']=='1'||$_GET['is_show']=='2')){
            $where .= " AND is_show =?  ";
            $value[] = $_GET['is_show'];
        }
    
        # ********翻页S**********
        $perpage=10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $pageon = ($page-1)*$perpage;
    
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM blogs where $where");
        $stmt->execute($value);
        $count = $stmt->fetch(PDO::FETCH_COLUMN);
    
        $pageCount = ceil($count/$perpage);
    
        $pagebtns = '';
    
        for($i=1;$i<=$pageCount;$i++){
            $cstr = getUrl(['page']);
            $class = ($i==$page)? 'active' : '';
            $pagebtns .= "<a href='?{$cstr}page=$i' class={$class} apage> $i </a>";
        }
        # ********翻页E***********
    
        $stmt = $this->pdo->prepare("SELECT * FROM blogs where $where limit $pageon,$perpage");
        $stmt->execute($value);
        $data = $stmt->fetchall(PDO::FETCH_ASSOC);

        return [
            'data'=>$data,
            'pagebtns'=>$pagebtns
        ];
    }
} 
    