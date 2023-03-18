<?php

class LoginController extends Controller
{
    public function authorisation()
    {
        if(!isset($_POST['email']) || strlen(trim($_POST['email']))===0)
        {
            $this->view->render('login',[
                'message'=>'E-mail is required',
                'email'=>''
            ]);
            return;
        }
        if(!isset($_POST['password']) || strlen(trim($_POST['password']))===0)
        {
            $this->view->render('login',[
                'message'=>'Password is required',
                'email'=>''
            ]);
            return;
        }

        $operater=Operater::authorise($_POST['email'],$_POST['password']);

        if($operater==null)
        {
            $this->view->render('login',[
                'message'=>'Combination of email and password does not match',
                'email'=>$_POST['email']
            ]);
            return;
        }

        $_SESSION['auth']=$operater;
        setcookie('email',$_POST['email']);
        header('location:'.App::config('url') . 'controlpanel/index');

    }
}

?>