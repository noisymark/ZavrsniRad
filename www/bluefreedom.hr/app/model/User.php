<?php

class User
{
    public static function authorise($email,$password)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba where email=:email
        ');
        $query->execute(['email'=>$email]);

        $operater = $query->fetch();

        if($operater==null)
        {
            return null;
        }

        if(!password_verify($password,$operater->lozinka))
        {
            return null;
        }

        unset($operater->lozinka);
        return $operater;
    }
}