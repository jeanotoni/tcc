angular.module("tcc").service("pedidoService", function ($http) {
    
    this.listPedido = function (){
        return $http.get('/pedido/listar');
    };
    
    this.listarClientes = function (){
        return $http.get('/pedido/listarClientes');
    };
    
    this.salvarPedido = function(data){
        return $http.post('/pedido/salvar', data);
    };
    
    
    
    //reusar
    this.excluirAnimal = function (id){
        return $http.get('/animal/deletar/' + id);
    };
    this.addAnimal = function (animal){
        return $http.post('/animal/salvar/', animal);
    };
    
    
    
});