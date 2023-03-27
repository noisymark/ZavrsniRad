<?php
class UserController extends UserAuthorisationController
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
        $info = Post::read();
        foreach($info as $i)
        {
            $i->totalLikes=Like::countLikes($i->postid);
        }
        $this->view->render($this->viewPath . 'index',[
            'info'=>$info
        ]);
    }
}