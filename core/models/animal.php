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

    /*    public function salvar($dados) {
      if (empty($id)) {
      $this->insert($dados)->exec();
      $rs = $this->getProperties();
      if ($rs['error'] === 0) {
      return $rs['lastId'];
      } else {
      return false;
      }
      } else {
      $id = isset($_GET['id']) ? $_GET['id'] : null;

      $w = array(
      "id = ?" => $id
      );

      $this->update()->where($w)->exec();
      }
      }
     */

    /**
     * Método para salvar um animal na base de dados, verifica se não tiver um id é uma inserção, caso haja,
     * ele trata como uma edição.
     * @method salvar
     * @date 17/08/2016
     * @return no caso de inserção: retorna o último id inserido, e no caso de edição o id do animal editado
     */
    public function salvar($dados) {
        // trata para pegar somente o que for data e ignorar o restante que são as horas
        if (isset($dados['dataNascimento'])) {
            $dados['dataNascimento'] = date('Y-m-d', strtotime(substr($dados['dataNascimento'], 0, 10)));
        }
        if (empty($dados['id'])) {
            $this->insert($dados)->exec();
            $rs = $this->getProperties();
            if ($rs['error'] === 0) {
                return $rs['lastId'];
            } else {
                return false;
            }
        } else {
            $id = $dados['id'];
            // para quando for editar ele não tentar atualizar o id, pq senão vai dar pau
            unset($dados['id']);

            $w = array(
                "id = ?" => $id
            );
            $this->update($dados)->where($w)->exec();

            $rs = $this->getProperties();

            if ($rs['error'] === 0) {
                return $id;
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
     * Método listar todos os animais da base ordenados por id
     * @method listar
     * @date 17/08/2016
     * @return $rs
     */
    public function listar() {
        $w = array(
            "statusVenda = ?" => 0
        );
        
        $rs = $this->select()->where($w)->exec('ALL');
        return $rs;
    }

    /**
     * Método para deletar um animal
     * @method salvar
     * @param $id
     * @date 17/08/2016
     * @return retorna true caso não dê erro
     */
    public function deletar($id) {
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
    
//    public function getAnimalById($id){
//        $w = array(
//            "id = ?" => $id
//        );
//
//        $rs = $this->select()->where($w)->exec('ALL');
//        
//        if (empty($rs['error'])) {
//            return $rs;
//        } else {
//            return false;
//        }
//    }

    

}
