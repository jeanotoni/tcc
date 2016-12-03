<?php

function autoload($classe) {

//    echo $classe . ' - ';
    $name = explode("\\", $classe);
    $array = array('controllers', 'interfaces', 'models', 'utils', 'route', 'filters');
    if (!in_array($name[0], $array)) {
        return false;
    }

    if (count($name) == 2) {
        $path = CORE . strtolower($name[0]) . '/' . strtolower($name[1]) . '.php';
    } else {
        $path = CORE . 'system/' . $classe . '.php';
    }

    require_once($path);
}

spl_autoload_register("autoload");
