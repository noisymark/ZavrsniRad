<?php

class Like
{
    public static function likedBy($postID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        CONCAT(b.ime, \' \', b.prezime) as likedby,
        b.sifra as likerid,
        a.sifra as likeid
        from svidamise a
        inner join osoba b on a.osoba = b.sifra 
        where a.objava=:postID 
        group by a.sifra, b.ime, b.prezime
        ;
        ');
        $query->execute([
            'postID'=>$postID
        ]);
        return $query->fetchAll();
    }

    public static function countLikes($postID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        count(sifra)
        from svidamise
        where objava=:postID 
        ;
        ');
        $query->execute([
            'postID'=>$postID
        ]);
        return $query->fetchColumn();
    }
}
