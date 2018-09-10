<?php
namespace controllers;
class UploadController{
    function upload(){
        $image = $_FILES['image'];
        $name = time();
        move_uploaded_file($image['tmp_name'],ROOT."public/uploads/".$name.".png");
        echo json_encode([
            'success'=>true,
            'file_path'=>"/public/uploads/".$name.".png",
        ]);
    }
}