<?php

abstract class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!App::admin())
        {
            $this->view->render('private'.DIRECTORY_SEPARATOR.'ControlPanel');
        }
    }
}

?>