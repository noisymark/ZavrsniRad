<?php

class App
{
    public static function start()
    {
        $route=Request::getRoute();
        $parts=explode('/',substr($route,1));
        $controller='';
        if(!isset($parts[0]) || $parts[0]==='')
        {
            $controller='IndexController';
        }
        else
        {
            $controller=ucfirst($parts[0].'Controller');
        }

        $method='';
        if(!isset($parts[1]) || $parts[1]==='')
        {
            $method='index';
        }
        else
        {
            $method=$parts[1];
        }

        if(class_exists($controller) && method_exists($controller,$method))
        {
            $instance = new $controller();
            $instance->$method();
        }
        else
        {
            echo 'NON EXISTANCE OF ' . $controller . '-&gt' . $method;
        }
    }
    public static function config($key)
    {
        $configFile = BP_APP . 'config.php';

        if(!file_exists($configFile)){
            return 'Configuration file does not exist';
        }

        $config = require $configFile;

        if(!isset($config[$key])){
            return 'Key ' . $key . ' is not set up in configuration file';
        }

        return $config[$key];

    }

    public static function auth()
    {
        return isset($_SESSION['auth']);
    }

    public static function operater()
    {
        return $_SESSION['auth']->ime . ' ' . $_SESSION['auth']->prezime;
    }

    public static function admin()
    {
        return $_SESSION['auth']->uloga==='admin';
    }

    public static function oper()
    {
        return $_SESSION['auth']->uloga==='oper';
    }

    public static function dev()
    {
        return App::config('dev') ;
    }
}

?>