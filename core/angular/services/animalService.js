angular.module("tcc").service("animalService", function ($http) {

    this.listAnimal = function () {
        return $http.get('/animal/listar/');
    };

    this.excluirAnimal = function (id) {
        return $http.get('/animal/deletar/' + id);
    };

    this.addAnimal = function (animal) {
        return $http.post('/animal/salvar/', animal);
    };

    this.insertMultiple = function (animal) {
        return $http.post('/animal/insertMultiple/', animal);
    };

});