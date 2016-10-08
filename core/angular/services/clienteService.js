angular.module("tcc").service("clienteService", function ($http) {
    
    this.listCliente = function () {
        return $http.get('/cliente/listar');
    };
});
