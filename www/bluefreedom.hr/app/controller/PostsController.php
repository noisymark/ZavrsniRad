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

    public function update($id)
    {
        $id=(int)$id;
        $this->e=Post::readOne($id);
        if($this->e==null)
        {
            header('location: ' . App::config('url'));
            return;
        }

        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->view->render($this->viewPath . 'update',[
                'info'=>$this->e,
                'message'=>$this->message
            ]);
            return;
        }
        else
        {
            $this->preparePost();
            if(!$this->controlUpdate())
            {
                $this->view->render($this->viewPath . 'update',[
                    'info'=>$this->e,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->e->id=$id;
            Post::updated((array)$this->e);
            header('location: ' . App::config('url') . 'posts/index');
        }
    }

    private function preparePost()
    {
        $this->e=(object)$_POST;
    }

    private function controlUpdate()
    {
        $title=$this->e->posttitle;
        $description=$this->e->postdescription;

        if(strlen(trim($title))==0)
        {
            $this->message='Title cannot be empty!';
            return false;
        }
        if(strlen(trim($title))>50)
        {
            $this->message='Title cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($description))==0)
        {
            $this->message='Description cannot be empty!';
            return false;
        }
        if(strlen(trim($description))>50)
        {
            $this->message='Description cannot be longer than 250 chars!';
            return false;
        }

        return true;
    }
}

?>