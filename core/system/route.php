<?php

namespace route;

$route = new Route();

$route->setRoute('animal/deletar', array(
    'id' => 'integerFilter'
//    'order' => null
));

$route->setRoute('vacina/deletar', array(
    'id' => 'integerFilter'
));

//$route->setRoute('cliente/deletar', array(
//    'id' => 'integerFilter'
//));


$route->init();