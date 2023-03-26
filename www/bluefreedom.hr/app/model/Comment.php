<?php

class Comment
{
    public static function read($postID)
    {
        $connection=DB::getInstance();
        $query = $connection->prepare('
        select 
        a.sifra as commentid,
        a.vrijemekomentiranja as commenttime,
        a.opis as commentdescription,
        a.osoba as commenterid,
        concat (b.ime, \' \', b.prezime) as commenter
        from komentar a
        inner join osoba b on a.osoba = b.sifra 
        inner join objava c on a.objava = c.sifra 
        where c.sifra = :postID
        ');
        $query->bindParam('postID',$postID);
        $query->execute();
        return $query->fetchAll();
    }
}