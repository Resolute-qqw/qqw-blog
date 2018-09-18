<?php
namespace models;

use PDO;

class Redbag extends Base{

    function add($user_id){
        $stmt = self::$pdo->prepare("INSERT INTO redbag('user_id') VALUE(?)");
        return $stmt->execute([
            $user_id
        ]);
    }

}