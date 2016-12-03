<?php

/* ao invés do run() usar aqui é exec() para executar */

namespace models;

class pedido extends model implements \interfaces\model {

//    aqui seta-se a tabela que será usada neste model
    function __construct() {
        parent::__construct('pedido');
    }

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
            //validação para não dar erro caso o usuário não selecione nenhum animal

            if (!empty($dados['itens'])) {
                $this->sellAnimal($dados['itens'], $rs['lastId']);
            }

            if ($rs['error'] == 0) {
                return $rs;
            } else {
                return false;
            }
        } else {
            /**
             * O id é necessário para que quando for editar ele não tente atualizar o id, pq senão vai dar pau
             * E o nome do cliente pois no método de listar eu envio ele junto com o resultado, e na hora de abrir o 
             * modal de edição, ele vem junto com o angular.copy(pedido), porém é um campo que não existe no banco
             */
            $id = $dados['model']->id;
            unset($dados['model']->id);
            unset($dados['model']->cliente);

            // Chama método somente se estiver algum animal selecionado
            if (!empty($dados['itens'])) {
                $this->sellAnimal($dados['itens'], $id);
            }

            // Se o pedido estiver cancelado(3) ou estornado(4) e clicar em salvar abre-o novamente (OPÇÃO DESCARTADA)
            // if ($dados['model']->situacao == 3 || $dados['model']->situacao == 4) {
            // $dados['model']->situacao = 1;
            // }

            $w = array(
                "id = ?" => $id
            );

            $this->setTable('pedido');
            $this->update($dados['model'])->where($w)->exec();

            $rs = $this->getProperties();

            if ($rs['error'] == 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * updateStatusPedido
     * @date 05/11/2016
     * Usado para alterar o status de um pedido para pago, estornado ou cancelado
     */
    public function updateStatusPedido($request) {
        $u = array(
            'situacao' => $request['situacao']
        );
        $w = array(
            'id = ?' => $request['model']->id
        );

        $this->setTable('pedido');
        $this->update($u)->where($w)->exec();
        $rs = $this->getProperties();

        if ($rs['error'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para estornar pedido, tem de ser separado pois além de alterar o seu status para estornado
     * ele tem de remover seus itens da tabela pedidoIem e alterar o statusVenda do animal na tabela animal.
     * @param $request
     */
    public function estornarCancelarPedido($request) {
        /* --------------------------------------------------------*\
         * Altera situação do pedido para estornado(4)
         * -------------------------------------------------------- */
        $u = array(
            'situacao' => $request['situacao']
        );
        $w = array(
            'id = ?' => $request['model']->id
        );

        $this->setTable('pedido');
        $this->update($u)->where($w)->exec();

        /* --------------------------------------------------------------------------------------*\
         * Itera sob os itens alterando seus status para 0 e os removendo da tabela pedidoItem
         * -------------------------------------------------------------------------------------- */
        $us = array(
            'statusVenda' => 0
        );
        if (!empty($request['itens'])) {
            foreach ($request['itens'] as $idAnimal => $value) {
                $ws = array(
                    'id = ?' => $idAnimal
                );

                $this->setTable('animal');
                $this->update($us)->where($ws)->exec();

                $wd = array(
                    'idAnimal = ?' => $idAnimal
                );

                $this->setTable('pedidoItem');
                $this->delete()->where($wd)->exec();
            }
        }
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método listar todos os animais da base ordenados por id
     * @method listar
     * @date 17/08/2016
     * @return $rs
     */
    public function listar() {
        $o = 'pedido.id DESC';

        $s = array(
            'pedido.*', 'cliente.nome as cliente',
            '(select SUM(valorUnitario) from pedidoitem where idPedido = pedido.id) as valorTotal'
        );
        $j = array(
            "table" => 'cliente',
            "cond" => 'cliente.id = pedido.idCliente'
        );

        $this->setTable('pedido');
        $rs = $this->select($s)->join($j)->orderBy($o)->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
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
     * Caso value seja = 1 $add recebe true para indicar no método addItem que aquele registro será inserido 
     * no pedido e seu status de venda passa para 1, caso seja = 0 indica que aquele registro será somente 
     * deletado do pedido e seu status de venda volta a ser 0 como de padrão.
     * @date 17/08/2016
     */
    public function sellAnimal($dados, $idPedido) {
        foreach ($dados as $key => $value) {
            $add = false;
            // Para não sair executando e alterando o status dos animais não marcados e para entrar quando remover um animal do pedido
            if ($value->status == 1) {
                $add = true;
                $u = array(
                    "statusVenda" => 1
                );
            } else if ($value->status == 0) {
                $add = false;
                $u = array(
                    "statusVenda" => 0
                );
            }
            if (isset($u)) {
                $w = array(
                    "id = ?" => $key
                );
                // Faz a alteração no status de venda do animal
                $this->setTable('animal');
                $this->update($u)->where($w)->exec();

                // Chama método que adiciona o animal na tabela pedidoItem
                $this->addItem($key, $idPedido, $add, $value->valorUnitario);
            }
        }

        $rs = $this->getProperties();
        if ($rs['error'] === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método addItem primeiramente del
     * @param type $idAnimal
     * @param type $idPedido
     * @param type $add
     */
    public function addItem($idAnimal, $idPedido, $add, $valorUnitario) {
        $w = array(
            'idAnimal = ?' => $idAnimal,
            'idPedido = ?' => $idPedido
        );
        $this->setTable('pedidoitem');
        $this->delete()->where($w)->exec();

        if ($add == true) {
            $i = array(
                'idAnimal' => $idAnimal,
                'idPedido' => $idPedido,
                'valorUnitario' => $valorUnitario
            );

            $this->insert($i)->exec();
        }
    }

    /**
     * Método faz duas coisas, primeira: busca todos os animais cujo idPedido seja o passado por parâmetro OU cujo
     * statusVenda seja (0). Segunda: após a consulta verifica quais são os respectivos animais desse mesmo pedido
     * para que assim eu possa traze-los selecionados ao abrir o modal
     * @param $idPedido
     * @return boolean|int
     */
    public function getAnimalByPedido($idPedido) {
        $s = array('animal.id');

        $j = array(
            'table' => 'pedidoItem',
            'cond' => 'animal.id = pedidoItem.idAnimal'
        );

        $w = array(
            'animal.statusVenda = 0 OR pedidoItem.idPedido = ?' => $idPedido
        );

        $this->setTable('animal');
        $rs = $this->select($s)->join($j)->where($w)->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Verifica quais animais são deste pedido para atribuí-los em $selected[idDoAnimal][status] recebendo 1
     * E traz o valorUnitario de cada animal e atribui em $selected[idDoAnimal][valorUnitario] para poder listar 
     * no modal na hora de editar um pedido
     */
    public function getAnimalSelected($idPedido) {
        $w = array(
            'idPedido = ?' => $idPedido
        );

        $this->setTable('pedidoItem');
        $arrAnimais = $this->select()->where($w)->exec('ALL');
        $info = $this->getProperties();

        $selecteds = null;
        if (!empty($arrAnimais)) {
            foreach ($arrAnimais as $animal) {
                $selecteds[$animal['idAnimal']]['status'] = 1;
                $selecteds[$animal['idAnimal']]['valorUnitario'] = $animal['valorUnitario'];
            }
        }

        if ($info['error'] == 0) {
            return $selecteds;
        } else {
            return false;
        }
    }

    /**
     * Realiza a soma do valorUnitario de cada item de um pedido e retorna o valor total
     * @param $idPedido
     */
    public function getValorTotal($idPedido) {
        $s = array(
            'SUM(valorUnitario) as valorTotal'
        );
        $w = array(
            'idPedido = ?' => $idPedido
        );

        $this->setTable('pedidoItem');
        $rs = $this->select($s)->where($w)->exec('ROW');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * - Método usado para pegar todos os animais de acordo com o pedido selecionado, para que ao abrir para edição
     * os animais daquele pedido já venham preenchidos no modal
     * - Após o select, tenho um array com os ids dos animais, feito isso faço um foreach para atribuir os id como
     * índice da variável $rs recebendo valor de 1(que é utilizado no checkbox para marcar um animal como selecionado)
     * @date 05/11/2016
     * @param $idPedido
     */
//    public function getAnimalByPedido($idPedido) {
//        $s = array('idAnimal');
//        $w = array(
//            "idPedido = ?" => $idPedido
//        );
//
//        $this->setTable('pedidoItem');
//        $arrAnimais = $this->select($s)->where($w)->exec('ALL');
//        $info = $this->getProperties();
//
//        $rs = array();
//        foreach ($arrAnimais as $animal) {
//            $rs[$animal['idAnimal']] = 1;
//        }
//
//        if (empty($info['error'])) {
//            return $rs;
//        } else {
//            return false;
//        }
//    }
}
