<?php

class PostController extends UserAuthorisationController
{
    private $viewPath='public' . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR;
    private $e;
    private $message='';
    private $info;
    public function __construct($sifra='')
    {
        parent::__construct();
        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">'
        ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/search.js"></script>',
            '<script>
                let url=\'' . App::config('url') . '\';
                let grupasifra=' . $sifra . ';
            </script>'
        ]);
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
        $info[0]->totalLikes=Like::countLikes($postID);
        $likes=Like::likedBy($postID);
        foreach($likes as $li)
        {
            if($li->likerid==$_SESSION['auth']->sifra)
            {
                $liked=true;
                $likeid=$li->likeid;
                break;
            }
            else
            {
                $liked=false;
            }
        }
        if(!isset($likeid))
        {
            $likeid='';
        }
        $this->view->render($this->viewPath . 'index', [
            'info'=>$info,
            'comments'=>$comments,
            'likes'=>$likes,
            'liked'=>isset($liked) ? $liked : '',
            'likeid'=>$likeid
        ]);
        }
    }
    public function new()
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->view->render($this->viewPath . 'new',[
                'message'=>'',
                'info'=>$this->emptyInfo()
            ]);
        }
        else
        {
            $this->e=(object)$_POST;
            $this->e->id=(int)$_SESSION['auth']->sifra;
            if(!$this->controlInput())
            {
                $this->view->render($this->viewPath . 'new',[
                    'info'=>(object)$_POST,
                    'message'=>$this->message
                ]);
                return;
            }
            else
            {
            $lastId=Post::create((array)$this->e);
            header('location: '. App::config('url') . 'post/index/'.$lastId);
            }
        }
    }

    public function comment($postID)
    {
        if($_SERVER['REQUEST_METHOD']==='POST')
        {
            $postID=(int)$postID;
            if($postID<=0)
            {
                header('location: '.App::config('url').'index/logout');
                return;
            }
            $info=(object)$_POST;
            $info->authorID=$_SESSION['auth']->sifra;
            $info->postID=$postID;
            $this->info=(object)$_POST;
            if(!$this->controlNewComment())
            {
                echo '<script>alert("'.$this->message.'");window.location.href="'.App::config('url').'post/index/'. $postID .'";</script>';
                return;
            }
            else
            {
            Comment::new((array)$info);
            header('location: '.App::config('url').'post/index/'.$postID);
            }
        }
    }

    private function controlNewComment()
    {
        $comment=$this->info->NewComment;

        if(strlen(trim($comment))<=0)
        {
            $this->message='Comment cannot be empty!';
            return false;
        }
        if(strlen(trim($comment))>250){
            $this->message='Comment cannot be longer than 250 chars!';
            return false;
        }
        return true;
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
                'info'=>$info,
                'message'=>''
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
        if(strlen(trim($title))<5)
        {
            $this->message='Title cannot be shorter than 5 characters!';
            return false;
        }
        if(strlen(trim($description))<10)
        {
            $this->message='Description cannot be shorter than 10 characters!';
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

    private function emptyInfo()
    {
        $info = new stdClass();
        $info->title='';
        $info->description='';

        return $info;
    }

    public function ajaxSearch($search)
    {
        $this->view->api(Posts::read($search));
    }
}