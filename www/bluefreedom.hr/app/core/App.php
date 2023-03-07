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
        if(!(class_exists($controller) && method_exists($controller,$method)))
        {
            echo 'NON EXISTANCE OF ' . $controller . '-&gt;' . $method;
            return;
        }
        $instance=new $controller();
        $instance->$method();
    }

    public static function configuration($key)
    {
        $configFile=BP_APP.'config.php';
        if(!(file_exists($configFile)))
        {
            return 'Config file does not exist or the path is not valid!';
        }

        $configuration=require $configFile;

        if(!isset($configuration[$key]))
        {
            return 'Key ' . $key . 'is not set up in configuration file';
        }

        return $configuration[$key];
    }

    public static function auth()
    {
        return isset($_SESSION['auth']);
    }

    public static function operator()
    {
        return $_SESSION['auth']->ime . ' ' . $_SESSION['auth']->prezime;
    }

    public static function admin()
    {
        return $_SESSION['auth']->role==='admin';
    }
}

?>