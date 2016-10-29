angular.module("tcc").service("vacinaService", function ($http) {

    this.listVaccine = function () {
        return $http.get('/vacina/listar/');
    };

    this.deleteVaccine = function (id) {
        return $http.get('/vacina/deletar/' + id);
    };

    this.addVaccine = function (vacina) {
        return $http.post('/vacina/salvar/', vacina);
    };
    
    // RELACIONADO À APLICAÇÃO DE VACINAS
    
    this.getVaccineApplication = function (){
        return $http.get('/vacinaAplicacao/getVaccineApplication/');
    };
    
    this.vaccinateAnimals = function (dados) {
        return $http.post('/vacinaAplicacao/vaccinateAnimals/', dados);
    };
    
    this.getDadosAplicacao = function (idAnimal) {
        return $http.post('/vacinaAplicacao/getDadosAplicacao/', idAnimal);
    };

});