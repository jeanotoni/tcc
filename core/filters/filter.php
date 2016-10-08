<?php

    namespace filters;
    
    /**
     * Classe responsável por tratar os métodos de entrada, principal defesa anti sql injection 
     * Nela tratamos os métodos de requisição HTTP assegurando que os mesmos retornem aquilo que foi esperado.
     */
    class filter{
        
        /**
         * @method getNum
         * 
         * Método responsável por tratar as estradas numéricas do $_GET
         * Caso $index não seja informado, retorna um array com todas as 
         * propriedades numéricas em $_GET
         * 
         * @author Jeferson Capobianco <jefersoncapobianco@gmail.com>
         * 
         * @var array return            Variável auxiliar para guardar valores do array superglobal _GET enquanto o tratamos.
         * @param string $index         Indice para filtrar o resultado
         * @return array                
         */
        protected function getNum($index = null){
            if($index){
                return isset($_GET[$index]) && is_numeric($_GET[$index]) ? $_GET[$index] : false;
            }else{
                $return = [];
                foreach($_GET as $k => $v){
                    if(is_numeric($v)){
                        $return[$k] = $v;
                    }
                }
                return $return;
            }
        }        
        

        protected function integerFilter($num){
            return (int) $num;
        } 
        
        protected function stringFilter($num){
            return (string) $num;
        } 
        
        
        /**
         * @method postNum
         * 
         * Método responsável por tratar as estradas numéricas do $_POST
         * Caso $index não seja informado, retorna um array com todas as 
         * propriedades numéricas em $_POST
         * 
         * @author Jeferson Capobianco <jefersoncapobianco@gmail.com>
         * 
         * @var array return            Variável auxiliar para guardar valores do array superglobal _POST enquanto o tratamos.
         * @param string $index         Indice para filtrar o resultado
         * @return array                
         */
        protected function postNum($index = null){
            if($index){
                return isset($_POST[$index]) && is_numeric($_POST[$index]) ? $_POST[$index] : false;
            }else{
                $return = [];
                foreach($_POST as $k => $v){
                    if(is_numeric($v)){
                        $return[$k] = $v;
                    }
                }
                return $return;
            }
        }
        
        protected function getStr($index = null){
            $pattern = "/[^a-zA-Z0-9_@.]/i";
            if($index){
                return isset($_GET[$index]) && !empty($_GET[$index]) ? preg_replace($pattern, "", $_GET[$index]) : false;
            }else{
                $return = [];
                foreach($_GET as $k => $v){
                    if(!empty($v)){
                        $return[$k] = preg_replace($pattern, "", $v);
                    }
                }
                return $return;
            }
        }
    }

