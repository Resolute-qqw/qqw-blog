<?php
namespace controllers;

use \models\Comment;

class CommentController
{
    function comment(){
        
        if(!isset($_SESSION['id'])){
            echo json_encode([
                'status_code'=>'401',
                'message'=>"未登录~"
            ]);
            exit;
        }
        $data = file_get_contents('php://input');
        $_POST = json_decode($data,TRUE);
        
        $content = filter($_POST['content']);
        $blog_id = $_POST['blog_id'];
        // $content = "123123";
        // $blog_id = "2";
        
        $comment = new Comment;
        $comment->add($content,$blog_id);
        echo json_encode([
            'status_code'=>'200',
            'message'=>'发布成功',
            'data'=>[
                'content'=>$content,
                'avatar'=>$_SESSION['face'],
                'email'=>$_SESSION['email'],
                'created_at'=>date('Y-m-d H:i:s'),
            ]
        ]);
    }
    function comment_list(){
        $blog_id = $_GET['blog_id'];

        $comment = new Comment;
        $data = $comment->get_comment($blog_id);

        echo json_encode([
            'status_code'=>'200',
            'data'=>$data,
        ]);

    }
}