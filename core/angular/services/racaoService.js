angular.module("tcc").service("racaoService", function ($http) {

    this.listarRacao = function () {
        return $http.get('/racao/listar/');
    };

    this.salvarRacao = function (racao) {
        return $http.post('/racao/salvar/', racao);
    };
    
    this.deleteVaccine = function (id) {
        return $http.get('/vacina/deletar/' + id);
    };


});