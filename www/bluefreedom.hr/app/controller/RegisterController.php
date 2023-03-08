<?php

class RegisterController extends Controller
{
    
    public function index()
    {
        $info=RegisterController::collect();
        $end=RegisterController::checkEmail($info['email']);
        if(!$end)
        {
            $connection=DB::getInstance();
            $query=$connection->prepare('
            insert into osoba(ime,prezime,datumrodenja,email,lozinka,stanje,administrator)
            values (:fname,:lname,:dob,:email,:password,1,0);
            ');
            $query->execute($info);
            $this->view->render('login',[
                'message'=>'REGISTRATION SUCCESSFULL, YOU CAN NOW LOG IN'
            ]);
        }
        else{
            $this->view->render('register',[
                'message'=>'E-mail is already in use'
            ]);
            
        }

    }

    public function collect()
    {
        if(!isset($_POST['fname']) || strlen(trim($_POST['fname']))===0)
        {
            $this->view->render('register',[
                'message'=>'First name is required.',
                'email'=>''
            ]);
            return;
        }
        if(!isset($_POST['lname']) || strlen(trim($_POST['lname']))===0)
        {
            $this->view->render('register',[
                'message'=>'Last name is required',
                'email'=>''
            ]);
            return;
        }
        if(!isset($_POST['email']) || strlen(trim($_POST['email']))===0)
        {
            $this->view->render('register',[
                'message'=>'E-mail is required',
                'email'=>''
            ]);
            return;
        }
        if(!isset($_POST['password']) || strlen(trim($_POST['password']))===0)
        {
            $this->view->render('register',[
                'message'=>'Password is required',
                'email'=>''
            ]);
            return;
        }
        if(!isset($_POST['password']) || strlen(trim($_POST['password']))===0)
        {
            $this->view->render('register',[
                'message'=>'Confirm password is required',
                'email'=>''
            ]);
            return;
        }
        if(!$_POST['password']===$_POST['confirmpw'])
        {
            $this->view->render('register',[
                'message'=>'Passwords do not match',
                'email'=>''
            ]);
            return;
        }
        unset ($_POST['confirmpw']);
        return $_POST;
    }

    public function checkEmail($email)
    {
        $end='';
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba where email=:email
        ');
        $query->execute(['email'=>$email]);
        $result=$query->fetchAll();
        if(!empty($result))
        {
            $end=true;
            return $end;
        } 
        else
        {
            $end=false;
            return $end;
        }
    }
}

?>