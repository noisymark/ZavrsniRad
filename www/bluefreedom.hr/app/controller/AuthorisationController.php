<?php

abstract class AuthorisationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!App::auth())
        {
            $this->view->render('login',[
                'message'=>'Please login first',
                'email'=>''
            ]);
        }
    }
}

?>