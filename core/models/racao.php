<?php

namespace models;

class racao extends model implements \interfaces\model {
    
    function __construct() {
        parent::__construct('racao');
    }
    
    public function salvar($dados){
        $this->insert($dados)->exec();
        
        $rs = $this->getProperties();
        
        if($rs['error'] == 0){
            return $rs['lastId'];
        } else {
            return false;
        }
    }
    
    public function listar(){
        $rs = $this->select()->exec('ALL');
        
        return $rs;
    }
    
}

