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
        and b.aktivan!=false
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

    public static function getPostOfLike($likeID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select objava
        from svidamise
        where sifra=:likeID;
        ');
        $query->execute([
            'likeID'=>$likeID
        ]);
        return $query->fetchColumn();
    }

    public static function checkIfLiked($postID,$userID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select count(sifra)
        from svidamise
        where objava=:postID
        and osoba=:userID;
        ');
        $query->execute([
            'postID'=>$postID,
            'userID'=>$userID
        ]);
        $result=$query->fetchColumn();
        return $result>0;
    }

    public static function checkLikeOwner($likeID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select osoba
        from svidamise
        where sifra=:likeID;
        ');
        $query->execute([
            'likeID'=>$likeID
        ]);
        return $query->fetchColumn();
    }

    public static function delete($likeID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        delete from svidamise where sifra=:likeID;
        ');
        $query->execute([
            'likeID'=>$likeID
        ]);
    }

    public static function new($postID,$userID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        insert into svidamise
        (vrijemesvidanja,objava,osoba)
        values
        (now(),:postID,:userID);
        ');
        $query->execute([
            'postID'=>$postID,
            'userID'=>$userID
        ]);
    }
}
