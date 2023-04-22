<?php
class CommentLike
{
    public static function read($postID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
            select CONCAT(b.ime, \' \', b.prezime) as commentliker,
            b.sifra as likerid
            from svidamise_komentar a
            inner join osoba b on a.osoba = b.sifra
            inner join komentar c on a.komentar = c.sifra 
            where c.sifra=:sifra;
        ');
        $query->execute(['sifra'=>$postID]);
        return $query->fetchAll();
    }

    public static function like($commentID)
    {
        $userID=$_SESSION['auth']->sifra;
        $connection=DB::getInstance();
        $query=$connection->prepare('
            insert into svidamise_komentar
            (osoba,komentar)
            values
            (:sifrakorisnika,:sifrakomentara);
        ');
        $query->execute([
            'sifrakorisnika'=>$userID,
            'sifrakomentara'=>$commentID
        ]);
    }
    public static function unlike($commentID)
    {
        $userID=$_SESSION['auth']->sifra;
        $connection=DB::getInstance();
        $query=$connection->prepare('
            delete from svidamise_komentar
            where osoba=:userID
            and komentar=:commentID
        ');
        $query->execute([
            'userID'=>$userID,
            'commentID'=>$commentID
        ]);
    }

    public static function totalLikes($commentID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
            select count(sifra)
            from svidamise_komentar
            where komentar=:sifrakomentara
        ');
        $query->execute([
            'sifrakomentara'=>$commentID
        ]);
        return $query->fetchColumn();
    }

    public static function isLiked($commentID)
    {
        $userID=$_SESSION['auth']->sifra;
        $connection=DB::getInstance();
        $query=$connection->prepare('
            select count(sifra)
            from svidamise_komentar
            where osoba=:userID
            and komentar=:sifrakomentara
        ');
        $query->execute([
            'sifrakomentara'=>$commentID,
            'userID'=>$userID
        ]);
        return $query->fetchColumn();
    }

    public static function findPostOfComment($commentID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
            select c.sifra
            from svidamise_komentar a
            inner join komentar b on a.komentar=b.sifra
            inner join objava c on b.objava=c.sifra
            where a.sifra=:sifrakomentara
        ');
        $query->execute([
            'sifrakomentara'=>$commentID
        ]);
        return $query->fetchColumn();
    }
}