<?php 

class ReportController extends UserAuthorisationController
{

    private $viewPath='public' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR;
    private $info;
    private $message='';

    public function bug()
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
            {
                $this->view->render($this->viewPath . 'bug',[
                    'info'=>$this->emptyEntrySpam(),
                    'message'=>$this->message
                ]);
            }
            else
            {
                $this->preparePost();
                if(!$this->controlInputSpam())
                {
                    $this->view->render($this->viewPath . 'bug',[
                        'info'=>$this->info,
                        'message'=>$this->message
                    ]);
                    return;
                }
                $this->info->userid=$_SESSION['auth']->sifra;
                $this->info->time=Log::time();
                $sendmail = new SendMail;
                $sendmail->bug($this->info);
                $this->view->render($this->viewPath . 'bug',[
                    'info'=>$this->emptyEntrySpam(),
                    'message'=>'REPORT HAS BEEN SUCCESSFULLY SENT!'
                ]);
            }
    }
    public function spam()
    {
        if($this->checkPrivilages())
        {
            if($_SERVER['REQUEST_METHOD']==='GET')
            {
                $this->view->render($this->viewPath . 'spam',[
                    'info'=>$this->emptyEntrySpam(),
                    'message'=>$this->message
                ]);
            }
            else
            {
                $this->preparePost();
                if(!$this->controlInputSpam())
                {
                    $this->view->render($this->viewPath . 'spam',[
                        'info'=>$this->info,
                        'message'=>$this->message
                    ]);
                    return;
                }
                $this->info->userid=$_SESSION['auth']->sifra;
                $this->info->time=Log::time();
                $sendmail = new SendMail;
                $sendmail->spam($this->info);
                $this->view->render($this->viewPath . 'spam',[
                    'info'=>$this->emptyEntrySpam(),
                    'message'=>'REPORT HAS BEEN SUCCESSFULLY SENT!'
                ]);
            }
        }
        else
        {
            header('location: ' . App::config('url'));
            return;
        }
    }
    public function adminapplication()
    {
        if(!$this->checkPrivilages())
        {
            if($_SERVER['REQUEST_METHOD']==='GET')
            {
                $this->view->render($this->viewPath . 'adminapplication',[
                    'info'=>$this->emptyEntrySpam(),
                    'message'=>$this->message
                ]);
            }
            else
            {
                $this->preparePost();
                if(!$this->controlInputSpam())
                {
                    $this->view->render($this->viewPath . 'adminapplication',[
                        'info'=>$this->info,
                        'message'=>$this->message
                    ]);
                    return;
                }
                $this->info->userid=$_SESSION['auth']->sifra;
                $this->info->time=Log::time();
                $sendmail = new SendMail;
                $sendmail->adminapplication($this->info);
                $this->view->render($this->viewPath . 'adminapplication',[
                    'info'=>$this->emptyEntrySpam(),
                    'message'=>'APPLICATION FOR ADMIN HAS BEEN SUCCESSFULLY SENT!'
                ]);
            }
        }
        else
        {
            header('location: ' . App::config('url'));
            return;
        }
    }

    private function checkPrivilages()
    {
        if($_SESSION['auth']->administrator)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function controlInputSpam()
    {
        $entry=$this->info->entry;
        if(strlen(trim($entry))===0)
        {
            $this->message='We can\'t consider your report if you do not fill out this form!';
            return false;
        }
        if(strlen(trim($entry))>250)
        {
            $this->message='Maximum input is 250 characters!';
            return false;
        }

        return true;
    }

    private function preparePost()
    {
        $this->info=(object)$_POST;
    }

    private function emptyEntrySpam()
    {
        $this->info = new stdClass;
        $this->info->entry='';
        return $this->info;
    }
}