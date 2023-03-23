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

        foreach($posts as $p)
        {
            if($p->sifrakorisnika=='' || $p->sifrakorisnika==null)
            {
                Posts::delete($p->sifraobjave);
            }
        }

        $this->view->render($this->viewPath . 'index',[
            'info'=>$posts,
            'search'=>$search,
            'page'=>$page,
            'lastPage'=>$lastPage
        ]);
    }

    public function delete($sifra=0)
    {
        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Posts::delete($sifra);
        header('location: ' . App::config('url') . 'posts/index');
    }
}

?>