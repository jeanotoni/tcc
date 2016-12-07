<?php

// para definir que é um controller

namespace controllers;

class cliente extends controller implements \interfaces\controller {

    private $model;

    function __construct() {
        $this->model = new \models\cliente();
    }

    public function init() {
        $dados['cliente'] = $this->model->listar('id DESC');
        $this->view('cliente', $dados);
    }

    // Lista todos os dados do cliente
    public function listar() {
        $rs = $this->model->listar();

        echo $this->toJson($rs);
    }
    
    public function listExport() {
        $rs = $this->model->listar();

        return $rs;
    }

    // Lista id e nome do cliente(usado no modal de novo pedido como <select>)
    public function listarIdNome() {
        $rs = $this->model->listarIdNome();

        echo $this->toJson($rs);
    }

    public function salvar() {
        $input = file_get_contents('php://input');
        $request = (array) json_decode($input);

        $result = $this->model->salvar($request);

        $rs = array('id' => $result);

        echo $this->toJson($rs);
    }

    public function deletar() {
        $idCliente = isset($_GET['id']) ? $_GET['id'] : null;

        $rs = $this->model->deletar($idCliente);

        if ($rs) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ESTADOS / CIDADES
     */
    public function getEstado() {
        $rs = $this->model->getEstado();

        echo $this->toJson($rs);
    }

    public function getCidadeByEstado() {
        $idEstado = isset($_GET['id']) ? $_GET['id'] : null;

        $rs = $this->model->getCidadeByEstado($idEstado);

        echo $this->toJson($rs);
    }
    
    public function exportar() {
        $rs = $this->listExport();

        $title = 'Relatório de Clientes';

        $export = new \utils\pdf();
        $export->export($rs, $title);
    }

}
