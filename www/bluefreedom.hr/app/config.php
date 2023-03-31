<?php

$dev = $_SERVER['SERVER_ADDR']==='127.0.0.1' || $_SERVER['SERVER_ADDR']==='localhost' || $_SERVER['SERVER_ADDR']==='::1' ? true : false;

if($dev){
    return [
        'dev'=>$dev,
        'url'=>'https://bluefreedom.hr/',
        'appName'=>'BlueFreedom',
        'resultsPerPage'=>10,
        'resultsPerPageUser'=>5,
        'database'=>[
            'dsn'=>'mysql:host=localhost;dbname=BlueFreedom;charset=utf8mb4',
            'user'=>'root',
            'password'=>'root'
        ]
    ];
}else{
    return [
        'dev'=>$dev,
        'url'=>'https://www.marko-pavlovic.net/',
        'appName'=>'BlueFreedom',
        'resultsPerPage'=>10,
        'resultsPerPageUser'=>5,
        'database'=>[
            'dsn'=>'mysql:host=localhost;dbname=markopav_BlueFreedom;charset=utf8mb4',
            'user'=>'markopav_admin',
            'password'=>'Savannnah1'
        ]
    ];
}

?>