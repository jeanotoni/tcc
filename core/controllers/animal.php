<?php

// para definir que é um controller

namespace controllers;

class animal extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\animal();
    }

    public function init() {
//        $dados['animais'] = $this->model->listar();
        $this->view('animal/animal');
    }

//    public function details(){
//        $id = (isset($_GET['id']) ? $_GET['id'] : null);
//        
//        $this->view('animal/animalDetails');
//        
//        echo 'oi';
//    }
//    public function getAnimalById(){
//        $id = (isset($_GET['id']) ? $_GET['id'] : null);
//        
//        $rs = $this->model->getAnimalById($id);
//        
//        debug($rs);
//        
//        echo $this->toJson($rs);
//    }

    /**
     * Método para salvar o animal, na resposta envio o lastId que pode ser tanto o id de uma inserção ou de uma edição
     * e abaixo a propriedade 'updated' para indicar se é inserção ou edição para receberem tratamentos diferentes no controller.js
     */
    public function salvar() {
        $input = file_get_contents('php://input');
        $dados = (array) json_decode($input);

        $info = $this->model->salvar($dados);

        $rs = array(
            'id' => $info['lastId'],
            'updated' => $info['updated']
        );

        echo $this->toJson($rs);
    }

    public function insertMultiple() {
        $input = file_get_contents('php://input');
        $dados = (array) json_decode($input);

        $rs = $this->model->insertMultiple($dados);

        echo $this->toJson($rs);
    }

//    ANTIGO MODO DE SALVAR SEM USAR ANGULAR
//    public function add() {
//        $dados = array();
//        if (isset($_POST['salvarAnimal'])) {
//            $dados = array(
//                'dataNascimento' => $_POST['dataNascimento'],
//                'custo' => $_POST['custo'],
//                'statusVenda' => $_POST['statusVenda'],
//                'observacao' => $_POST['observacao']
//            );
//            if (empty($_POST['id'])) {
//                $rs = $this->model->salvar($dados);
//                if ($rs > 0) {
//                    $dados['feedback'] = "Animal INSERIDO com sucesso!";
//                }
//            } else {
//                $rs = $this->model->editar($dados);
//                if ($rs) {
//                    $dados['feedback'] = "Animal EDITADO com sucesso!";
//                }
//            }
//        }
//    }

    public function listar() {
        $rs = $this->model->listar();
        echo $this->toJson($rs);
    }

    public function listAll() {
        $rs = $this->model->listAll();
        echo $this->toJson($rs);
    }
    
    public function listExport() {
        $rs = $this->model->listAll();
        return $rs;
    }

    public function deletar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        $rs = $this->model->deletar($id);

        if ($rs) {
            return true;
        } else {
            return false;
        }
    }

    public function exportar() {
        $rs = $this->listExport();
        
        $title = 'Relatório de Animais';
        
        $export = new \utils\pdf();
        $export->export($rs, $title);
    }

}
