<?php

class Log
{

    public static function info($what)
    {
        echo '<pre>';
        var_dump($what);
        echo '</pre>';
    }

}

?>