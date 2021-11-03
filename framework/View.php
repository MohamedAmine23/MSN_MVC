<?php

require_once 'Configuration.php';

class View
{

    private $file;

    public function __construct($action)
    {
        $this->file = "view/view_$action.php";
    }

    //affiche la vue en lui passant les données reçues
    //sous forme de variables
    public function show($data = array())
    {
        if (file_exists($this->file)) {
            extract($data);
            $web_root = Configuration::get("web_root");
            require $this->file;
        } else {
            throw new Exception("File '$this->file' does'nt exist");
        }
    }

}
