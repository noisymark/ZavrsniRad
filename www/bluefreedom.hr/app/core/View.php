<?php

class View
{
    private $template;

    private $CSSdependency=null;
    private $JSdependency=null;

    public function __construct($template='template')
    {
        $this->template=$template;
    }

    public function render($phtmlPage,$parameters=[])
    {
        $cssFile= BP. 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $phtmlPage . '.css';
        if(file_exists($cssFile))
        {
            $css=str_replace('\\','/',$phtmlPage).'.css';
        }

        if($this->CSSdependency!=null)
        {
            $cssdependency= $this->CSSdependency;
        }
        if($this->JSdependency!=null)
        {
            $jsdependency= $this->JSdependency;
        }

        $viewFile = BP_APP . 'view' . DIRECTORY_SEPARATOR . $phtmlPage . '.phtml';
        ob_start();
        extract($parameters);
        if(file_exists($viewFile))
        {
            include_once $viewFile;
        }
        else
        {
            include_once BP_APP . 'view' . DIRECTORY_SEPARATOR . 'errorPage.phtml';
        }

        $contents = ob_get_clean();
        include_once BP_APP . 'view' . DIRECTORY_SEPARATOR .  $this->template . '.phtml';
    }

    public function setCSSdependency($dep)
    {
        $this->CSSdependency=$dep;
    }
    public function setJSdependency($dep)
    {
        $this->JSdependency=$dep;
    }
}

?>