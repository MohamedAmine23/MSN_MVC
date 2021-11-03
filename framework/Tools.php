<?php

require_once 'View.php';

class Tools
{

    //nettoie le string donné
    public static function sanitize($var)
    {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlspecialchars($var);
        return $var;
    }

    //dirige vers la page d'erreur
    public static function abort($err)
    {
        (new View("error"))->show(array("error" => $err));
        die;
    }

    //renvoie le string donné haché.
    public static function my_hash($password)
    {
        $prefix_salt = "vJemLnU3";
        $suffix_salt = "QUaLtRs7";
        return md5($prefix_salt . $password . $suffix_salt);
    }

}
