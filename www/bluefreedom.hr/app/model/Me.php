<?php

class Me
{
    public static function updateName($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba
        set ime=:fname,
        prezime=:lname
        where sifra=:id
        ');
        $query->execute($parameters);
    }

    public static function updateEmail($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba
        set email=:email
        where sifra=:id
        ');
        $query->execute($parameters);
    }
    public static function updatePhoneNumber($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        ');
        $query->execute($parameters);
    }
    public static function updateDob($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set
        datumrodenja=:dob
        where sifra=:id
        ');
        $query->execute($parameters);
    }
    public static function removePhoneNumber($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set
        brojtel=null
        where sifra=:id
        ');
        $query->execute(['id'=>$id]);
        header('location: ' . App::config('url') . 'user/profile/' . $id);
    }
    public static function addPhoneNumber($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update osoba set
        brojtel=:phoneNumber
        where sifra=:id
        ');
        $query->execute($parameters);
    }
}