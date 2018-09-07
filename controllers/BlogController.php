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
        echo $blogs->getDisplay($id);
    }
    function updisplay(){
        $blogs = new Blog;
        $blogs->updisplay();
    }
    function addblog(){
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];

        $blogs = new Blog;
        $blogs->add($title,$content,$is_show);
    }
    function delete(){
        $id = $_GET['id'];
        $blogs = new Blog;
        $blogs->del($id);
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
        $blogs->edit($title,$content,$is_show,$id);
    }
}
