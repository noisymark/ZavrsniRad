<?php

class CommentController extends UserAuthorisationController
{
    private $viewPath='public' . DIRECTORY_SEPARATOR . 'comment' . DIRECTORY_SEPARATOR;
    private $message;
    private $info;

    public function edit($commentID)
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $commentID=(int)$commentID;
            if($commentID<=0)
            {
                header('location: '.App::config('url').'index/logout');
                return;
            }
            $info=Comment::readOne($commentID);
            $this->view->render($this->viewPath.'update',[
                'info'=>$info,
                'message'=>''
            ]);
        }
        else
        {
            $check=Comment::readOne($commentID);
            if($check[0]->authorid!=$_SESSION['auth']->sifra)
            {
                header('location: '.App::config('url').'index/logout');
                return;
            }
            $info=(object)$_POST;
            $this->info=(object)$_POST;
            if(!$this->controlEditComment())
            {
                $this->view->render($this->viewPath.'update',[
                    'info'=>Comment::readOne($commentID),
                    'message'=>$this->message
                ]);
                return;
            }
            else{
            $info->commentID=$commentID;
            Comment::update((array)$info);
            header('location: '. App::config('url') . 'post/index/'.$check[0]->postID);
            }
        }
    }

    private function controlEditComment()
    {
        $comment=$this->info->comment;
        if(strlen(trim($comment))<=0)
        {
            $this->message='Comment cannot be empty!';
            return false;
        }
        if(strlen(trim($comment))>250)
        {
            $this->message='Comment cannot be longer than 250 chars!';
            return false;
        }

        return true;
    }

    public function delete($commentID)
    {
        $commentID=(int)$commentID;
        if($commentID===0)
        {
            header('location: '.App::config('url').'index/logout');
            return;
        }
        else
        {
            $info=Comment::readOne($commentID);
            $postID=$info[0]->postID;
            if($info[0]->authorid!==$_SESSION['auth']->sifra)
            {
                header('location: '.App::config('url').'index/logout');
                return;
            }
        Comment::delete($commentID);
        header('location: '.App::config('url').'post/index/'.$postID);
        }
    }
}