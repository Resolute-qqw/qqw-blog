<?php
namespace controllers;
use models\Blog;
use PDO;

class BlogController{

    function create(){
        view("blogs.create");
    }

    function index(){
        
        $blogs = new Blog;
        $data = $blogs->blogslist();

        view("blogs.index",$data);
    }

    function to_content(){
        $blogs = new Blog;
        $blogs->content_to_html();
    }

    function display(){
        $id = (int)$_GET['id']; 
        $blogs = new Blog;
        $display=  $blogs->getDisplay($id);
        echo json_encode([
            'display'=>$display,
            'email'=>isset($_SESSION['email'])?$_SESSION['email']:'',
        ]);
    }
    function updisplay(){
        $blogs = new Blog;
        $blogs->updisplay();
        back();
    }
    function addblog(){
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];

        $blogs = new Blog;
        $id = $blogs->add($title,$content,$is_show);
        if($id){
            if($is_show==1){
                $blogs->makeHtml($id);
            }
            message("发布成功!~~",3,"/blog/index");
        }else{
            message("发布失败!~~",1,"/blog/create");
        }
    }
    function delete(){
        $id = $_POST['id'];
        $blogs = new Blog;
        $res = $blogs->del($id);
        if($res){
            $blogs->deleteHtml($id);
            message("删除成功!",3,"/blog/index");
        }
        
    }
    function modify(){
        $id = $_GET['id'];
        $blogs = new Blog;
        $data = $blogs->find($id);
        view("blogs.edit",[
            'data'=>$data
        ]);
    }
    function toedit(){
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];

        $id = $_POST['id'];
        $blogs = new Blog;
        $res = $blogs->edit($title,$content,$is_show,$id);
        if($res){
            if($is_show==1){
                $blogs->makeHtml($id);
            }else{
                $blogs->deleteHtml($id);
            }
            message("修改成功!~~",3,"/blog/index");
        }else{
            message("修改失败!~~",1,"/blog/create");
        }
    }

    function content(){
        $id = $_GET['id'];
        $blogs = new Blog;
        $data = $blogs->find($id);
        if($data['user_id']!=$_SESSION['id'])
        die("无权限访问!");
        if($data){
            view("blogs.content",[
                'blog'=>$data,
            ]);
        }else{
            message("访问错误!~~",1,"/blog/index");
        }
    }
}
