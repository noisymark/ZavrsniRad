<?php

class MeController extends UserAuthorisationController
{
    private $viewPath='public' . DIRECTORY_SEPARATOR . 'me' . DIRECTORY_SEPARATOR;
    private $info;
    private $message='';

    public function __construct()
    {
        parent::__construct();
        if(!isset($this->message))
        {
            $this->message='';
        }
    }

    public function changeName($id)
    {
        $id=(int)$id;
        if($id==0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(!$this->checkProfileOwner($id))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->info=Users::readOne($id);
            $this->view->render($this->viewPath . 'editName',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
        }
        else
        {
            $this->prepareAfterSubmit();
            if(!$this->controlName())
            {
                $this->view->render($this->viewPath . 'editName',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->prepareForDB();
            $this->info['id']=$id;
            //Log::info($this->info['fname']);
            Me::updateName($this->info);
            header('location: ' . App::config('url') . 'user/profile/' . $id);
        }

    }

    public function changeEmail($id)
    {
        $id=(int)$id;
        if($id==0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(!$this->checkProfileOwner($id))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->info=Users::readOne($id);
            $this->view->render($this->viewPath . 'editEmail',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
        }
        else
        {
            $this->prepareAfterSubmit();
            if(!$this->controlEmail())
            {
                $this->view->render($this->viewPath . 'editEmail',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->prepareForDB();
            $this->info['id']=$id;
            //Log::info($this->info['email']);
            Me::updateEmail($this->info);
            header('location: ' . App::config('url') . 'user/profile/' . $id);
        }
    }

    public function changeDob($id)
    {
        $id=(int)$id;
        if($id==0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(!$this->checkProfileOwner($id))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->info=Users::readOne($id);
            $this->view->render($this->viewPath . 'editDob',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
        }
        else
        {
            $this->prepareAfterSubmit();
            if(!$this->controlDob())
            {
                $this->view->render($this->viewPath . 'editDob',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->prepareForDB();
            $this->info['id']=$id;
            //Log::info($this->info);
            Me::updateDob($this->info);
            header('location: ' . App::config('url') . 'user/profile/' . $id);
        }
    }

    public function addPhoneNumber($id)
    {
        $id=(int)$id;
        if($id==0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(!$this->checkProfileOwner($id))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->info=Users::readOne($id);
            $this->view->render($this->viewPath . 'editPhoneNumber',[
                'info'=>$this->info,
                'message'=>$this->message
            ]);
        }
        else
        {
            $this->prepareAfterSubmit();
            if(!$this->controlName())
            {
                $this->view->render($this->viewPath . 'editName',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->prepareForDB();
            $this->info['id']=$id;
            //Log::info($this->info['fname']);
            Me::updateName($this->info);
            header('location: ' . App::config('url') . 'user/profile/' . $id);
        }
    }

    public function changePhoneNumber($id)
    {
        $id=(int)$id;
        if($id==0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(!$this->checkProfileOwner($id))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->info=Users::readOne($id);
            $this->view->render($this->viewPath . 'editName',[
                'info'=>$this->info
            ]);
        }
        else
        {
            $this->prepareAfterSubmit();
            if(!$this->controlName())
            {
                $this->view->render($this->viewPath . 'editName',[
                    'info'=>$this->info,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->prepareForDB();
            $this->info['id']=$id;
            //Log::info($this->info['fname']);
            Me::updateName($this->info);
            header('location: ' . App::config('url') . 'user/profile/' . $id);
        }
    }

    public function removePhoneNumber($id)
    {
        $id=(int)$id;
        if($id==0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(!$this->checkProfileOwner($id))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        Me::removePhoneNumber($id);
        
    }

    private function checkProfileOwner($id)
    {
        if(!($id==$_SESSION['auth']->sifra))
        {
            header('location: ' . App::config('url') . 'index/logout');
            return false;
        }

        return true;
    }

    private function prepareAfterSubmit()
    {
        $this->info=(object)$_POST;
    }
    private function prepareForDB()
    {
        $this->info=(array)$this->info;
    }

    private function controlName()
    {
        $fname=$this->info->fname;
        $lname=$this->info->lname;

        if(strlen(trim($fname))<=0)
        {
            $this->message='First name cannot be empty!';
            return false;
        }
        if(strlen(trim($lname))<=0)
        {
            $this->message='Last name cannot be empty!';
            return false;
        }
        if(strlen(trim($fname))>25)
        {
            $this->message='First name cannot be longer than 25 chars!';
            return false; 
        }
        if(strlen(trim($lname))>25)
        {
            $this->message='Last name cannot be longer than 25 chars!';
            return false;
        }

        return true;
    }

    private function controlEmail()
    {
        $email=$this->info->email;
        if(strlen(trim($email))=='' || $email==null)
        {
            $this->message='E-mail cannot be empty!';
            return false;
        }
        if(strpos($email,'@')===false)
        {
            $this->message='Invalid e-mail address! <br> E-mail address must contain @example.com';
            return false;
        }
        return true;
    } 

    private function controlDob()
    {
        $date=explode('-',$this->info->dob);
        $year=(int)$date[0];
        $month=(int)$date[1];
        $day=(int)$date[2];
        if(!checkdate($month,$day,$year))
        {
            $this->message='Invalid date format!';
            return false;
        }
        if($year>2006)
        {
            $this->message='You must have at least 18 years!';
            return false;
        }
        if($year<1924)
        {
            $this->message='Please input correct year!';
            return false;
        }
        return true;
    }
}