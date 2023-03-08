<?php

class UsersController extends AuthorisationController
{
    private $viewPath = 'private' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $users = Users::read();

        foreach($users as $u)
        {
            $u->stanje = $u->stanje ? 'check':'x';
            $u->administrator = $u->administrator ? 'check':'x';
        }

        $this->view->render($this->viewPath . 'index',[
            'info'=>$users,
            'css'=>'users.css'
        ]);

    }
}

?>