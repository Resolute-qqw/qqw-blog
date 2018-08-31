<?php
namespace controllers;
use PDO;

class BlogController{

    function index(){
        
        $pdo = new PDO("mysql:host=localhost;dbname=threelianxi",'root','142560');
        $pdo->exec('set names utf8');

        $where = 1;
        $value = [];

        if(isset($_GET['keyword']) && $_GET['keyword']){
            $where .= " AND (title LIKE ? OR content LIKE ?) ";
            $value[] = '%'.$_GET['keyword'].'%';
            $value[] = '%'.$_GET['keyword'].'%';
        }
        // 日期
        if(isset($_GET['start_date']) && $_GET['start_date']){
            $where .= " AND created_at >=?  ";
            $value[] = $_GET['start_date'];
        }
        if(isset($_GET['end_date']) && $_GET['end_date']){
            $where .= " AND created_at <=?  ";
            $value[] = $_GET['end_date'];
        }
        
        # ********翻页S**********
        $perpage=10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $pageon = ($page-1)*$perpage;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);

        $pageCount = ceil($count/$perpage);

        $pagebtns = '';

        for($i=1;$i<=$pageCount;$i++){
            $class = ($i==$page)? 'active' : '';
            $pagebtns .= "<a href='?page={$i}' class={$class} apage> $i </a>";
        }
        # ********翻页E***********

        $stmt = $pdo->prepare("SELECT * FROM blogs where {$where} limit $pageon,$perpage");
        $stmt->execute($value);
        $data = $stmt->fetchall(PDO::FETCH_ASSOC);

        view("blogs.index",[
            'data'=>$data,
            'pagebtns'=>$pagebtns,
        ]);
    }
}
