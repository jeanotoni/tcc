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

    public function salvar() {
        $input = file_get_contents('php://input');
        $dados = (array) json_decode($input);
        
        $rs = $this->model->salvar($dados);
        $rs = array('id' => $rs);

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
//                'apelido' => $_POST['apelido'],
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

    public function deletar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id == null) {
            echo 'O id é nulo.';
        } else {
            $rs = $this->model->deletar($id);
            if ($rs) {
                echo 'Animal excluido com sucesso!';
            } else {
                echo 'Falha ao deletar animal!';
            }
        }
    }

    

}