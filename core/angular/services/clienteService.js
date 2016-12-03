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
    
    
    // CLIENTES
    this.getEstado = function (){
        return $http.get('/cliente/getEstado/');
    };
    
    this.getCidadeByEstado= function (idEstado){
        return $http.get('/cliente/getCidadeByEstado/'+ idEstado);
    };
    
    

    
});
