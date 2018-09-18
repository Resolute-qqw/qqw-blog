<?php
namespace models;

class Redbag extends Base{

    function add($user_id){
        $stmt = self::$pdo->prepare("INSERT INTO redbag (user_id) VALUE(?)");
        $stmt->execute([
            $user_id
        ]);
    }

}