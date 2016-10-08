<?php

namespace models;

class cliente extends model implements \interfaces\model {

    function __construct() {
        parent::__construct('cliente');
    }

    public function listar(){
        $order = 'id DESC';
        
        $rs = $this->select()->orderBy($order)->exec('ALL');
        
        return $rs;
    }
    
    public function save() {
        
    }

}
