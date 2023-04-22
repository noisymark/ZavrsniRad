<?php

class UsersController extends AuthorisationController
{
    private $viewPath = 'private' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;

    private $e;
    private $nf;
    private $message='';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern('###-###-0000');
    }

    public function index()
    {

        if(isset($_GET['search']))
        {
            $search=trim($_GET['search']);
        }
        else
        {
            $search='';
        }

        if(isset($_GET['page']))
        {
            $page=(int)trim($_GET['page']);
            if($page<1)
            {
                $page=1;
            }
        }
        else
        {
            $page=1;
        }

        $totalUsers=Users::totalUsers($search);
        $lastPage=(int)ceil($totalUsers/App::config('resultsPerPage'));

        $users=Users::read($search,$page);

        foreach($users as $u)
        {
            $u->stanje = $u->stanje ? 'check':'x';
            $u->administrator = $u->administrator ? 'check':'x';
            //$u->brojtel = $this->nf->parse($u->brojtel);
            $u->datumrodenja = date('d.m.Y',strtotime($u->datumrodenja));
            $u->profilePhoto=new stdClass;
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'userProfilePhotos' . DIRECTORY_SEPARATOR . $u->sifra . '.png'))
            {
                $u->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/' . $u->sifra . '.png';
            }
            else
            {
                $u->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/unknown.png';
            }
        }

        $this->view->render($this->viewPath . 'index',[
            'info'=>$users,
            'search'=>$search,
            'page'=>$page,
            'lastPage'=>$lastPage
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
        //unset($this->e->confirmpw);
        Users::create((array)$this->e);
        $this->callView([
            'e'=>$this->startingInfo(),
            'message'=>'Created successfully'
        ]);
    }

    public function changepassword($id='')
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
            $this->e=Users::readOneToDisable($id);
            unset($this->e->lozinka);
            $this->e->stanje = $this->e->stanje ? 'check':'x';
            $this->e->administrator = $this->e->administrator ? 'check':'x';
            $this->e->datumrodenja = date('d.m.Y',strtotime($this->e->datumrodenja));
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'userProfilePhotos' . DIRECTORY_SEPARATOR . $this->e->sifra . '.png'))
            {
                $this->e->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/' . $this->e->sifra . '.png';
            }
            else
            {
                $this->e->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/unknown.png';
            }
            $this->view->render($this->viewPath . 'changePassword',[
                'e'=>$this->e,
                'message'=>'Change password to a new one'
            ]);
        }
        else
        {
            $this->e=(object)$_POST;
            if(!$this->checkChangePassword())
            {
                $this->e=Users::readOneToDisable($id);
                $this->e->stanje = $this->e->stanje ? 'check':'x';
                $this->e->administrator = $this->e->administrator ? 'check':'x';
                $this->e->datumrodenja = date('d.m.Y',strtotime($this->e->datumrodenja));
                $this->view->render($this->viewPath . 'changePassword',[
                    'e'=>$this->e,
                    'message'=>$this->message
                ]);
                return;
            }
            $this->e->sifra=$id;
            $this->e=(array)$this->e;
            Users::updatePassword($this->e);
            $this->e=Users::readOneToDisable($id);
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . 'userProfilePhotos' . DIRECTORY_SEPARATOR . $this->e->sifra . '.png'))
            {
                $this->e->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/' . $this->e->sifra . '.png';
            }
            else
            {
                $this->e->profilePhoto=App::config('url') . 'public/photos/userProfilePhotos/unknown.png';
            }
            $this->e->stanje = $this->e->stanje ? 'check':'x';
            $this->e->administrator = $this->e->administrator ? 'check':'x';
            $this->e->datumrodenja = date('d.m.Y',strtotime($this->e->datumrodenja));
            $this->view->render($this->viewPath . 'changePassword',[
                'e'=>$this->e,
                'message'=>'Password updated successfully!'
            ]);
        }
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

            $this->e=Users::readOneToDisable($id);

            if($this->e==null)
            {
                header('location:'.App::config('url').'index/logout');
                return;
            }

            $this->e->datumrodenja = DateTime::createFromFormat("Y-m-d H:i:s",$this->e->datumrodenja)->format("Y-m-d");

            $this->view->render($this->viewPath . 'update',[
                'e'=>$this->e,
                'message'=>'Change data by your will'
            ]);
            return;
        }

        $this->prepareForView();
        $this->e->sifra=$id;
        if(!$this->controlChange())
        {
            $this->view->render($this->viewPath . 'update',[
                'e'=>$this->e,
                'message'=>$this->message
            ]);
            return;
        }

        $this->prepareForDB();
        Users::update((array)$this->e);
        $this->e->dob=DateTime::createFromFormat("Y-m-d h:i:s",$this->e->dob)->format("Y-m-d");
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

    public function disable($id)
    {
        $id=(int)$id;
        $info=Users::readOneToDisable($id);
        if($info==null)
        {
            header('location: ' . App::config('url') . 'users/index');
            return;
        }
        Users::disable($id);
        header('location: ' . App::config('url') . 'users/index');
    }

    public function enable($id)
    {
        $id=(int)$id;
        $info=Users::readOneToDisable($id);
        if($info==null)
        {
            header('location: ' . App::config('url') . 'users/index');
            return;
        }
        Users::enable($id);
        header('location: ' . App::config('url') . 'users/index');
    }

    public function enableadmin($id)
    {
        $id=(int)$id;
        $info=Users::readOne($id);
        if($info==null)
        {
            header('location: ' . App::config('url') . 'users/index');
            return;
        }
            Users::enableadmin($id);
            header('location: ' . App::config('url') . 'users/index');
    }

    public function disableadmin($id)
    {
        $id=(int)$id;
        $info=Users::readOne($id);
        if($info==null)
        {
            header('location: ' . App::config('url') . 'users/index');
            return;
        }
        Users::disableadmin($id);
        header('location: ' . App::config('url') . 'users/index');
    }

    private function controlChange()
    {
        return $this->controlUpdateName() && $this->controlUpdateEmail() && $this->controlSameEmail($this->e->sifra) && $this->controlUpdatePhone() && $this->controlUpdateDob() && $this->controlUpdateActiveAdmin();
    }

    private function controlUpdateActiveAdmin()
    {
        $admin = $this->e->admin;
        $status = $this->e->status;

        if(!($admin===1 || $admin===0))
        {
            $this->message='Admin option you chose does not exist!';
            return false;
        }
        if(!($status===1 || $status===0))
        {
            $this->message='Status option you chose does not exist!';
            return false;
        }

        return true;
    }

    private function checkChangePassword()
    {
        $pw = $this->e->password;

        if(strlen(trim($pw))===0)
        {
            $this->message='Password cannot be empty!';
            return false;
        }
        if(strlen(trim($pw))>15)
        {
            $this->message='Password cannot be longer than 15 chars!';
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
    private function controlUpdatePhone()
    {
        $y=$this->e->phnum;
        if(strlen(trim($y))==0)
        {
            $this->e->phnum=0;
            return true;
        }
        $y=(int)$y;
        if(strlen(trim($y))>10){
            $this->message='Phone number cannot be longer than 10 digits';
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

?>