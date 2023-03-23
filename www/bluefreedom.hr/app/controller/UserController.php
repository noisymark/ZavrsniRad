<?php
class UserController extends AuthorisationController
{
    private $viewPath = 'public' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;

    private $e;
    private $nf;
    private $message='';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern('###-###-0000');
    }
    public function index()
    {
        $this->view->render($this->viewPath . 'index');
    }
}