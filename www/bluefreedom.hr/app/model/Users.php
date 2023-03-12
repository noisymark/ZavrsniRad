<?php

class Users
{
    public static function read()
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba
        order by ime asc
        ');

        $query->execute();
        return $query->fetchAll();
    }

    public static function readOne($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba where sifra=:sifra
        ');
        $query->execute([
            'sifra'=>$id
        ]);
        return $query->fetch();
    }

    public static function create($parameters)
    {
        try
        {$connection=DB::getInstance();
        $query=$connection->prepare('
        insert into osoba(ime,prezime,datumrodenja,email,lozinka,stanje,administrator)
        values(:fname,:lname,:dob,:email,:password,true,false)
        ');
        $query->execute($parameters);}
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public static function sameEmailInDatabase($s)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select sifra from osoba
        where email=:email
        ');
        $query->execute([
            'email'=>$s
        ]);
        $sifra=$query->fetchColumn();
        return $sifra>0;
    }
}

?>