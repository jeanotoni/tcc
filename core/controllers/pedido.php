<?php

// para definir que é um controller

namespace controllers;

class pedido extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\pedido();
    }

    public function init() {
        $dados['pedidos'] = $this->model->listar();
        $this->view('pedido', $dados);
    }

    public function salvar() {
        $input = file_get_contents('php://input');
        $dados = (array) json_decode($input);
        
//        $request['dataCriacao'] = date('d-m-y', strtotime($request['dataCriacao']));
        
        $rs = $this->model->salvar($dados);

        // quando apontar para o índice $data['id'] ele me retornará todos os dados do pedido
        // $data = array('id' => $rs);

        echo $this->toJson($rs);
        exit();
    }

    public function sellAnimal() {
        $input = file_get_contents('php://input');

        $dados = (array) json_decode($input);
        
        debug($dados);

        $this->model->sellAnimal($dados, $idPedido);
    }

    /*  ANTIGO MODO DE SALVAR SEM USAR ANGULAR
    public function add() {
        $dados = array();
        if (isset($_POST['salvarAnimal'])) {
            $dados = array(
                'apelido' => $_POST['apelido'],
                'dataNascimento' => $_POST['dataNascimento'],
                'custo' => $_POST['custo'],
                'statusVenda' => $_POST['statusVenda'],
                'observacao' => $_POST['observacao']
            );
            if (empty($_POST['id'])) {
                $rs = $this->model->salvar($dados);
                if ($rs > 0) {
                    $dados['feedback'] = "Animal INSERIDO com sucesso!";
                }
            } else {
                $rs = $this->model->editar($dados);
                if ($rs) {
                    $dados['feedback'] = "Animal EDITADO com sucesso!";
                }
            }
        }
    } */

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
                echo 'Animal excluido com sucesso!';
            } else {
                echo 'Falha ao deletar animal!';
            }
        }
    }

    public function listarClientes() {
        $rs = $this->model->listarClientes();

        echo $this->toJson($rs);
    }

}
