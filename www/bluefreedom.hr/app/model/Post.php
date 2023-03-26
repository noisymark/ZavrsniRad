<?php

class Post
{
    public static function read($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPage');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        concat(a.ime, \' \', a.prezime) as postauthor,
        a.sifra as authorid,
        b.sifra as postid,
        b.naslov as posttitle,
        b.upis as postdescription
        from osoba a
        inner join objava b on b.osoba = a.sifra
        where concat(b.naslov, \' \' , b.upis, \' \', b.sifra)
        like :search
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

    public static function delete($id)
    {
        $connection = DB::getInstance();
        $query = $connection->prepare('
            delete
            from objava
            where sifra=:id
        ');
        $query->execute([
            'id'=>$id
        ]);
    }

    public static function create($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        insert into objava (naslov,upis,vrijemeizrade,osoba)
        values(:title,:description,now(),:id)
        ');
        $query->execute($parameters);
        return $connection->lastInsertId();
    }

//
//// LIKES SELECTOR
//
//select 
//concat(c.ime, ' ', c.prezime) as liker,
//b.sifra as likeID
//from objava a
//inner join svidamise b 
//on b.objava = a.sifra 
//inner join osoba c
//on b.osoba = c.sifra 
//inner join osoba d 
//on a.osoba = d.sifra 
//where a.sifra = :sifra

}

?>