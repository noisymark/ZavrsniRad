<?php

class LikeController extends UserAuthorisationController
{
    public function new($postID)
    {
        $postID=(int)$postID;
        if($postID==0)
        {
            header('location: '. App::config('url') . 'index/logout');
            return;
        }
        if(!Post::postExists($postID))
        {
            header('location: '. App::config('url') . 'index/logout');
            return;
        }
        $userID=(int)$_SESSION['auth']->sifra;
        if(Like::checkIfLiked($postID,$userID))
        {
            header('location: '. App::config('url') . 'index/logout');
            return;
        }
        Like::new($postID,$userID);
        header('location: ' . App::config('url') . 'post/index/' . $postID);
    }

    public function delete($likeID)
    {
        $likeID=(int)$likeID;
        $userID=(int)$_SESSION['auth']->sifra;
        if($likeID==0)
        {
            header('location: '. App::config('url') . 'index/logout');
            return;
        }
        if(!(Like::checkLikeOwner($likeID)==$userID))
        {
            header('location: '. App::config('url') . 'index/logout');
            return;
        }
        $postID=Like::getPostOfLike($likeID);
        Like::delete($likeID);
        header('location: '. App::config('url') . 'post/index/' . $postID);
    }
}