<?php

class Register extends Controller
{
    public static function index()
    {
        $info=Register::collect();
        if(Register::checkEmail($info->email))
        {
            $connection=DB::getInstance();
            $query=$connection->prepare('');
            $query->execute();
            Log::info($query);

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

        $o=new stdClass();
        $o->fname=$_POST['fname'];
        $o->lname=$_POST['lname'];
        $o->dob=$_POST['dob'];
        $o->email=$_POST['email'];
        $o->password=$_POST['password'];

        return $o;
    }

    public function checkEmail($email)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba where email=:email
        ');
        $query->execute(['email'=>$email]);
        $query->fetchAll();
        if($query!==0)
        {
            return $query;
        } 
        else
        {
            return null;
        }
    }
}

?>