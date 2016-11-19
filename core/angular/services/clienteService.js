angular.module("tcc").service("clienteService", function ($http) {

    this.listarCliente = function () {
        return $http.get('/cliente/listar');
    };
    
    this.listarIdNomeCliente = function () {
        return $http.get('/cliente/listarIdNome/');
    };
    
    this.addClient = function (client) {
        return $http.post('/cliente/salvar/', client);
    };
    
    this.deletarCliente = function (id) {
        return $http.get('/cliente/deletar/' + id);
    };
    
    
    
    
    

    this.listVaccine = function () {
        return $http.get('/vacina/listar/');
    };


    

    // RELACIONADO À APLICAÇÃO DE VACINAS

    this.getVaccineApplication = function () {
        return $http.get('/vacinaAplicacao/getVaccineApplication/');
    };

    this.vaccinateAnimals = function (dados) {
        return $http.post('/vacinaAplicacao/vaccinateAnimals/', dados);
    };

    this.getDadosAplicacao = function (idAnimal) {
        return $http.post('/vacinaAplicacao/getDadosAplicacao/', idAnimal);
    };
});
