<?php

class PostsController extends AuthorisationController
{
    private $viewPath='private' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR;
    private $e;
    private $message;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view->render($this->viewPath . 'index',[
            'info'=>Posts::read()
        ]);
    }
}

?>