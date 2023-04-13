<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendMail extends UserAuthorisationController
{
    private $viewPath = BP_APP . 'view' . DIRECTORY_SEPARATOR . 'mailTemplates' . DIRECTORY_SEPARATOR;
    private $mail;
    public function spam($parameters)
    {
        $this->mail = new PHPMailer(false);
        $this->setPHPMailer();
            //Recipients
            $this->mail->setFrom('info@marko-pavlovic.net', 'Blue Freedom');
            $this->mail->addAddress('info@marko-pavlovic.net', 'Blue Freedom');     //Add a recipient

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = 'Spam/abuse report';
            ob_start();
            include ($this->viewPath . 'spam.phtml');
            $body=ob_get_clean();
            $this->mail->Body = $body;
        
            $this->mail->send();
    }

    private function setPHPMailer()
    {
        //Server settings
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = 'mail.marko-pavlovic.net';                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = 'info@marko-pavlovic.net';                     //SMTP username
        $this->mail->Password   = 'Savannnah1';                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`        
    }
}