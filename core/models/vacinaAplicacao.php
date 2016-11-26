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

        $o = 'vacinaAplicacao.id DESC';

        $s = array(
            'vacinaAplicacao.*', 'vacina.nome as vacina'
        );

        $j = array(
            "table" => 'vacina',
            "cond" => 'vacina.id = vacinaAplicacao.idVacina'
        );

        $this->setTable('vacinaAplicacao');
        $rs = $this->select($s)->join($j)->orderBy($o)->exec('ALL');

        // $rs['dataAplicacao'] = date('Y-m-d', strtotime($rs[0]['dataAplicacao']));
        return $rs;
    }

    /**
     * Método responsável por criar uma aplicação de vacinas e por chamar método p/ adicionar os animais na tabela de itens
     * @param $request
     * @return $rs lastId
     */
    public function vaccinateAnimals($request) {
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
            // Para quando for editar ele não tentar atualizar o id, pq senão vai dar pau
            $id = $request['model']->id;
            unset($request['model']->id);
            unset($request['model']->vacina);

            $w = array(
                "id = ?" => $id
            );

            $this->update($request['model'])->where($w)->exec();

//            if ($request['itens']) {
            $this->addItem($id, $request['itens']);
//            }

            $rs = $this->getProperties();

            if ($rs['error'] == 0) {
                return $id;
            } else {
                return false;
            }
        }
    }

    /**
     * Método para adicionar os animais que foram vacinados na tabela vacinaItem
     * Primeiro ele deleta todos os itens e em seguida adiciona apenas os que tem $value igual à 1, 
     * ou seja os que estiverem selecionados
     * @param $idVacinaAplicacao
     * @param $arrAnimals
     */
    public function addItem($idVacinaAplicacao, $arrAnimals) {
        $this->setTable('vacinaItem');

        foreach ($arrAnimals as $key => $value) {
            $idAnimal = $key;

            $w = array(
                'idVacinaAplicacao = ?' => $idVacinaAplicacao,
                'idAnimal = ?' => $idAnimal
            );
            $this->delete()->where($w)->exec();

            if ($value == 1) {
                $i = array(
                    'idVacinaAplicacao' => $idVacinaAplicacao,
                    'idAnimal' => $idAnimal
                );

                $this->insert($i)->exec();
            }
        }
    }

    /**
     * Método responsável por trazer os dados da aplicação da vacina como descrição, data de aplicação, etc. E para isso
     * faz um join na tabela de itens para saber qual o id da aplicação da vacina referente ao animal que está sendo solicitado($idAnimal)
     * @param $idAnimal
     * @return $rs
     */
    public function getAplicacaoByIdAnimal($idAnimal) {
        $s = array(
            'vacinaAplicacao.*',
            'vacinaItem.*',
            'vacina.nome as nomeVacina'
        );
        
        $j = array(
            array(
                'table' => 'vacinaItem',
                'cond' => 'vacinaAplicacao.id = vacinaItem.idVacinaAplicacao'
            ),
            array(
                'table' => 'vacina',
                'cond' => 'vacinaAplicacao.idVacina = vacina.id'
            )
        );

        $w = array(
            'idAnimal = ?' => $idAnimal
        );

        $this->setTable('vacinaAplicacao');
        $rs = $this->select($s)->multipleJoin($j)->where($w)->exec('ALL');

        $info = $this->getProperties();

        if ($info['error'] == 0) {
            return $rs;
        } else {
            return false;
        }
    }

    public function getAnimalSelected($idVacinaAplicacao) {
        $w = array(
            'idVacinaAplicacao = ?' => $idVacinaAplicacao
        );

        $this->setTable('vacinaItem');
        $arrAnimais = $this->select()->where($w)->exec('ALL');
        $info = $this->getProperties();

        // Verifica quais animais são deste pedido para atribuí-los em $selected
        $selecteds = null;
        if (!empty($arrAnimais)) {
            foreach ($arrAnimais as $animal) {
                $selecteds[$animal['idAnimal']] = 1;
            }
        }

        if ($info['error'] == 0) {
            return $selecteds;
        } else {
            return false;
        }
    }

}
