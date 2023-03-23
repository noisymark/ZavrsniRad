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
            // OPERATER NIJE AUTORIZIRAN

            // PROVJERI OBIČNOG KORISNIKA

            $user=User::authorise($_POST['email'],$_POST['password']);
            if($user==null)
            {
                // NI KORISNIKU SE NE PODUDARAJU EMAIL I LOZINKA
                $this->view->render('login',[
                    'message'=>'Combination of email and password does not match',
                    'email'=>$_POST['email']
                ]);
                return;
            }
            else
            {
                $_SESSION['auth']=$user;
                setcookie('email',$_POST['email']);
                header('location:'.App::config('url') . 'user/index');
                return;
            }
        }
        else
        {
        $_SESSION['auth']=$operater;
        setcookie('email',$_POST['email']);
        header('location:'.App::config('url') . 'controlpanel/index');
        }

    }
}

?>