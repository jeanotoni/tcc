angular.module("tcc").service("pedidoService", function ($http) {

    this.listPedido = function () {
        return $http.get('/pedido/listar');
    };

    this.listarClientes = function () {
        return $http.get('/pedido/listarClientes');
    };

    this.salvarPedido = function (data) {
        return $http.post('/pedido/salvar', data);
    };

    this.sellAnimal = function (dados) {
        return $http.post('/pedido/sellAnimal', dados);
    };

    this.addItem = function (dados) {
        return $http.post('/pedido/addItem', dados);
    };
    
    this.updateStatusPedido = function(params){
        return $http.post('/pedido/updateStatusPedido/', params);
    };
    
    this.estornarCancelarPedido = function(list){
        return $http.post('/pedido/estornarCancelarPedido/', list);
    };
    
    this.getAnimalByPedido = function (idPedido){
        return $http.get('/pedido/getAnimalByPedido/'+ idPedido);
    };
    
    this.getValorTotal = function (idPedido){
        return $http.post('/pedido/getValorTotal/', idPedido);
    };
    
    
});