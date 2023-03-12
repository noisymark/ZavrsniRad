<?php

class UsersController extends AuthorisationController
{
    private $viewPath = 'private' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;

    private $e;
    private $nf;
    private $df;
    private $message='';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern('###-###-0000');
        //$this->df = new IntlDateFormatter('hr-HR',IntlDateFormatter::FULL,0,null,null,'MM/dd/yyyy');
    }

    public function index()
    {
        $users = Users::read();

        foreach($users as $u)
        {
            $u->stanje = $u->stanje ? 'check':'x';
            $u->administrator = $u->administrator ? 'check':'x';
            //$u->brojtel = $this->nf->parse($u->brojtel);
            $u->datumrodenja = date('d.m.Y',strtotime($u->datumrodenja));
        }

        $this->view->render($this->viewPath . 'index',[
            'info'=>$users,
            'css'=>'users.css'
        ]);

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
        unset($this->e->confirmpw);
        Users::create((array)$this->e);
        $this->callView([
            'e'=>$this->startingInfo(),
            'message'=>'Created successfully'
        ]);
    }

    private function callView($parameters)
    {
        $this->view->render($this->viewPath.'new',$parameters);
    }

    private function prepareForPOST()
    {
        $this->e=(object)$_POST;
    }

    private function prepareForDB()
    {
        // OVDJE ĆE IČI DATEFORMATTER KOJI NE MOGU POSTAVITI
    }

    private function controlNew()
    {
        return $this->controlName() && $this->controlEmail() && $this->controlPassword();
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
            $this->message='Ime je obavezno';
            return false;
        }

        if(strlen(trim($sd))===0){
            $this->message='Prezime je obavezno';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->message='Ime ne može biti više od 50 znakova';
            return false;
        }
        if(strlen(trim($sd))>50){
            $this->message='Prezime ne može biti više od 50 znakova';
            return false;
        }
        return true;
    }

    private function controlEmail()
    {
        $x=$this->e->email;
        if(strlen(trim($x))===0){
            $this->message='E-mail je obavezan';
            return false;
        }

        if(strlen(trim($x))>50){
            $this->message='E-mail ne može biti više od 50 znakova';
            return false;
        }

        if(Users::sameEmailInDatabase($x)){
            $this->message='Isti naziv postoji u bazi';
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

?>