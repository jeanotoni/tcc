<?php

namespace models;

class racao extends model implements \interfaces\model {

    function __construct() {
        parent::__construct('racao');
    }

    /**
     * Método para salvar uma ração na base de dados, verifica se não tiver um id é uma inserção, caso haja,
     * ele trata como uma edição.
     * @method salvar
     * @date 01/11/2016
     * @return no caso de inserção: retorna o último id inserido, e no caso de edição o id da ração editado
     */
    public function salvar($request) {
        if (empty($request['id'])) {

            $this->insert($request)->exec();
            $info = $this->getProperties();

            if ($info['error'] == 0) {
                return $info['lastId'];
            } else {
                return false;
            }
        } else {
            // para quando for editar ele não tentar atualizar o id, pq senão vai dar pau
            $id = $request['id'];
            unset($request['id']);

            $w = array(
                "id = ?" => $id
            );
            $this->update($request)->where($w)->exec();

            $info = $this->getProperties();

            if ($info['error'] == 0) {
                return $id;
            } else {
                return false;
            }
        }
    }

    /**
     * Método usado para listar as rações cadastradas na base
     * @method listar
     * @date 19/11/2016
     * @return $rs
     */
     public function listar() {
        $this->setTable('racao');
        $rs = $this->select()->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

}
