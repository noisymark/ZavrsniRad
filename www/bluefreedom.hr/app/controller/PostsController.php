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
        
        if(isset($_GET['search']))
        {
            $search=trim($_GET['search']);
        }
        else
        {
            $search='';
        }

        if(isset($_GET['page']))
        {
            $page=(int)trim($_GET['page']);
            if($page<1)
            {
                $page=1;
            }
        }
        else
        {
            $page=1;
        }

        $totalPosts=Posts::totalPosts($search);
        $lastPage=(int)ceil($totalPosts/App::config('resultsPerPage'));
        
        $posts=Posts::read($search,$page);

        $this->view->render($this->viewPath . 'index',[
            'info'=>$posts,
            'search'=>$search,
            'page'=>$page,
            'lastPage'=>$lastPage
        ]);
    }
}

?>