<?php

/* ao invés do run() aqui usa exec() para executar */

namespace models;

class animal extends model implements \interfaces\model {

    /**
     * statusVenda:
     * 1: aberto
     * 2: vendido
     */
//    aqui seta-se a tabela que será usada neste model animal
    function __construct() {
        parent::__construct('animal');
    }

    /**
     * Método para salvar um animal na base de dados, verifica se não tiver um id é uma inserção, caso haja,
     * ele trata como uma edição.
     * @method salvar
     * @date 17/08/2016
     * @return no caso de inserção: retorna o último id inserido, e no caso de edição o id do animal editado
     */
    public function salvar($dados) {
        // Usa método para tratar data e pegar somente a parte que seja de fato data e ignorar o restante que são as horas
        $dados['dataNascimento'] = $this->tratarData($dados['dataNascimento']);

        if (empty($dados['id'])) {
            $this->insert($dados)->exec();
            $rs = $this->getProperties();
                        
            $rs['updated'] = false;

            if ($rs['error'] == 0) {
                return $rs;
            } else {
                return false;
            }
        } else {
            /*
             * - Dou o unset para quando for editar ele não tentar atualizar o id, pq senão vai dar erro
             * - Atribuo o id do animal alterado para padronizar no controller para que ambos sempre tenham a propriedade
             *   lastId idependente se foi inserida ou alterada, o que vai diferenciar isso será a propriedade updated passada como parâmetro na inserção acima
             */
            $id = $dados['id'];
            unset($dados['id']);

            $w = array(
                "id = ?" => $id
            );

            $this->update($dados)->where($w)->exec();
            $rs = $this->getProperties();

            $rs['lastId'] = $id;
            $rs['updated'] = true;
            
            if ($rs['error'] == 0) {
                return $rs;
            } else {
                return false;
            }
        }
    }

    /**
     * @method insertMultiple
     * Faz a inserção de vários animais de acordo com a quantidade passada pelo usuário
     * @param $dados
     * @return true/false
     */
    public function insertMultiple($dados) {
        // atribuo a quantidade do formulário
        if (!empty($dados['quantidade'])) {
            $quantidade = $dados['quantidade'];
            unset($dados['quantidade']);
        }
        // atribuo o custo do formulário
        if (!empty($dados['custoTotal'])) {
            $custo = $dados['custoTotal'];
            unset($dados['custoTotal']);
            $dados['custo'] = ($custo / $quantidade);
        }
        // remove data de nascimento que já vem setada por padrão no formulário
        unset($dados['dataNascimento']);
        if (empty($dados['id'])) {
            for ($i = 0; $i < $quantidade; $i++) {
                $this->insert($dados)->exec();
                $rs = $this->getProperties();
                if ($rs['error'] === 1) {
                    return false;
                }
            }
        }
    }

    /**
     * Lista todos os animais da base e não precisa mais levar em consideração o statusVenda, pois agora o usuário
     * pode alternar a visualização clicando em aberto, vendido ou todos.
     * @method listar
     * @date 17/08/2016
     * @return $rs
     */
    public function listar() {
//        $w = array(
//            "statusVenda = ?" => 0
//        );

        $rs = $this->select()->exec('ALL');
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Lista todos os animais da base sem levar em considerção seu status
     * @date 15/11/2016
     */
    public function listAll() {
        $s = array('id');

        $this->setTable('animal');
        $rs = $this->select($s)->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Método que faz busca na tabela pedidoItem para saber se há algum pedido com aquele determinado animal
     * para que possa deletar-lo sem problemas posteriormente
     * Obs: usado aqui pelo método deletar()
     * @param $idAnimal
     * @date 19/11/2016
     */
    public function getPedidoItemByAnimal($idAnimal) {
        $s = array('id');

        $w = array(
            'idAnimal = ?' => $idAnimal
        );

        $this->setTable('pedidoItem');
        $this->select($s)->where($w)->exec('ALL');

        $info = $this->getProperties();

        if ($info['rowCount'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para deletar um animal, verifica se o resultado do método getPedidoItemByAnimal retornar 'true', significa
     * que não há nenhum pedido ligado a àquele cliente, e ele pode ser deletado normalmente
     * @param $idAnimal
     * @date 19/11/2016
     */
    public function deletar($idAnimal) {
        $verify = $this->getPedidoItemByAnimal($idAnimal);

        if ($verify) {
            echo 'oi';
            $w = array(
                "id = ?" => $idAnimal
            );

            $this->setTable('animal');
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

}
