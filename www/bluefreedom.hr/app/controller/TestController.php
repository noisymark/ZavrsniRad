<?php

class TestController
{

    public function addUsers()
    {

        for($i=0;$i<300;$i++)
        {
        Users::create([
            'fname'=>'TestFName'.$i,
            'lname'=>'TestLName'.$i,
            'dob'=>'2000-12-12',
            'email'=>'user'.$i.'@testadded.net',
            'password'=>'exampleuser'
        ]);
    }
    }
    public function password()
    {
        echo password_hash('edunova',PASSWORD_BCRYPT);
    }
    public function password2()
    {
        echo password_hash('testuser',PASSWORD_BCRYPT);
    }
    public function edunovapw()
    {
        echo password_hash('edunovaa',PASSWORD_BCRYPT);
    }
}

?>