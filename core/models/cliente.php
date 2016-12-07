<?php

namespace models;

class cliente extends model implements \interfaces\model {

    function __construct() {
        parent::__construct('cliente');
    }

    /**
     * Método para listar os clientes com ordenação id DESC 
     */
    public function listar() {
        $this->setTable('cliente');
        $rs = $this->select()->orderBy('id DESC')->exec('ALL');
        
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Método para listar id e nome do cliente que é somente o necessário para o modal de pedidos para listar
     * os clientes em um select
     */
    public function listarIdNome() {
        $s = array('id', 'nome');

        $this->setTable('cliente');
        $rs = $this->select($s)->orderBy('nome ASC')->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Método para salvar um vacina na base de dados, verifica se não tiver um id é uma inserção, caso haja,
     * ele trata como uma edição.
     * @method salvar
     * @date 11/10/2016
     * @return no caso de inserção: retorna o último id inserido, e no caso de edição o id do animal editado
     */
    public function salvar($request) {
        $this->setTable('cliente');
        if (empty($request['id'])) {

            $this->insert($request)->exec();
            $rs = $this->getProperties();

            if ($rs['error'] == 0) {
                return $rs['lastId'];
            } else {
                return false;
            }
        } else {
            $id = $request['id'];
            // para quando for editar ele não tentar atualizar o id, pq senão vai dar pau
            unset($request['id']);

            $w = array(
                "id = ?" => $id
            );
            $this->update($request)->where($w)->exec();

            $rs = $this->getProperties();

            if ($rs['error'] == 0) {
                return $id;
            } else {
                return false;
            }
        }
    }

    /**
     * Método que faz busca na tabela pedido para saber se há algum cliente vinculado à algum pedido
     * para que possa deletar-lo sem problemas posteriormente
     * @param $idCliente
     * @date 19/11/2016
     */
    public function getPedidoByCliente($idCliente) {
        $s = array('id');

        $w = array(
            'idCliente = ?' => $idCliente
        );

        $this->setTable('pedido');
        $this->select($s)->where($w)->exec('ALL');

        $info = $this->getProperties();

        if ($info['rowCount'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para deletar um cliente, verifica se o resultado do método getPedidoByCliente retornar 'true', significa
     * que não há nenhum pedido ligado a àquele cliente, e ele pode ser deletado normalmente
     * @param $idCliente
     * @date 19/11/2016
     */
    public function deletar($idCliente) {
        $verify = $this->getPedidoByCliente($idCliente);

        if ($verify) {
            $w = array(
                "id = ?" => $idCliente
            );

            $this->setTable('cliente');
            $this->delete()->where($w)->exec();

            $info = $this->getProperties();

            if ($info['error'] == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getEstado() {
        $this->setTable('estado');
        $rs = $this->select()->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }
    
    public function getCidadeByEstado($idEstado){
        $this->setTable('cidade');
        
        $w = array(
            'idEstado = ?' => $idEstado
        );
        
        $rs = $this->select()->where($w)->exec('ALL');
        
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

}
