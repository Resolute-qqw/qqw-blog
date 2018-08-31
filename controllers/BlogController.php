<?php
namespace controllers;
use models\Blog;

class BlogController{

    function index(){
        
        $blogs = new Blog;
        $data = $blogs->blogslist();

        view("blogs.index",$data);
    }
}
