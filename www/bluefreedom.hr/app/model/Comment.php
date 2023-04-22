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
        a.vrijemekomentiranja,
        concat (b.ime, \' \', b.prezime) as commenter,
        b.sifra as authorid
        from komentar a
        inner join osoba b on a.osoba = b.sifra 
        inner join objava c on a.objava = c.sifra 
        where c.sifra = :postID
        order by a.vrijemekomentiranja desc
        ');
        $query->bindParam('postID',$postID);
        $query->execute();
        return $query->fetchAll();
    }
    public static function delete($commentID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        delete from komentar
        where sifra=:id
        ');
        $query->bindParam('id',$commentID);
        $query->execute();
    }

    public static function readOne($commentID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select 
        a.opis as commentdescription,
        b.sifra as authorid,
        a.objava as postID
        from
        komentar a
        inner join osoba b on a.osoba = b.sifra 
        where a.sifra = :commentID
        ');
        $query->execute([
            'commentID'=>$commentID
        ]);
        return $query->fetchAll();
    }

    public static function new($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        insert into komentar
        (vrijemekomentiranja,opis,objava,osoba)
        values
        (now(),:NewComment,:postID,:authorID);
        ');
        $query->execute($parameters);
    }

    public static function update($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update komentar
        set
        opis=:comment
        where sifra=:commentID
        ');
        $query->execute($parameters);
    }

    public static function findPostOfComment($commentID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select objava from komentar
        where sifra=:commentID
        ');
        $query->execute([
            'commentID'=>$commentID
        ]);
        return $query->fetchColumn();
    }
}