<?php

class Log
{

    public static function info($what)
    {
        echo '<pre>';
        var_dump($what);
        echo '</pre>';
    }

    public static function time()
    {
        $now = new DateTime();
        return $now->format('Y-m-d H:i:s'); 
    }

}

?>