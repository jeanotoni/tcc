/**
 * Filtro truncate, ele não deixa o texto estrapolar o limite estabelecido que por default é 20 caracteres, ai então
 * adiciona a máscara no final que por default é os 3 pontinhos(...)
 */
angular.module('tcc').filter('truncate', function ($filter) {
    return function (text, tamanho, mascara) {
        text += '';
        var extend = '...';
        if (mascara) {
            extend = mascara;
        }
        var limit = 20;
        
        if(tamanho){
            limit = tamanho;
        }
        var rs = $filter('limitTo')(text, limit);
        
        if (text.length > limit) {
            rs = rs + extend;
        }

        return rs;
    };
});