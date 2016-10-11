angular.module("tcc").service("vacinaService", function ($http) {

    this.listVacina = function () {
        return $http.get('/vacina/listar/');
    };

    this.excluirVacina = function (id) {
        return $http.get('/vacina/deletar/' + id);
    };

    this.addVacina = function (vacina) {
        return $http.post('/vacina/salvar/', vacina);
    };

});