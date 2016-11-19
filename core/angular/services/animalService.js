angular.module("tcc").service("animalService", function ($http) {

    this.listAnimal = function () {
        return $http.get('/animal/listar/');
    };
    
    this.listAll = function () {
        return $http.get('/animal/listAll/');
    };

    this.deletarAnimal = function (id) {
        return $http.get('/animal/deletar/' + id);
    };

    this.addAnimal = function (animal) {
        return $http.post('/animal/salvar/', animal);
    };

    this.insertMultiple = function (animal) {
        return $http.post('/animal/insertMultiple/', animal);
    };
    
//    this.details = function (){
//        return $http.get('/animal/animalDetails/');
//    };
    
    this.getAnimalById = function (){
        return $http.get('/animal/getAnimalById/');
    };

});