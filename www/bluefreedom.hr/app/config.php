<?php

$dev = $_SERVER['SERVER_ADDR']==='127.0.0.1' || $_SERVER['SERVER_ADDR']==='localhost' || $_SERVER['SERVER_ADDR']==='::1' ? true : false;

if($dev){
    return [
        'dev'=>$dev,
        'url'=>'https://bluefreedom.hr/',
        'appName'=>'BlueFreedom',
        'database'=>[
            'dsn'=>'mysql:host=localhost;dbname=BlueFreedom;charset=utf8mb4',
            'user'=>'root',
            'password'=>'root'
        ]
    ];
}else{
    return [
        'dev'=>$dev,
        'url'=>'https://polaznik27.edunova.hr/',
        'appName'=>'BlueFreedom',
        'database'=>[
            'dsn'=>'mysql:host=localhost;dbname=selena_BlueFreedom;charset=utf8mb4',
            'user'=>'selena_admin',
            'password'=>'selenaadmin'
        ]
    ];
}

?>