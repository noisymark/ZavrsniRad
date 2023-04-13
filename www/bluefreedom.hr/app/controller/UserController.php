<?php
class UserController extends UserAuthorisationController
{
    private $viewPath = 'public' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;

    private $info;
    private $nf;
    private $message='';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern('###-###-0000');
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
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/cropper.css">',
            '<link rel="stylesheet" href="' . App::config('url') . 'public/css/dependency/jquery-ui.css">'
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

    public function changePassword($id)
    {
        $id=(int)$id;
        $this->info=User::readOneForEdit($id);
        if($this->info->sifra==$_SESSION['auth']->sifra)
        {
            if($_SERVER['REQUEST_METHOD']==='GET')
            {
                $this->view->render($this->viewPath . 'changePassword',[
                    'info'=>$this->newPassword(),
                    'message'=>$this->message
                ]);
                return;
            }
            $this->preparePost();
            if(!$this->controlPassword())
            {
                $this->view->render($this->viewPath . 'changePassword',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            unset($this->info->confirmpw);
            $this->info->id=$id;
            User::updatePassword((array)$this->info);
            header('location: ' . App::config('url') . 'user/myProfile');
        }
        else
        {
            header('location: ' . App::config('url') . 'user/index');
            return;
        }
    }

    public function index($page=1)
    {
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

    public function editMyProfile($id)
    {
        $id=(int)$id;
        $this->info=User::readOneForEdit($id);
        if($this->info->sifra==$_SESSION['auth']->sifra)
        {
            if($_SERVER['REQUEST_METHOD']==='GET')
            {
                $this->view->render($this->viewPath . 'edit',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->preparePost();
            if(!$this->controlEdit())
            {
                $this->view->render($this->viewPath . 'edit',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->info->id=$id;
            User::update((array)$this->info);
            header('location: ' . App::config('url') . 'user/myProfile');
        }
        else
        {
            header('location: ' . App::config('url') . 'user/index');
            return;
        }
    }

    public function saveImage()
    {
        $profilePhoto = $_POST['profilePhoto'];
        $profilePhoto=str_replace('data:image/png;base64,','',$profilePhoto);
        $profilePhoto=str_replace(' ','+',$profilePhoto);
        $data=base64_decode($profilePhoto);

        if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR
        . 'photos' . DIRECTORY_SEPARATOR . 
        'userProfilePhotos' . DIRECTORY_SEPARATOR 
        . $_POST['id'] . '.png'))
        {
            clearstatcache(true, BP . 'public' . DIRECTORY_SEPARATOR
            . 'photos' . DIRECTORY_SEPARATOR . 
            'userProfilePhotos' . DIRECTORY_SEPARATOR . $_POST['id'] . '.png');
        }
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

    private function preparePost()
    {
        $this->info=(object)$_POST;
    }

    private function controlEdit()
    {
        $fname=$this->info->fname;
        $lname=$this->info->lname;
        $email=$this->info->email;

        if(strlen(trim($fname))==0)
        {
            $this->message='First name cannot be empty!';
            return false;
        }
        if(strlen(trim($fname))>50){
            $this->message='First name cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($lname))==0)
        {
            $this->message='Last name cannot be empty!';
            return false;
        }
        if(strlen(trim($lname))>50){
            $this->message='Last name cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($email))==0)
        {
            $this->message='E-mail cannot be empty!';
            return false;
        }
        if(strlen(trim($email))>50){
            $this->message='E-mail cannot be longer than 50 chars!';
            return false;
        }
        return true;
    }

    private function newPassword()
    {
        $p = new stdClass;
        $p->password='';
        $p->confirmpw='';
        return $p;
    }

    private function controlPassword()
    {
        $password=$this->info->password;
        $confirmpw=$this->info->confirmpw;

        if(strlen(trim($password))==0)
        {
            $this->message='Password cannot be empty';
            return false;
        }
        if(strlen(trim($password))<8)
        {
            $this->message='Password cannot be less than 8 characters long';
            return false;
        }
        if(strlen(trim($password))>60)
        {
            $this->message='Password cannot be longer than 60 characters';
            return false;
        }
        if(strlen(trim($confirmpw))==0)
        {
            $this->message='Confirm password cannot be empty';
            return false;
        }
        if(strlen(trim($confirmpw))<8)
        {
            $this->message='Confirm password cannot be less than 8 characters long';
            return false;
        }
        if(strlen(trim($confirmpw))>60)
        {
            $this->message='Confirm password cannot be longer than 60 characters';
            return false;
        }
        if($password!=$confirmpw)
        {
            $this->message='Both passwords must match!';
            return false;
        }
        return true;
    }
}