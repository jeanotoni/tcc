<?php

/* ao invés do run() aqui usa exec() para executar */

namespace models;

class vacina extends model implements \interfaces\model {

    function __construct() {
        parent::__construct('vacina');
    }

    /**
     * Método para salvar um vacina na base de dados, verifica se não tiver um id é uma inserção, caso haja,
     * ele trata como uma edição.
     * @method salvar
     * @date 11/10/2016
     * @return no caso de inserção: retorna o último id inserido, e no caso de edição o id do animal editado
     */
    public function salvar($dados) {
        $this->setTable('vacina');
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
     * Método listar todos as vacinas
     * @method listar
     * @date 11/10/2016
     * @return $rs
     */
    public function listar() {
        $rs = $this->select()->orderBy('id DESC')->exec('ALL');
        return $rs;
    }

    /**
     * Método para deletar uma vacina
     * @method deletar
     * @param $id
     * @date 11/10/2016
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
    
}
