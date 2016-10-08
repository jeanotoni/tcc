angular.module("tcc").controller("pedidoController", function ($scope, pedidoService) {

    var listPedido = function () {
        pedidoService.listPedido().then(function (response) {
            $scope.pedidos = response.data;
        });
    };
    listPedido();
    
    $scope.openModal = function (pedido) {
        if (pedido) {
            $scope.model = angular.copy(pedido);
        } else {
            $scope.model = {};
            $scope.model.dataCriacao = new Date();
        }
        $('#newPedido').openModal();
    };


    $scope.addPedido = function () {
        pedidoService.salvarPedido($scope.model).then(function (response) {
            if (response.data) {
                listPedido();
            }
            $scope.model = {};
//            $scope.model.$setPristine();
        });
    };


    $scope.excluir = function (id) {
        var rs = confirm("Deseja realmente excluir este animal?");
        if (rs) {
            animalService.excluirAnimal(id).then(function (response) {
                console.log(response);
                listAnimal();
            });
        }
    };

    /*  
     TENTANDO RESETAR SELECT DO MATERIALIZE
     */
    $scope.teste = null;
    $scope.resetSelect = function () {
        $('select').material_select();
    };

    var listarClientes = function () {
        pedidoService.listarClientes().then(function (response) {
            $scope.clientes = response.data;
        });
    };
    listarClientes();



});