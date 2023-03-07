<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('index');
    }

    public function login()
    {
        $this->view->render('login',[
            $message='',
            $email=''
        ]);
    }

    public function register()
    {
        $this->view->render('register',[
            $message='',
            $email=''
        ]);
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        session_destroy();
        header('location:'.App::configuration('url'));
    }

    public function api()
    {
        $this->view->api([
            'info'=>[
                'id'=>2,
                'person'=>[
                    'name'=>'Marko',
                    'surname'=>'Marulić'
                ]
                ]
        ]);
    }
}

?>