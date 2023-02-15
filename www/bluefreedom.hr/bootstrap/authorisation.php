<?php

if($_POST['email']==='test@test.hr' && $_POST['password']==='test'){
    session_start();
    $_SESSION['auth']=true;
    setcookie('email',$_POST['email']);
    header('location: home.php');
}else{
    header('location: index.php?email=' . $_POST['email']);
}

?>