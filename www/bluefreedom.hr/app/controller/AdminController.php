<?php

abstract class AdminController extends AuthorisationController
{
    public function __construct()
    {
        parent::__construct();
        if(!App::admin())
        {
            $this->view->render('private'.DIRECTORY_SEPARATOR.'controlPanel');
        }
    }
}

?>