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

    /**
     * Método para atribuir rações à um determinado animal, cria um registro na tabela racaoItem com idRacao, idAnimal e quantidade
     * @param $request
     * @date 26/11/2016
     */
    public function addRacaoByAnimal($request) {
        $request['model']->idAnimal = $request['id'];

        $this->setTable('racaoItem');

        $this->insert($request['model'])->exec();
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $info['lastId'];
        } else {
            return false;
        }
    }
    
    public function interromperRacao($request){
        $u = array(
            'statusRacaoItem' => 1,
            'dataFinal' => $request['dataFinal']
        );
        
        $w = array(
            'id = ?' => $request['idRacaoItem']
        );
        
        $this->setTable('racaoItem');
        $this->update($u)->where($w)->exec();
        
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método usado para listar e trazer algumas informações da ração que aquele animal consome. É usado para listagem no
     * modal do animal na aba 'Ração'
     * @param type $idAnimal
     * @date 26/11/2016
     */
    public function listRacaoByAnimal($idAnimal) {
        $s = array(
            'racaoItem.id as idRacaoItem',
            'racaoItem.quantidade',
            'racaoItem.dataInicial',
            'racaoItem.dataFinal',
            'racaoItem.statusRacaoItem',
            'racao.nome as racao',
            'racao.unidadeMedida'
        );
        
        $w = array(
            'idAnimal = ?' => $idAnimal
        );
        
        $j = array(
            'table' => 'racao',
            'cond' => 'racao.id = racaoItem.idRacao'
        );
        
        $this->setTable('racaoItem');

        $rs = $this->select($s)->join($j)->where($w)->exec('ALL');
        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }
    
    
    

}
