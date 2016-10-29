/*
 * Filtro para tratar adicionar máscara ao valor de ml
 * Retorna a a quantidade já com a máscara caso não esteja vazia
 **/
angular.module('tcc').filter('tccMl', function ($filter) {
    return function (value) {

        if (value && value > 1) {
            return value + ' mls';
        } else if (value && value <= 1){
            return value + ' ml';
        } else {
            return '- -';
        }
        
        return $filter(value);
    };
});