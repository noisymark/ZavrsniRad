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
    public static function readAjax($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPageUser');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select sifra as \'id\',
        \'post\' as \'type\',
        naslov as \'text\'
        from objava
        where naslov like :search
        union 
        select sifra as \'id\',
        \'user\' as \'type\',
        concat(ime, \' \', prezime) as \'text\'
        from osoba
        where concat(ime, \' \', prezime) like :search
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

}

?>