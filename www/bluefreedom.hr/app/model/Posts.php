<?php

class Posts
{
    public static function read()
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select a.sifra as sifraobjave,
        a.naslov,
        a.upis,
        a.vrijemeizrade,
        a.ipadresa,
        a.slika,
        b.sifra as sifrakorisnika,
        b.ime as imekorisnika,
        b.prezime as prezimekorisnika
        from objava a
        left join osoba b
        on a.osoba=b.sifra;
        ');
        $query->execute();
        return $query->fetchAll();
    }
}

?>