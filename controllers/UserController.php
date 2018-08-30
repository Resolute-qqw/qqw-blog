<?php
namespace controllers;
use models\User;

class UserController{

    function hello(){
        $user = new User;
        $name = $user->getname();
        return view("user.hello",[
            "name"=>$name,
        ]);
    }

    function gogogo(){
        $user = new User;
        $name = $user->gogo();
        return view("user.hello",[
            "name"=>$name,
        ]);
    }

}

