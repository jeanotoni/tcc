<?php

namespace route;

$route = new Route();

// ANIMAL
$route->setRoute('animal/deletar', array(
    'id' => 'integerFilter'
//    'order' => null
));

// RAÇÃO
$route->setRoute('racao/listRacaoByAnimal', array(
    'id' => 'integerFilter'
));
$route->setRoute('racao/deletar', array(
    'id' => 'integerFilter'
));

// CLIENTE
$route->setRoute('cliente/deletar', array(
    'id' => 'integerFilter'
));
$route->setRoute('cliente/getCidadeByEstado', array(
    'id' => 'integerFilter'
));


// PEDIDO
$route->setRoute('pedido/getAnimalByPedido', array(
    'id' => 'integerFilter'
));
$route->setRoute('pedido/listAnimalByPedido', array(
    'id' => 'integerFilter'
));
//$route->setRoute('/pedido/getValorTotal', array(
//    'id' => 'integerFilter'
//));

// VACINA - VACINA APLICAÇÃO
$route->setRoute('vacina/deletar', array(
    'id' => 'integerFilter'
));
$route->setRoute('vacinaAplicacao/getAnimalSelected', array(
    'id' => 'integerFilter'
));


$route->setRoute('animal/exportar');


//$route->setRoute('animal/details', array(
//    'id' => 'integerFilter'
//));
//$route->setRoute('cliente/deletar', array(
//    'id' => 'integerFilter'
//));

$route->init();