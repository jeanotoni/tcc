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
        $request = (array) json_decode($input);

        $rs = $this->model->salvar($request);

        // quando apontar para o índice $data['id'] ele me retornará todos os dados do pedido
        // $data = array('id' => $rs);

        echo $this->toJson($rs);
        exit();
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

    /**
     * Usado para alterar o status de um pedido de aberto para pago, cancelado e estornado
     */
    public function updateStatusPedido() {
        $input = file_get_contents('php://input');
        $request = (array) json_decode($input);

        $rs = $this->model->updateStatusPedido($request);

        echo $this->toJson($rs);
    }

    /**
     * Usado para pegar todos os animais de um determinado pedido e trazer os que são deles já selecionados
     */
    public function listAnimalByPedido() {
        if (!empty($_GET['id']) && (int) $_GET['id'] > 0) {
            $rs = $this->model->listAnimalByPedido($_GET['id']);
        }
        echo $this->toJson($rs);
    }

    /**
     * Usado para listar os pedidos
     */
    public function listar() {
        $dados = $this->model->listar();
        echo $this->toJson($dados);
    }

    /**
     * Usado para deletar um pedido
     */
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
