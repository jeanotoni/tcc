<?php

namespace controllers;

class racao extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\racao();
    }

    public function init() {
        $dados = array();

        if (isset($_POST['salvarRacao'])) {
            $dados = array(
                "nome" => $_POST['nome'],
                "descricao" => $_POST['descricao']
            );

            $rs = $this->model->salvar($dados);
            if ($rs > 0) {
                $dados['feedback'] = "Ração inserida com sucesso!";
            }
        }
        $dados['racoes'] = $this->model->listar();
//        debug($dados);
        $this->view('racao', $dados);
    }


//    public function listagem() {
//        $dados['racao'] = $this->model->listar();
//        $this->view('racao', $dados);
//    }

}
