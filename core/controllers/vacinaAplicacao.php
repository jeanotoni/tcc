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

    public function getDadosAplicacao() {
        $input = file_get_contents('php://input');
        $request = (array) json_decode($input);

        $rs = $this->model->getAplicacaoByIdAnimal($request['idAnimal']);

        echo $this->toJson($rs);
    }

    public function getAnimalSelected() {
        if (!empty($_GET['id']) && (int) $_GET['id'] > 0) {
            $id = $_GET['id'];
            $rs = array(
                'selected' => $this->model->getAnimalSelected($id)
            );
        }

        echo $this->toJson($rs);
    }
    
    public function listExport(){
        $rs = $this->model->listar();

        return $rs;
    }

    public function exportar() {
        $rs = $this->listExport();

        $title = 'Relatório de Aplicação de Vacinas';

        $export = new \utils\pdf();
        $export->export($rs, $title);
    }

}
