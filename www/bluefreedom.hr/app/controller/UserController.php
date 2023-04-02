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

    public function ajaxSearch($id)
    {
        if(isset($_GET['search']) && $_GET['search']!='')
        {
            $search=$_GET['search'];
        }
        else
        {
            $search='';
        }
        $this->view->api(User::searchPostsOfUser($id,$search));
    }

    public function myProfile()
    {
        $id=$_SESSION['auth']->sifra;
        header('location: '. App::config('url') . 'user/profile/' . $id);
    }

    public function profile($id)
    {
        $info=Users::readOne($id);
        if($info==null)
        {
            header('location: ' . App::config('url') . 'user/notFound');
            return;
        }
        $posts=User::readPostsOfUser($id);
        foreach($posts as $i)
        {
            $i->totalLikes=Like::countLikes($i->postid);
        }
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/search.js"></script>',
            '<script>
                let url=\'' . App::config('url') . '\';
                let id=\'' . $id . '\';
            </script>'
        ]); 
        $this->view->render($this->viewPath . 'profile',[
            'info'=>$info,
            'posts'=>$posts,
            'id'=>$id
        ]);
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

    public function notFound()
    {
        $this->view->render($this->viewPath . 'notFound');
    }
    
}