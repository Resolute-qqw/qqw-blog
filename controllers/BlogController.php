<?php
namespace controllers;
use models\Blog;
use PDO;

class BlogController{

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
}
