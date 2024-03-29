<?php

class IndexController extends Controller
{
    public function index()
    {
        if(App::oper() || App::admin())
        {
            header('location: '.App::config('url').'controlPanel/index');
        }
        else if(App::auth())
        {
            header('location: '.App::config('url').'user/index');
        }
        $this->view->render('index');
    }

    public function login()
    {
        if(App::auth())
        {
            header('location:'.App::config('url'));
        }
        $this->view->render('login',[
            'message'=>'',
            'email'=>''
        ]);
    }

    public function register()
    {
        $u = new RegisterController; $u->new();
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        session_destroy();
        header('location:'.App::config('url'));
    }
}

?>