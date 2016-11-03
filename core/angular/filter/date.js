/*
 * Filtro para tratar data vindo do banco
 * Recebe dois parametros: data e formato
 * Verifica se o valor vindo do banco não for uma data, significa que ele é uma string, então ele a transforma em 
 * um objeto de data e logo em seguida aplica o filtro com o formato descrito na view
 * Retorna a data filtrada com um formato definido
 **/
angular.module('tcc').filter('tccDate', function ($filter) {
    return function (date, format) {
        var formatDate = format ? format : "dd/MM/yyyy";

        if (!date) {
            return '- -';
        }
        if (!angular.isDate(date)) {
            date = new Date(date);
        }
        //UTC é para não bugar a data, pois sem ele o valor da data vem um dia antes
        return $filter('date')(date, formatDate, 'UTC');
    };
});