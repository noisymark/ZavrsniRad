<?php

class Comments
{
    public static function read($search='',$page=1)
    {
        $search='%' . $search . '%';
        $resultsPerPage=App::config('resultsPerPage');
        $start=($page*$resultsPerPage)-$resultsPerPage;
        $connection=DB::getInstance();
        $query = $connection->prepare('
        select 
        CONCAT(e.ime, \' \', e.prezime) as commenter,
        b.sifra as commentID,
        b.opis as comment
        from objava a
        inner join komentar b
        on b.objava = a.sifra  
        inner join osoba d 
        on a.osoba = d.sifra
        inner join osoba e 
        on b.osoba = e.sifra 
        where concat(e.ime, \' \' , e.prezime, \' \', b.opis, \' \', b.sifra)
        like :search
        and e.aktivan!=false
        order by
        e.ime,
        e.prezime,
        b.sifra,
        b.opis
        limit :start, :resultsPerPage
        ');
        $query->bindValue('start',$start,PDO::PARAM_INT);
        $query->bindValue('resultsPerPage',$resultsPerPage,PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();
        return $query->fetchAll();
    }

    public static function readOne($sifra)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select opis from komentar
        where sifra=:sifra
        ');
        $query->execute([
            'sifra'=>$sifra
        ]);
        return $query->fetch();
    }

    public static function update($parameters)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        update komentar
        set opis=:opis
        where sifra=:sifra
        ');
        $query->execute($parameters);
    }

    public static function totalComments($search='')
    {
        $search = '%' . $search . '%';
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select 
        count(*)
        from komentar a
        inner join osoba b
        on a.osoba = b.sifra
        where concat(b.ime, \' \', b.prezime, \' \', a.opis, \' \', a.sifra)
        like :search
        ');
        $query->execute([
            'search'=>$search
        ]);

        return $query->fetchColumn();
    }
    public static function delete($id)
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        delete from komentar
        where sifra=:id
        ');
        $query->bindParam('id',$id);
        $query->execute();
    }
}