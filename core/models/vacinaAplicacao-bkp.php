<?php

namespace models;

class vacinaAplicacao extends model implements \interfaces\model {

    function __construct() {
        parent::__construct('vacinaAplicacao');
    }

    /**
     * Método para trazer os dados das aplicações da vacina
     * $date 29/10/2016
     * @return $rs
     */
    public function getVaccineApplication() {
        $this->setTable('vacinaAplicacao');
        $rs = $this->select()->where()->exec('ALL');

//        $rs['dataAplicacao'] = date('Y-m-d', strtotime($rs[0]['dataAplicacao']));
//        debug($rs);

        return $rs;
    }

    /**
     * Método responsável por criar uma aplicação de vacinas e por chamar método p/ adicionar os animais na tabela de itens
     * @param $request
     * @return $rs lastId
     */
    public function vaccinateAnimals($request) {
        //////// FALTA VER A QUESTÃO DAS DATAS. COMO SALVAR E COMO TRAZER DO BANCO
//        $request['dataAplicacao'] = date('Y-m-d');

        $this->setTable('vacinaAplicacao');

        if (empty($request['model']->id)) {
            $this->insert($request['model'])->exec();
            $rs = $this->getProperties();

            $this->addItem($rs['lastId'], $request['itens']);

            if ($rs['error'] === 0) {
                return $rs['lastId'];
            } else {
                return false;
            }
        } else {
            $id = $request['model']->id;
            // Para quando for editar ele não tentar atualizar o id, pq senão vai dar pau
            unset($request['model']->id);

            $w = array(
                "id = ?" => $id
            );
            
            $this->update($request['model'])->where($w)->exec();
            if($request['itens']){
                $this->addItem($id, $request['itens']);
            }
            
            $rs = $this->getProperties();

            if ($rs['error'] == 0) {
                return $id;
            } else {
                return false;
            }
        }
    }

    /**
     * Método responsável por adicionar os animais na tabela vacinaItem pegando um array de animais selecionados e o id da aplicação
     * @param $idVacinaAplicacao
     * @param $arrAnimals
     */
    public function addItem($idVacinaAplicacao, $arrAnimals) {
        foreach ($arrAnimals as $key => $value) {
            if ($value == 1) {
                $idAnimal = $key;

                $i = array(
                    'idVacinaAplicacao' => $idVacinaAplicacao,
                    'idAnimal' => $idAnimal
                );

                $this->setTable('vacinaItem');
                $this->insert($i)->exec();
            }
        }
    }

    public function getIdAplicacaoByAnimal($idAnimal) {
        $s = array('idVacinaAplicacao');
        $w = array(
            'idAnimal = ?' => $idAnimal
        );

        $this->setTable('vacinaItem');
        $rs = $this->select($s)->where($w)->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * Função para listar as informações de todas as vacinas tomadas por um certo animal
     * @param $request
     * @return $rs
     */
    public function getDadosAplicacao($request) {
        // Aqui pego o id de todas as aplicações de vacinas feitas em um animal
        $arrIdAplicacao = $this->getIdAplicacaoByAnimal($request['idAnimal']);

        // Aqui pego cada id e listo os dados da aplicação um por um e armazeno em um array $rs
        $rs = array();
        foreach ($arrIdAplicacao as $key => $value) {
            $id = $value['idVacinaAplicacao'];

            $w = array(
                'id = ?' => $id
            );

            $this->setTable('vacinaAplicacao');
            $temp = $this->select()->where($w)->exec('ALL');

            $rs[$key] = $temp[0];
            $info = $this->getProperties();
        }

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

}