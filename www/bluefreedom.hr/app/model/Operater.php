<?php

class Operater
{
    public static function read()
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from operater
        where uloga!=\'admin\'
        ');
        $query->execute();
        return $query->fetchAll();
    }
    public static function readOne($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from operater
        where uloga!=\'admin\'
        and sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
        return $query->fetch();
    }
    public static function readOneForEdit($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select ime as fname,
        prezime as lname,
        email as email,
        sifra
        from operater
        where uloga!=\'admin\'
        and sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
        return $query->fetch();
    }

    public static function new($parameters)
    {
        $connection=DB::getInstance();
        $parameters['password']=password_hash($parameters['password'],PASSWORD_BCRYPT);
        $query=$connection->prepare('
        insert into operater
        (ime,prezime,email,lozinka,uloga)
        values
        (:fname,:lname,:email,:password,\'oper\')
        ');
        $query->execute($parameters);
    }

    public static function authorise($email,$password)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from operater where email=:email
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

    public static function update($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update operater
        set ime=:fname,
        prezime=:lname,
        email=:email
        where sifra=:id
        ');
        $query->execute($parameters);
    }

    public static function changePassword($parameters)
    {
        $connection=DB::getInstance();
        $parameters['password']=password_hash($parameters['password'],PASSWORD_BCRYPT);
        $query=$connection->prepare('
        update operater
        set lozinka=:password
        where sifra=:id
        ');
        $query->execute($parameters);
    }

    public static function delete($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        delete from operater
        where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }
}

?>