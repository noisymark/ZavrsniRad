<?php

class ControlPanelController extends AuthorisationController
{
    public function index()
    {
        $this->view->render('private'.DIRECTORY_SEPARATOR.'controlPanel');
    }
}

?>