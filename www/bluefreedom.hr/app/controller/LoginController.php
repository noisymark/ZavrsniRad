<?php

class LoginController extends Controller
{
    public function authorisation()
    {
        if(!isset($_POST['email']) || strlen(trim($_POST['email']))==='')
        {
            $this->view->render('login',[
                'message'=>'E-mail cannot be empty!',
                'email'=>''
            ]);
            return;
        }
        if(!isset($_POST['password']) || strlen(trim($_POST['password']))==='')
        {
            $this->view->render('login',[
                'message'=>'Password cannot be empty!',
                'email'=>$_POST['email']
            ]);
            return;
        }

        $operater=Operater::authorise($_POST['email'],$_POST['password']);

        if($operater===null)
        {
            $this->view->render('login',[
                'message'=>'Combination of email and password is not valid!',
                'email'=>$_POST['email']
            ]);
            return;
        }

        $_SESSION['auth']=$operater;
        header('location:'.App::configuration('url').'controlPanel/index');
    }
}

?>