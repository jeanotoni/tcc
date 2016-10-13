<?php

function autoload($classe){
//    echo $classe . ' - ';
    $name = explode("\\", $classe);
    if(count($name) == 2){
        $path = CORE . strtolower($name[0]) . '/' . strtolower($name[1]) . '.php';
    }else{
        $path = CORE . 'system/' . $classe . '.php';
    }
    
//    echo $path . '<br />';
    require_once($path);
}
spl_autoload_register("autoload");