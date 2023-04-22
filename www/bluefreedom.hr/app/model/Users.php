<?php

class Users
{
    public static function read($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPage');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba
        where concat(ime, \' \' , prezime, \' \', email)
        like :search
        order by ime asc
        limit :start, :resultsPerPage
        ');

        $query->bindValue('start',$start,PDO::PARAM_INT);
        $query->bindValue('resultsPerPage',$resultsPerPage,PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();
        return $query->fetchAll();
    }

    public static function totalUsers($search='')
    {
        $search='%' . $search . '%';
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select count(*) from osoba
        where concat(ime, \' \' , prezime, \' \', email)
        like :search
        and aktivan!=false
        ');
        $query->execute([
            'search'=>$search
        ]);
        return $query->fetchColumn();
    }

    public static function readOne($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba where sifra=:id
        and aktivan!=false
        ');
        $query->execute([
            'id'=>$id
        ]);
        return $query->fetch();
    }
    public static function readOneToDisable($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
        return $query->fetch();
    }

    public static function create($parameters)
    {
        $parameters['password']=password_hash($parameters['password'],PASSWORD_BCRYPT);
        unset($parameters['confirmpw']);
        try
        {$connection=DB::getInstance();
        $query=$connection->prepare('
        insert into osoba(ime,prezime,datumrodenja,email,lozinka,stanje,administrator,aktivan)
        values(:fname,:lname,:dob,:email,:password,true,false,true)
        ');
        $query->execute($parameters);}
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public static function update($parameters)
    {
        //Log::info($parameters);
        try{
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set
        ime=:fname,
        prezime=:lname,
        datumrodenja=:dob,
        email=:email,
        stanje=:status,
        administrator=:admin,
        brojtel=:phnum
        where sifra=:sifra;
        ');
        $query->execute($parameters);
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public static function updatePassword($parameters)
    {
        $parameters['password']=password_hash($parameters['password'],PASSWORD_BCRYPT);
        try{
            $connection=DB::getInstance();
            $query=$connection->prepare('
            update osoba set
            lozinka=:password
            where sifra=:sifra
            ');
            $query->execute($parameters);
            }
            catch(Exception $ex)
            {
                echo $ex->getMessage();
            }
    }

    public static function delete($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        delete from osoba where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }

    public static function sameEmailInDatabase($s,$id='')
    {
        $connection=DB::getInstance();
        if($id!=='')
        {
            $query=$connection->prepare('
        select count(sifra) from osoba
        where email=:email
        and sifra!=:sifra
        ');
        $query->execute([
            'email'=>$s,
            'sifra'=>$id
        ]);
        }
        else
        {
        $query=$connection->prepare('
        select count(sifra) from osoba
        where email=:email
        ');
        $query->execute([
            'email'=>$s
        ]);
        }
        $sifra=$query->fetchColumn();
        return $sifra>0;
    }

    public static function disable($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set
        aktivan=0
        where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }

    public static function enable($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set
        aktivan=1
        where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }

    public static function confirmMail($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set stanje=true
        where uniqueid=:id
        ');
        return $query->execute(['id'=>$id]);
    }

    public static function disableadmin($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba
        set administrator=false
        where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }
    public static function enableadmin($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba
        set administrator=true
        where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }
}

?>