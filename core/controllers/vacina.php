<?php
// para definir que é um controller
namespace controllers;

class vacina extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\vacina();
    }

    public function init() {
//        $dados['vacina'] = $this->model->listar();
        $this->view('vacina');
    }

    public function salvar() {
        $input = file_get_contents('php://input');
        $dados = (array) json_decode($input);

        $rs = $this->model->salvar($dados);

        $rs = array('id' => $rs);

        echo $this->toJson($rs);
    }

//    public function insertMultiple() {
//        $input = file_get_contents('php://input');
//
//        $dados = (array) json_decode($input);
//
//        $rs = $this->model->insertMultiple($dados);
//
//        echo $this->toJson($rs);
//    }

    public function listar() {
        $dados = $this->model->listar();
        echo $this->toJson($dados);
    }

    public function deletar() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id == null) {
            echo 'O id é nulo.';
        } else {
            $rs = $this->model->deletar($id);
            if ($rs) {
                echo 'Vacina excluida com sucesso!';
            } else {
                echo 'Falha ao deletar Vacina!';
            }
        }
    }
  
}
