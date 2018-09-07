<?php
namespace models;
use PDO;

class Blog extends Base{

    function blogslist(){
        
        if(!isset($_SESSION['id'])){
            $where = 'false';
        }else{
            $where = 'user_id='.$_SESSION['id'];
        }
        
        
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

        # ********排序S********** 
        $odby = 'created_at';
        $odway = 'desc';
        if(isset($_GET['odby'])&&$_GET['odby']=='display'){
            $odby = 'display';
        }
        if(isset($_GET['odway'])&&$_GET['odway']=='asc'){
            $odway = 'asc';
        }
        # ********排序E********** 

        # ********翻页S**********
        $perpage=10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $pageon = ($page-1)*$perpage;
    
        $stmt = self::$pdo->prepare("SELECT COUNT(*) FROM blogs where $where");
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
    
        $stmt = self::$pdo->prepare("SELECT * FROM blogs where $where ORDER BY $odby $odway limit $pageon,$perpage");
        $stmt->execute($value);
        $data = $stmt->fetchall(PDO::FETCH_ASSOC);

        return [
            'data'=>$data,
            'pagebtns'=>$pagebtns
        ];
    }

    function indexlist(){
        $stmt = self::$pdo->query('SELECT * FROM blogs LIMIT 15');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    function content_to_html(){
        $stmt = self::$pdo->query('SELECT * FROM blogs');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();

        foreach($data as $v){
            view('blogs.content',[
                'blog'=>$v
            ]);

            $str = ob_get_contents();
            
            file_put_contents(ROOT.'public/contents/'.$v['id'].'.html',$str);
            ob_clean();
        }
    }

    function getDisplay($id){
        
        $key = "blog-{$id}";
        $redis = \libs\Redis::getInstance();
        if($redis->hexists('blog_displays',$key)){
            $newNum = $redis->hincrby('blog_displays',$key,1);
            return $newNum;
        }else{
            
            $stmt = self::$pdo->prepare('SELECT display FROM blogs WHERE id = ?');
            $stmt->execute([$id]);
            $display = $stmt->fetch(PDO::FETCH_COLUMN);
            $display++;
            $redis->hset('blog_displays',$key,$display);
            return $display;
        }

    }
    function updisplay(){
        $redis = \libs\Redis::getInstance();
        $data = $redis->hgetall('blog_displays');
        
        foreach($data as $k=>$v){
            $id = str_replace("blog-","",$k);
            
            $sql = "UPDATE blogs SET display={$v} where id={$id}";
            self::$pdo->exec($sql);
        }
        
    }

    function add($title,$content,$is_show){
        $stmt = self::$pdo->prepare("INSERT INTO blogs(title,content,is_show,user_id) VALUE(?,?,?,?)");
        $res = $stmt->execute([
            $title,
            $content,
            $is_show,
            $_SESSION['id'],
        ]);
        if($res){
            message("发布成功!~~",3,"/blog/index");
        }else{
            message("发布失败!~~",1,"/blog/create");
        }

    }
    function del($id){
        $stmt = self::$pdo->prepare("DELETE FROM blogs WHERE id=?");
        $stmt->execute([$id]);
        message("删除成功!",3,"/blog/index");
    }

    function edit($title,$content,$is_show,$id){
        $stmt = self::$pdo->prepare("UPDATE blogs SET title=?,content=?,is_show=? WHERE id=?");
        $res = $stmt->execute([
            $title,
            $content,
            $is_show,
            $id
        ]);
        if($res){
            message("修改成功!~~",3,"/blog/index");
        }else{
            message("修改失败!~~",1,"/blog/create");
        }
    }

    function find($id){
        $stmt = self::$pdo->prepare("SELECT * FROM blogs WHERE id=?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
} 
    