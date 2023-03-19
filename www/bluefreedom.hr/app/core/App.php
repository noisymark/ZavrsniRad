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

        $parameter='';
        if(!isset($parts[2]) || $parts[2]==='')
        {
            $parameter='';
        }
        else
        {
            $parameter=$parts[2];
        }

        if(!(class_exists($controller) && method_exists($controller,$method)))
        {
            //echo 'NON EXISTANCE OF ' . $controller . '-&gt' . $method;
            $v = new View();
            $v->render('notFound');
        }

        $instance = new $controller();

        if(strlen($parameter)>0){
            $instance->$method($parameter);
        }
        else
        {
            $instance->$method();
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