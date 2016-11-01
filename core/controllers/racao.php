<?php

namespace controllers;

class racao extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\racao();
    }

    public function init() {
        $this->view('racao');
    }
    
    public function salvar(){
        $input = file_get_contents('php://input');
        $request = (array) json_decode($input);
        
        $rs = $this->model->salvar($request);
        $rs = array('id' => $rs);
        
        echo $this->toJson($rs);
    }


    public function listar() {
        $rs = $this->model->listar();
        
        echo $this->toJson($rs);
    }

}
