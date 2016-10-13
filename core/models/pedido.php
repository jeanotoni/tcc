<?php

/* ao invés do run() usar aqui é exec() para executar */

namespace models;

class pedido extends model implements \interfaces\model {
//    aqui seta-se a tabela que será usada neste model
//    function __construct() {
//        parent::__construct('pedido');
//    }

    /**
     * Método para salvar um animal na base de dados, verifica se não tiver um id é uma inserção, caso haja,
     * ele trata como uma edição.
     * @method salvar
     * @date 17/08/2016
     * @return no caso de inserção: retorna o último id inserido, e no caso de edição o id do animal editado
     */
    public function salvar($dados) {
        $this->setTable('pedido');
        if (empty($dados['model']->id)) {
            $this->insert($dados['model'])->exec();
            $rs = $this->getProperties();

            $this->sellAnimal($dados['itens'], $rs['lastId']);

            if ($rs['error'] === 0) {
                return $rs['lastId'];
            } else {
                return false;
            }
        } else {
            $id = $dados['id'];
            // Para quando for editar ele não tentar atualizar o id, pq senão vai dar pau
            unset($dados['id']);
            $w = array(
                "id = ?" => $id
            );
            $this->update($dados)->where($w)->exec();
            $rs = $this->getProperties();

            if ($rs['error'] == 0) {
                return $id;
            } else {
                return false;
            }
        }
    }

    /**
     * Método listar todos os animais da base ordenados por id
     * @method listar
     * @date 17/08/2016
     * @return $rs
     */
    public function listar() {
        $this->setTable('pedido');

        $o = 'pedido.id DESC';

        $s = array('pedido.*', 'cliente.nome as nomeCliente');

        $j = array(
            "table" => 'cliente',
            "cond" => 'cliente.id = pedido.idCliente'
        );

        $rs = $this->select($s)->join($j, 'LEFT')->orderBy($o)->exec('ALL');

        $data = $this->getProperties();
        if ($data['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Método para deletar um animal
     * @method salvar
     * @param $id
     * @date 17/08/2016
     * @return retorna true caso não dê erro
     */
    public function deletar($id) {
        $this->setTable('pedido');
        $w = array(
            "id = ?" => $id
        );

        $rs = $this->delete()->where($w)->exec();

        if ($rs['error']) {
            return false;
        } else {
            return true;
        }
    }

    // LISTAR CLIENTES
    public function listarClientes() {
        $this->setTable('cliente');

        $rs = $this->select()->exec('ALL');

        $data = $this->getProperties();

        if ($data['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Método para efetuar a venda de uma animal
     * @method sellAnimal
     * @date 17/08/2016
     * @return retorna true caso não dê erro
     */
    public function sellAnimal($dados, $idPedido) {
        foreach ($dados as $key => $value) {
            echo 'key: ' . $key . ' - ' . 'Value' . $value . '<br>';
            if ($value == 1) {
                $id = $key;
                $u = array(
                    "statusVenda" => 2
                );
                $w = array(
                    "id = ?" => $id
                );
                $this->setTable('animal');
                $rs = $this->update($u)->where($w)->exec();

                $this->addItem($id, $idPedido);
            }
        }
        if ($rs['error'] === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addItem($id, $idPedido) {

        $i = array(
            'idAnimal' => $id,
            'idPedido' => $idPedido
        );

        $this->setTable('pedidoitem');
        $this->insert($i)->exec();
    }

}
