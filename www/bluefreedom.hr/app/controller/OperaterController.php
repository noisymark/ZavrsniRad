<?php

class OperaterController extends AdminController
{
    private $viewPath = 'private'.DIRECTORY_SEPARATOR.'operaters'.DIRECTORY_SEPARATOR;
    private $info;
    private $message='';

    public function index()
    {
        $operaters=Operater::read();
        foreach ($operaters as $o)
        {
            unset($o->lozinka);
        }

        $this->view->render($this->viewPath . 'index',[
            'info'=>$operaters
        ]);
    }

    public function edit($id)
    {
        $id=(int)$id;
        $this->info=Operater::readOneForEdit($id);
        if($this->info==null)
        {
            header('location: ' . App::config('url') . 'operater/index');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->view->render($this->viewPath . 'edit',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
            return;
        }
        $this->preparePost();
        if(!$this->controlInputEdit())
        {
            $this->view->render($this->viewPath . 'edit',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
            return;
        }
        $this->info->id=$id;
        Operater::update((array)$this->info);
        header('location: ' . App::config('url') . 'operater/index');
    }

    public function delete($id)
    {
        $id=(int)$id;
        $this->info=Operater::readOne($id);
        if($this->info==null)
        {
            header('location: ' . App::config('url') . 'operater/index');
            return;
        }
        Operater::delete($id);
        header('location: ' . App::config('url') . 'operater/index');
    }

    public function changePassword($id)
    {
        $this->info=Operater::readOneForEdit($id);
        if($this->info==null)
        {
            header('location: ' . App::config('url') . 'operater/index');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->view->render($this->viewPath . 'changePassword',[
                'info'=>$this->newPassword(),
                'message'=>$this->message
            ]);
            return;
        }
        $this->preparePost();
        if(!$this->controlChangePassword())
        {
            $this->view->render($this->viewPath . 'changePassword',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
            return;
        }
        unset($this->info->confirmpw);
        $this->info->id=$id;
        Operater::changePassword((array)$this->info);
        header('location: ' . App::config('url') . 'operater/index');
    }

    public function new()
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->view->render($this->viewPath . 'new',[
                'info'=>$this->emptyInfo(),
                'message'=>$this->message
            ]);
            return;
        }
        $this->preparePost();
        if(!$this->controlInputNew())
        {
            $this->view->render($this->viewPath . 'new',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
            return;
        }
        unset($this->info->confirmpw);
        Operater::new((array)$this->info);
        header('location: ' . App::config('url') . 'operater/index');
    }

    private function emptyInfo()
    {
        $i = new stdClass;
        $i->fname='';
        $i->lname='';
        $i->email='';
        $i->password='';
        $i->confirmpw='';
        return $i;
    }

    private function preparePost()
    {
        $this->info=(object)$_POST;
    }

    private function controlInputNew()
    {
        $fname=$this->info->fname;
        $lname=$this->info->lname;
        $email=$this->info->email;
        $password=$this->info->password;
        $confirmpw=$this->info->confirmpw;

        if(strlen(trim($fname))==0)
        {
            $this->message='First name cannot be empty!';
            return false;
        }
        if(strlen(trim($fname))>50)
        {
            $this->message='First name cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($lname))==0)
        {
            $this->message='Last name cannot be empty!';
            return false;
        }
        if(strlen(trim($lname))>50)
        {
            $this->message='First name cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($email))==0)
        {
            $this->message='E-mail cannot be empty!';
            return false;
        }
        if(strlen(trim($email))>50)
        {
            $this->message='E-mail cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($password))==0)
        {
            $this->message='Password cannot be empty!';
            return false;
        }
        if(strlen(trim($password))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }
        if(strlen(trim($password))>60)
        {
            $this->message='Password cannot be longer than 60 chars!';
            return false;
        }
        if(strlen(trim($confirmpw))==0)
        {
            $this->message='Confirmed password cannot be empty!';
            return false;
        }
        if(strlen(trim($confirmpw))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }
        if(strlen(trim($confirmpw))>50)
        {
            $this->message='Confirmed password be longer than 60 chars!';
            return false;
        }
        if($password!=$confirmpw)
        {
            $this->message='Password and confirm password do not match!';
            return false;
        }
        return true;
    }
    private function controlInputEdit()
    {
        $fname=$this->info->fname;
        $lname=$this->info->lname;
        $email=$this->info->email;

        if(strlen(trim($fname))==0)
        {
            $this->message='First name cannot be empty!';
            return false;
        }
        if(strlen(trim($fname))>50)
        {
            $this->message='First name cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($lname))==0)
        {
            $this->message='Last name cannot be empty!';
            return false;
        }
        if(strlen(trim($lname))>50)
        {
            $this->message='First name cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($email))==0)
        {
            $this->message='E-mail cannot be empty!';
            return false;
        }
        if(strlen(trim($email))>50)
        {
            $this->message='E-mail cannot be longer than 50 chars!';
            return false;
        }
        return true;
    }

    private function controlChangePassword()
    {
        $password=$this->info->password;
        $confirmpw=$this->info->confirmpw;

        if(strlen(trim($password))==0)
        {
            $this->message='Password cannot be empty!';
            return false;
        }
        if(strlen(trim($password))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }
        if(strlen(trim($password))>60)
        {
            $this->message='Password cannot be longer than 60 chars!';
            return false;
        }
        if(strlen(trim($confirmpw))==0)
        {
            $this->message='Confirmed password cannot be empty!';
            return false;
        }
        if(strlen(trim($confirmpw))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }
        if(strlen(trim($confirmpw))>50)
        {
            $this->message='Confirmed password be longer than 60 chars!';
            return false;
        }
        if($password!=$confirmpw)
        {
            $this->message='Password and confirm password do not match!';
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
}

?>