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

    public function update($id='')
    {
        if($_SERVER['REQUEST_METHOD']==='GET')
        {
            if(strlen(trim($id))===0)
            {
                header('location:'.App::config('url').'index/logout');
                return;
            }

            $id=(int)$id;
            if($id===0)
            {
                header('location:'.App::config('url').'index/logout');
                return;
            }

            $this->e=Users::readOne($id);

            if($this->e==null)
            {
                header('location:'.App::config('url').'index/logout');
                return;
            }

            $this->view->render($this->viewPath . 'update',[
                'e'=>$this->e,
                'message'=>'Change data by your will'
            ]);
            return;
        }

        $this->prepareForView();
        if(!$this->controlChange())
        {
            $this->view->render($this->viewPath . 'update',[
                'e'=>$this->e,
                'message'=>$this->message
            ]);
            return;
        }

        $this->e->sifra=$id;
        $this->prepareForDB();
        Users::update((array)$this->e);
        $this->view->render($this->viewPath . 'update',[
            'e'=>$this->e,
            'message'=>'Changes saved successfully'
        ]);
    }

    public function delete($id=0)
    {
        $id=(int)$id;
        if($id===0)
        {
            header('location:'.App::config('url').'index/logout');
            return;
        }

        Users::delete($id);
        header('location:'.App::config('url').'users/index');
    }

    private function controlChange()
    {
        return $this->controlUpdateName() && $this->controlUpdateEmail() && $this->controlUpdateDob() && $this->controlUpdatePassword() && $this->controlUpdateActiveAdmin();
    }

    private function controlUpdateActiveAdmin()
    {
        $admin = $this->e->admin;
        $status = $this->e->status;

        if(!($admin===true || $admin===false))
        {
            $this->message='User admin option you chose does not exist!';
            return false;
        }
        if(!($status===true || $status===false))
        {
            $this->message='User status option you chose does not exist!';
            return false;
        }

        return true;
    }

    private function controlUpdatePassword()
    {
        $pw = $this->e->password;

        if(strlen(trim($pw))===0)
        {
            $this->message='Password cannot be empty!';
            return false;
        }
        if(strlen(trim($pw))>50)
        {
            $this->message='Password cannot be longer than 50 chars!';
            return false;
        }
        if(strlen(trim($pw))<8)
        {
            $this->message='Password cannot be shorter than 8 chars!';
            return false;
        }

        return true;
    }

    private function controlUpdateDob()
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

    private function controlUpdateEmail()
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

        return true;
    }

    private function controlUpdateName()
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

    private function prepareForView()
    {
        $this->e = (object)$_POST;
        $this->e->status = $this->e->status==='true' ? 1 : 0;
        $this->e->admin = $this->e->admin==='true' ? 1 : 0;
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
        $this->e->dob = date('Y-m-d H:i:s', strtotime($this->e->dob));
    }

    private function controlNew()
    {
        return $this->controlName() && $this->controlEmail() && $this->controlDOB() && $this->controlPassword();
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

?>