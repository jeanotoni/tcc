<?php

namespace controllers;

class contato extends controller implements \interfaces\controller {

    function __construct() {
        
    }

    public function init() {
        $this->view('contato');
    }

    

}
