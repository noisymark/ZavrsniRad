<?php

abstract class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function setCSSdependency($dependency){
        $this->view->setCSSdependency($dependency);
    }

    protected function setJSdependency($dependency){
        $this->view->setJSdependency($dependency);
    }
}

?>