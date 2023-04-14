<?php

class Post
{
    public static function read($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPageUser');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select
        concat(a.ime, \' \', a.prezime) as postauthor,
        a.sifra as authorid,
        b.sifra as postid,
        b.naslov as posttitle,
        b.upis as postdescription,
        b.vrijemeizrade
        from osoba a
        inner join objava b on b.osoba = a.sifra
        where concat(b.naslov, \' \' , b.upis, \' \', b.sifra)
        like :search
        and a.aktivan!=false
        order by b.vrijemeizrade desc
        limit :start, :resultsPerPage
        ');

        $query->bindValue('start',$start,PDO::PARAM_INT);
        $query->bindValue('resultsPerPage',$resultsPerPage,PDO::PARAM_INT);
        $query->bindParam('search',$search);

        $query->execute();
        return $query->fetchAll();
    }

    public static function totalPostsOfUser($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select count(*)
        from objava 
        where osoba=:id
        ');
        $query->execute(['id'=>$id]);
        return $query->fetchColumn();
    }

    public static function readAjax($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPageUser');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select a.sifra as \'id\',
        \'post\' as \'type\',
        a.naslov as \'text\'
        from objava a
        inner join osoba b on a.osoba=b.sifra
        where a.naslov like :search
        and b.aktivan!=false
        union 
        select sifra as \'id\',
        \'user\' as \'type\',
        concat(ime, \' \', prezime) as \'text\'
        from osoba
        where concat(ime, \' \', prezime) like :search
        and aktivan!=false
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
        return $query->fetch();
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
    public static function updated($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update objava
        set naslov=:posttitle,
        upis=:postdescription
        where sifra=:id
        ');
        $query->execute($parameters);
    }

    public static function postExists($postID)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select sifra from objava
        where sifra=:postID;
        ');
        $query->execute([
            'postID'=>$postID
        ]);
        $result=$query->fetchColumn();
        return $result>0;
    }

    public static function totalPosts($page=1)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select count(a.sifra)
        from objava a
        inner join osoba b on a.osoba=b.sifra
        where b.aktivan!=false
        ');
        $query->execute();
        return $query->fetchColumn();
    }

}

?>