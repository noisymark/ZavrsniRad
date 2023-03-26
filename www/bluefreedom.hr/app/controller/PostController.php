<?php

class PostController extends UserAuthorisationController
{
    private $viewPath='public' . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR;
    public function __construct()
    {
        parent::__construct();
    }

    public function index($postID)
    {
        $postID=(int)$postID;
        $info=Post::readOne($postID);
        $comments=Comment::read($postID);
        $this->view->render($this->viewPath . 'index', [
            'info'=>$info,
            'comments'=>$comments
        ]);
    }
}