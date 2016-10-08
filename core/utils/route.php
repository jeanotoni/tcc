<?php

    namespace route;
    
    $path = array(
        'url' => 'ver/:id',
        'controller' => 'clientes',
        'action' => 'ver',
        'params' => array(
            'id' => 'int'
        )
    );
    
    $route = route\Route::route($path);
