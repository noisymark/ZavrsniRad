<?php

abstract class UserAuthorisationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!App::auth() || (App::admin() || App::oper()))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        $_SESSION['auth']=Users::readOne($_SESSION['auth']->sifra);
        if($_SESSION['auth']->aktivan!=='1')
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if($_SESSION['auth']->stanje!=='1')
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
    }
}

?>