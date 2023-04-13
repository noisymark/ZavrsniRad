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
        $user=Users::readOne($_SESSION['auth']->sifra);
        if($user->aktivan!='1')
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
    }
}

?>