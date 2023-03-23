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
        a.sifra as sifrakorisnika,
        b.sifra as sifraobjave,
        b.naslov as naslovobjave,
        b.upis as upisobjave
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

    public static function delete($sifra)
    {
        $connection = DB::getInstance();
        $query = $connection->prepare('
            delete *
            from objava
            where sifra=:sifra
        ');
        $query->execute([
            'sifra'=>$sifra
        ]);
        $query->execute();
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