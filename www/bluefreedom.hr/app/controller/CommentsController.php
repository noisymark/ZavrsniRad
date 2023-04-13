<?php

class CommentsController extends AuthorisationController
{
    private $viewPath='private' . DIRECTORY_SEPARATOR . 'comments' . DIRECTORY_SEPARATOR;
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

        $totalComments=Comments::totalComments($search);
        $lastPage=(int)ceil($totalComments/App::config('resultsPerPage'));
        
        $comments=Comments::read($search,$page);

        $this->view->render($this->viewPath . 'index',[
            'info'=>$comments,
            'search'=>$search,
            'page'=>$page,
            'lastPage'=>$lastPage
        ]);
    }

    public function update($sifra)
    {
        $sifra=(int)$sifra;
        $this->e=Comments::readOne($sifra);
        if($this->e==null)
        {
            header('location: ' . App::config('url') . 'comments/index');
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
        $this->e->sifra=$sifra;
        Comments::update((array)$this->e);
        header('location: ' . App::config('url') . 'comments/index');
       } 
    }

    public function delete($sifra=0)
    {
        $sifra=(int)$sifra;
        if($sifra===0){
            header('location: ' . App::config('url') . 'index/odjava');
            return;
        }
        Comments::delete($sifra);
        header('location: ' . App::config('url') . 'comments/index');
    }

    private function preparePost()
    {
        $this->e=(object)$_POST;
    }

    private function controlUpdate()
    {
        $opis=$this->e->opis;

        if(strlen(trim($opis))===0)
        {
            $this->message='Comment cannot be empty!';
            return false;
        }
        if(strlen(trim($opis))>250)
        {
            $this->message='Comment cannot be longer than 250 chars';
            return false;
        }
        return true;
    }
}

?>