<?php

abstract class AuthorisationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!(App::admin() || App::oper()))
        {
            header('location: '.App::config('url').'index/logout');
        }
    }
}

?>