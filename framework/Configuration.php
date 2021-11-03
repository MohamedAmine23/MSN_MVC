<?php

class Configuration
{

    private static $parameters;

    //renvoie le paramètre demandé
    public static function get($name, $defaultValue = null)
    {
        if (isset(self::get_parameters()[$name])) {
            $value = self::get_parameters()[$name];
        } else {
            $value = $defaultValue;
        }
        return $value;
    }

    //renvoie les paramètres.
    //les paramètres de dev sont renvoyés s'ils existent.
    //sinon, renvoie les paramètres de prod
    private static function get_parameters()
    {
        if (self::$parameters == null) {
            $file_path = "config/dev.ini";
            if (!file_exists($file_path)) {
                $file_path = "config/prod.ini";
            }
            if (!file_exists($file_path)) {
                throw new Exception("Config file is missing.");
            } else {
                self::$parameters = parse_ini_file($file_path);
            }
        }
        return self::$parameters;
    }

}
