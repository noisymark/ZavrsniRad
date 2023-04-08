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
        insert into osoba(ime,prezime,datumrodenja,email,lozinka,stanje,administrator,aktivan)
        values(:fname,:lname,:dob,:email,:password,false,false,true)
        ');
        $query->execute($parameters);}
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public static function readPostsOfUser($id,$page=1)
    {
        $resultsPerPage=App::config('resultsPerPageUser');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        concat(a.ime, \' \', a.prezime) as postauthor,
        a.sifra as authorid,
        b.sifra as postid,
        b.naslov as posttitle,
        b.upis as postdescription
        from osoba a
        inner join objava b on b.osoba = a.sifra
        where a.sifra = :id
        and a.aktivan!=false
        limit :start, :resultsPerPage
        ');

        $query->bindValue('start',$start,PDO::PARAM_INT);
        $query->bindValue('resultsPerPage',$resultsPerPage,PDO::PARAM_INT);
        $query->bindParam('id',$id);
        $query->execute();
        return $query->fetchAll();
    }

    public static function searchPostsOfUser($id,$search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPageUser');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        concat(a.ime, \' \', a.prezime) as postauthor,
        a.sifra as authorid,
        b.sifra as postid,
        b.naslov as posttitle,
        b.upis as postdescription
        from osoba a
        inner join objava b on b.osoba = a.sifra
        where concat(b.naslov, \' \' , b.upis, \' \', b.sifra)
        like :search
        and a.aktivan!=false
        and a.sifra=:id
        order by
        a.ime,
        a.prezime,
        a.sifra,
        b.sifra,
        b.naslov,
        b.upis
        limit :start, :resultsPerPage
        ');

        $query->bindValue('start',$start,PDO::PARAM_INT);
        $query->bindValue('resultsPerPage',$resultsPerPage,PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->bindParam('id',$id);

        $query->execute();
        return $query->fetchAll();
    }

    public static function checkDisabled($email)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select aktivan
        from osoba
        where email=:email
        ');
        $query->execute([
            'email'=>$email
        ]);
        $end=$query->fetchColumn();
        return $end==1;
    }
}