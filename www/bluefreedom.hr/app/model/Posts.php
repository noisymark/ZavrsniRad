<?php

class Posts
{
    public static function read($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPage');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        a.ime as imekorisnika,
        a.prezime as prezimekorisnika,
        concat(a.ime, \' \', a.prezime) as punoimekorisnika,
        a.sifra as sifrakorisnika,
        b.sifra as sifraobjave,
        b.naslov as naslovobjave,
        b.upis as upisobjave
        from osoba a
        inner join objava b on b.osoba = a.sifra
        where concat(b.naslov, \' \' , b.upis, \' \', b.sifra)
        like :search
        and a.aktivan!=false
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

        $query->execute();
        return $query->fetchAll();
    }

    public static function totalPosts($search='')
    {
        $search='%' . $search . '%';
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select count(*)
        from objava
        where concat(naslov, \' \' , upis, \' \', sifra)
        like :search 
        ');
        $query->execute([
            'search'=>$search
        ]);
        return $query->fetchColumn();
    }
    
    public static function update($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update objava
        set naslov=:title,
        upis=:description
        where sifra=:id
        ');
        $query->execute($parameters);
        return $parameters['id'];
    }

    public static function delete($sifra)
    {
        $connection = DB::getInstance();
        $query = $connection->prepare('
            delete
            from objava
            where sifra=:sifra
        ');
        $query->execute([
            'sifra'=>$sifra
        ]);
    }

    public static function readOne($postID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        concat(a.ime, \' \', a.prezime) as postauthor,
        a.sifra as authorid,
        b.sifra as postid,
        b.naslov as posttitle,
        b.upis as postdescription,
        b.vrijemeizrade as postcreated
        from osoba a
        inner join objava b on b.osoba = a.sifra
        where b.sifra=:postID
        ');

        $query->bindParam('postID',$postID);
        $query->execute();
        return $query->fetchAll();
    }

}

?>