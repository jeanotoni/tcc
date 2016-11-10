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
            if(!empty($dados['itens'])){
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
            unset($dados['model']->nomeCliente);
            
            /////// ENCONTRAR UM JEITO DE VALIDAR A EDIÇÃO PARA QUE NÃO INSIRA DENOVO OS QUE JÁ ESTÃO PREENCHIDOS
            $this->sellAnimal($dados['itens'], $id);
            
            // Se o pedido estiver cancelado(3) ou estornado(4) e clicar em salvar abre-o novamente
            if ($dados['model']->situacao == 3 || $dados['model']->situacao == 4) {
                $dados['model']->situacao = 1;
            }
            
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
            if ($value == 1) {
                $idAnimal = $key;
                $u = array(
                    "statusVenda" => 1
                );
                $w = array(
                    "id = ?" => $idAnimal
                );
                $this->setTable('animal');
                $this->update($u)->where($w)->exec();
                
                $this->addItem($idAnimal, $idPedido);
                
                $rs = $this->getProperties();
            }
        }
        
        if ($rs['error'] === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addItem($idAnimal, $idPedido) {
        $this->setTable('pedidoitem');
        
        $w = array(
            'idAnimal = ?' => $idAnimal,
            'idPedido = ?' => $idPedido
        );
        
        $checkAnimal = $this->select('id')->where($w)->exec('ROW');
        
        if(!empty($checkAnimal['id'])){
            return false;
        }
        
        $i = array(
            'idAnimal' => $idAnimal,
            'idPedido' => $idPedido
        );

        $this->insert($i)->exec();
    }

    /**
     * Método faz duas coisas, primeira: busca todos os animais cujo idPedido seja o passado por parâmetro OU cujo
     * statusVenda seja (0). Segunda: após a consulta verifica quais são os respectivos animais desse mesmo pedido
     * para que assim eu possa traze-los selecionados ao abrir o modal
     * @param $idPedido
     * @return boolean|int
     */
    public function listAnimalByPedido($idPedido) {
        $s = array(
            'animal.id',
            'pedidoItem.idPedido'
        );
        $j = array(
            'table' => 'pedidoItem',
            'cond' => 'animal.id = pedidoItem.idAnimal'
        );
        $w = array(
            'pedidoItem.idPedido = ?' => $idPedido,
            'animal.statusVenda = ?' => 0
        );
        
        $this->setTable('animal');
        $arrAnimais = $this->select($s)->join($j)->where($w, 'OR')->exec('ALL');
        $info = $this->getProperties();
        
        // Verifica quais animais são deste pedido para atribuí-los em $selected
        $selecteds = null;
        foreach ($arrAnimais as $animal) {
            if (!empty($animal['idPedido'])) {
                $selecteds[$animal['id']] = 1;
            }
        }
        
        $rs = array(
            'selecteds' => $selecteds,
            'arrAnimais' => $arrAnimais
        );

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
    public function getAnimalByPedido($idPedido) {
        $s = array('idAnimal');
        $w = array(
            "idPedido = ?" => $idPedido
        );

        $this->setTable('pedidoItem');
        $arrAnimais = $this->select($s)->where($w)->exec('ALL');
        $info = $this->getProperties();

        $rs = array();
        foreach ($arrAnimais as $animal) {
            $rs[$animal['idAnimal']] = 1;
        }

        if (empty($info['error'])) {
            return $rs;
        } else {
            return false;
        }
    }

}
