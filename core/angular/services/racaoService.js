angular.module("tcc").service("racaoService", function ($http) {

    this.listarRacao = function () {
        return $http.get('/racao/listar/');
    };

    this.salvarRacao = function (racao) {
        return $http.post('/racao/salvar/', racao);
    };
    
    this.deletarRacao = function (id) {
        return $http.get('/racao/deletar/' + id);
    };
    
    this.addRacaoByAnimal = function (racao){
        return $http.post('/racao/addRacaoByAnimal/', racao);
    };
    
    this.addRacaoMultipleAnimal = function (dados){
        return $http.post('/racao/addRacaoMultipleAnimal/', dados);
    };
    
    this.listRacaoByAnimal = function (idAnimal) {
        return $http.get('/racao/listRacaoByAnimal/'+ idAnimal);
    };
    
    this.interromperRacao = function (params) {
        return $http.post('/racao/interromperRacao/', params);
    };


});