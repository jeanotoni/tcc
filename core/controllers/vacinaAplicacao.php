<?php

// para definir que é um controller

namespace controllers;

class vacinaAplicacao extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\vacinaAplicacao();
    }

    public function init() {
        $this->view('vacinaAplicacao');
    }

    // TRAZ DADOS DAS APLICAÇÕES DE VACINAS
    public function getVaccineApplication() {
        $rs = $this->model->getVaccineApplication();

        echo $this->toJson($rs);
    }

    // ADICIONA/SALVA APLICAÇÃO DE VACINAS
    public function vaccinateAnimals() {
        $input = file_get_contents('php://input');
        $request = (array) json_decode($input);

        $rs = $this->model->vaccinateAnimals($request);

        echo $this->toJson($rs);
    }
    
    public function getDadosAplicacao(){
        $input = file_get_contents('php://input');
        $request = (array) json_decode($input);
        
        $rs = $this->model->getDadosAplicacao($request);
        
        echo $this->toJson($rs);
    }

}
