<?php
namespace controllers;

class index extends controller implements \interfaces\controller{
 
    private $model;
    
    function __construct() {
        
    }
    
    public function init(){
        $this->view('index');
    }

}
