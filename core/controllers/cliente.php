<?php

// para definir que é um controller

namespace controllers;

class cliente extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\cliente();
    }

    public function init() {
        $dados['cliente'] = $this->model->listar();
        $this->view('cliente', $dados);
    }
    
    public function listar(){
        $dados = $this->model->listar();
        
        echo $this->toJson($dados);
    }

}
