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
        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">'
        ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/search.js"></script>',
            '<script>
                let url=\'' . App::config('url') . '\';
            </script>'
        ]);
    }

    public function ajaxSearch($search)
    {
        $this->view->api(Users::read($search));
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