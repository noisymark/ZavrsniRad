<?php

class User
{
    public static function authorise($email,$password)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select *
        from osoba
        where email=:email
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
    public static function controlVerified($email)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select stanje
        from osoba
        where email=:email
        ');
        $query->execute(['email'=>$email]);
        $verified = $query->fetchColumn();
        if($verified==0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    public static function create($parameters)
    {
        $parameters['password']=password_hash($parameters['password'],PASSWORD_BCRYPT);
        unset($parameters['confirmpw']);
        try
        {$connection=DB::getInstance();
        $query=$connection->prepare('
        insert into osoba(ime,prezime,datumrodenja,email,lozinka,stanje,administrator)
        values(:fname,:lname,:dob,:email,:password,false,false)
        ');
        $query->execute($parameters);}
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
}