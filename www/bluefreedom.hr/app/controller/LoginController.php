<?php

class LoginController extends Controller
{
    private $viewPath = 'public' . DIRECTORY_SEPARATOR;

    public function mailconfirm()
    {

        $id=$_GET['id'];
        if(strlen(trim($id))===0)
        {
            header('location: ' . App::config('url') . 'index/logout');
            return;
        }
        if(Users::confirmMail($id))
        {
            $this->view->render($this->viewPath . 'confirmed',[
                'message'=>'You have successfully confirmed your account!',
                'url'=>App::config('url') . 'index/login',
                'text'=>'Login now'
            ]);
        }
        else
        {
            $this->view->render($this->viewPath . 'confirmed',[
                'message'=>'Something went wrong, please try again from home page!',
                'url'=>App::config('url'),
                'text'=>'Return to home page'
            ]);
        }
    }

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

                // PROVJERI DA LI JE STANJE KORISNIKA 1 (PROVJERI E-MAIL POTVRDU KORISNIKA)

                if(!User::controlVerified($_POST['email']))
                {
                    $this->view->render('login',[
                        'message'=>'Your e-mail has not been verified',
                        'email'=>$_POST['email']
                    ]);
                    return;
                }
                else
                {
                    if(!User::checkDisabled($_POST['email']))
                    {
                        $this->view->render('login',[
                            'message'=>'Your account has been suspended!',
                            'email'=>$_POST['email']
                        ]);
                        return;
                    }

                    $_SESSION['auth']=$user;
                    setcookie('email',$_POST['email']);
                    header('location:'.App::config('url') . 'user/index');
                    return;
                }
            }
        }
        else
        {
        $_SESSION['auth']=$operater;
        setcookie('email',$_POST['email']);
        header('location:'.App::config('url') . 'controlPanel/index');
        }

    }
}

?>