<?php

class PostController extends UserAuthorisationController
{
    private $viewPath='public' . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR;
    private $e;
    private $message='';
    public function __construct()
    {
        parent::__construct();
    }

    public function index($postID)
    {
        $postID=(int)$postID;
        if($postID===0)
        {
            header('location: '.App::config('url').'index/logout');
            return;
        }
        else
        {
        $info=Post::readOne($postID);
        $comments=Comment::read($postID);
        $this->view->render($this->viewPath . 'index', [
            'info'=>$info,
            'comments'=>$comments
        ]);
        }
    }
    public function new()
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->view->render($this->viewPath . 'new');
        }
        else
        {
            $this->e=(object)$_POST;
            $this->e->id=(int)$_SESSION['auth']->sifra;
            $lastId=Post::create((array)$this->e);
            header('location: '. App::config('url') . 'post/index/'.$lastId);
        }
    }

    public function delete($id)
    {
        $id=(int)$id;
        if($id===0)
        {
            header('location: '.App::config('url').'index/logout');
            return;
        }
        else
        {
            $info=Post::readOne($id);
            if($info[0]->authorid!==$_SESSION['auth']->sifra)
            {
                header('location: '.App::config('url').'index/logout');
                return;
            }
        Post::delete($id);
        header('location: '.App::config('url').'user/index');
        }
    }

    public function edit($id)
    {
        $id=(int)$id;
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            if($id===0)
        {
            header('location: '.App::config('url').'index/logout');
            return;
        }
        else
        {
            $info=Post::readOne($id);
            if($info[0]->authorid!==$_SESSION['auth']->sifra)
            {
                header('location: '.App::config('url').'index/logout');
                return;
            }

            $this->view->render($this->viewPath.'edit',[
                'info'=>$info
            ]);
        }
        }
        else
        {
            $this->e = (object)$_POST;
            $this->e->id=$id;
            if(!$this->controlInput())
            {
                $this->view->render($this->viewPath . 'edit',[
                    'info'=>Post::readOne($id),
                    'message'=>$this->message
                ]);
                return;
            }
            else
            {
            $lastId=Post::update((array)$this->e);
            header('location: '.App::config('url').'post/index/'.$lastId);
            }
        }
    }
    private function controlInput()
    {
        $title=$this->e->title;
        $description=$this->e->description;
        if(strlen(trim($title))<=0)
        {
            $this->message='Title cannot be empty!';
            return false;
        }
        if(strlen(trim($description))<=0)
        {
            $this->message='Description cannot be empty!';
            return false;
        }
        if(strlen(trim($title))>50)
        {
            $this->message='Description cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($description))>250)
        {
            $this->message='Description cannot be longer than 250 chars!';
            return false;
        }
        return true;
    }
}