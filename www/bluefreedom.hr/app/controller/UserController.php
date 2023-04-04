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
        $id=(int)$id;
        $info=Users::readOne($id);
        if($info==null)
        {
            header('location: ' . App::config('url') . 'user/notFound');
            return;
        }
        if(isset($_GET['page']))
        {
            $page=(int)$_GET['page'];
        }
        else
        {
            $page=1;
        }

        $totalPosts=Post::totalPostsOfUser($id);
        $lastPage=(int)ceil($totalPosts/App::config('resultsPerPageUser'));
        $posts=User::readPostsOfUser($id,$page);
        foreach($posts as $i)
        {
            $i->totalLikes=Like::countLikes($i->postid);
        }
        parent::setCSSdependency([
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/cropper.css">'
        ]);
        parent::setJSdependency([
            '<script src="' . App::config('url') . 'public/js/dependency/cropper.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/jquery-ui.js"></script>',
            '<script src="' . App::config('url') . 'public/js/dependency/search.js"></script>',
            '<script>
                let url=\'' . App::config('url') . '\';
                let id=\'' . $id . '\';
            </script>'
        ]); 

        if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'userProfilePhotos' . DIRECTORY_SEPARATOR . $info->sifra . '.png'))
        {
            $info->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/' . $info->sifra . '.png';
        }
        else
        {
            $info->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/unknown.png';
        }
        
        $this->view->render($this->viewPath . 'profile',[
            'info'=>$info,
            'posts'=>$posts,
            'id'=>$id,
            'page'=>$page,
            'lastPage'=>$lastPage
        ]);
    }

    public function index($page=1)
    {
        $page=(int)$page;
        if($page==0)
        {
            header('location: ' .  App::config('url') . 'index/logout');
            return;
        }

        $totalPosts=Post::totalPosts();
        $lastPage=(int)ceil($totalPosts/App::config('resultsPerPageUser'));

        $info = Post::read('',$page);
        foreach($info as $i)
        {
            $i->totalLikes=Like::countLikes($i->postid);
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'userProfilePhotos' . DIRECTORY_SEPARATOR . $i->authorid . '.png'))
        {
            $i->photo=App::config('url') . 'public/photos/userProfilePhotos/' . $i->authorid . '.png';
        }
        else
        {
            $i->photo=App::config('url') . 'public/photos/userProfilePhotos/unknown.png';
        }
        }
        $this->view->render($this->viewPath . 'index',[
            'info'=>$info,
            'page'=>$page,
            'lastPage'=>$lastPage
        ]);
    }

    public function notFound()
    {
        $this->view->render($this->viewPath . 'notFound');
    }

    public function saveImage()
    {
        $profilePhoto = $_POST['profilePhoto'];
        $profilePhoto=str_replace('data:image/png;base64,','',$profilePhoto);
        $profilePhoto=str_replace(' ','+',$profilePhoto);
        $data=base64_decode($profilePhoto);

        file_put_contents(BP . 'public' . DIRECTORY_SEPARATOR
        . 'photos' . DIRECTORY_SEPARATOR . 
        'userProfilePhotos' . DIRECTORY_SEPARATOR 
        . $_POST['id'] . '.png', $data);


        $res = new stdClass();
        $res->error=false;
        $res->description='Uspješno spremljeno';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    }
    
}