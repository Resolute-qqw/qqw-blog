<?php
namespace models;
use PDO;
class Comment extends Base{
    function add($content,$blog_id){
        $stmt = self::$pdo->prepare("INSERT INTO comment(content,user_id,blog_id) VALUE(?,?,?)");
        $stmt->execute([$content,$_SESSION['id'],$blog_id]);
    }
    function get_comment($blog_id){
        $sql = "SELECT c.*,u.email,u.face FROM comment c LEFT JOIN users u on c.user_id=u.id WHERE c.blog_id=? ";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$blog_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}