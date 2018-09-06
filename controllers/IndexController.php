<?php
namespace controllers;
use models\Blog;

class IndexController{
    function index(){
        
        $blog = new Blog;
        $data = $blog ->indexlist();
        view('index.index',[
            'blogs'=>$data,
        ]);
    } 
}
