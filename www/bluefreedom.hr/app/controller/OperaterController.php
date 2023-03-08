<?php

class OperaterController extends AdminController
{
    private $viewPath = 'private'.DIRECTORY_SEPARATOR.'operaters'.DIRECTORY_SEPARATOR;

    public function index()
    {
        $operaters=Operater::read();
        foreach ($operaters as $o)
        {
            unset($o->lozinka);
        }

        $this->view->render($this->viewPath . 'index',[
            'info'=>$operaters,
            'css'=>'users.css'
        ]);
    }
}

?>