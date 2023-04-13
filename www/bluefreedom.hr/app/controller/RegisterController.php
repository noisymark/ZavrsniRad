<?php

class RegisterController extends Controller
{

    private $e;
    private $nf;
    private $message='';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern('###-###-0000');
    }

    public function new()
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            $this->callView([
                'e'=>$this->startingInfo(),
                'message'=>$this->message
            ]);
            return;
        }
        $this->prepareForPOST();
        if(!$this->controlNew())
        {
            $this->callView([
                'e'=>$this->e,
                'message'=>$this->message
            ]);
            return;
        }
        //$this->prepareForDB();
        //unset($this->e->confirmpw);
        $this->e->uniqueid=uniqid();
        User::create((array)$this->e);
        $mail = new SendMail;
        $this->e->fullname=$this->e->fname . ' ' . $this->e->lname;
        $mail->confirmMail($this->e->uniqueid,$this->e->email,$this->e->fullname);
        echo ("<script>window.alert('Registered successfully!');window.location.href='" . App::config('url') . "';</script>");
    }

    private function callView($parameters)
    {
        $this->view->render('register',$parameters);
    }

    private function prepareForPOST()
    {
        $this->e=(object)$_POST;
    }

    private function controlNew()
    {
        return $this->controlName() && $this->controlEmail() && $this->controlSameEmail() && $this->controlDOB() && $this->controlPassword() ;
    }

    private function controlSameEmail($id='')
    {
        if(Users::sameEmailInDatabase($this->e->email,$id))
        {
            $this->message='Same email already exists';
            return false;
        }
        return true;
    }

    private function controlDOB()
    {
        $dob = $this->e->dob;
        if(strlen(trim($dob)===0))
        {
            $this->message='Date of birth cannot be empty';
            return false;
        }
        if((int)$dob===0)
        {
            $this->message='Date of birth cannot be empty';
            return false;
        }
        return true;
    }

    private function controlPassword()
    {
        $pw = $this->e->password;
        $pww = $this->e->confirmpw;

        if(strlen(trim($pw))===0)
        {
            $this->message='Password cannot be empty!';
            return false;
        }
        if(strlen(trim($pww))===0)
        {
            $this->message='Confirm password cannot be empty!';
            return false;
        }

        if(strlen(trim($pw))>50)
        {
            $this->message='Password cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($pww))>50)
        {
            $this->message='Confirm password cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($pw))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }
        if(strlen(trim($pww))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }

        if(!($pw===$pww))
        {
            $this->message='Both passwords must match!';
            return false;
        }

        return true;
    }

    private function controlName()
    {
        $s=$this->e->fname;
        $sd=$this->e->lname;

        if(strlen(trim($s))===0){
            $this->message='First name cannot be empty';
            return false;
        }

        if(strlen(trim($sd))===0){
            $this->message='Last name cannot be empty';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->message='First name cannot be more than 50 chars';
            return false;
        }
        if(strlen(trim($sd))>50){
            $this->message='Last name cannot be more than 50 chars';
            return false;
        }
        return true;
    }

    private function controlEmail()
    {
        $x=$this->e->email;
        if(strlen(trim($x))===0){
            $this->message='E-mail cannot be empty';
            return false;
        }

        if(strlen(trim($x))>50){
            $this->message='E-mail cannot be longer than 50 chars';
            return false;
        }

        if(Users::sameEmailInDatabase($x)){
            $this->message='This e-mail is already registered!';
            return false; 
        }

        return true;
    }

    private function startingInfo()
    {
        $e = new stdClass();
        $e->fname='';
        $e->lname='';
        $e->email='';
        $e->password='';
        $e->dob='';

        return $e;
    }
}
