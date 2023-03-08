<?php

class ControlpanelController extends AuthorisationController
{
    public function index()
    {
        $this->view->render('private'.DIRECTORY_SEPARATOR.'ControlPanel');
    }

}

?>