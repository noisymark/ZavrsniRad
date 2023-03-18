<?php

class IndexController extends Controller
{
    public function index()
    {
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
        $this->view->render('register');
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        session_destroy();
        header('location:'.App::config('url'));
    }
}

?>