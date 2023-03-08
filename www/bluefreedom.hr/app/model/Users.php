<?php

class Users
{
    public static function read()
    {
        $connection=DB::getInstance();
        $query=$connection->prepare('
        select * from osoba
        order by ime asc
        ');

        $query->execute();
        return $query->fetchAll();
    }
}

?>