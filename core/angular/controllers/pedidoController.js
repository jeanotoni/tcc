angular.module("tcc").controller("pedidoController", function ($scope, pedidoService, animalService) {

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

    // Crio array que conterá os animais que estão marcados como pago
    $scope.sell = [];
    $scope.$watch('sell', function (val) {
        console.log(val);
    }, true);
    
//    $scope.sellAnimals = function () {
//        pedidoService.sellAnimal($scope.sell).then(function (response) {
//            console.log(response);
//            listAnimal();
//        });
//    };
    
    
    // TA DANDO PAU TEM QUE ARRUMAR AINDA ESSA PARTE PQ UM É ARRAY E O OUTRO É OBJETO
    // FALTA ARRUMAR CHAMADAS NO MODEL E NO CONTROLLER PRA PEGAR O ID DO PEDIDO
    $scope.addPedido = function () {
        var params = {
            itens: $scope.sell,
            model: $scope.model
        };
        pedidoService.salvarPedido(params).then(function (response) {
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

    // Utiliza requisição do service do cliente
    var listarClientes = function () {
        pedidoService.listarClientes().then(function (response) {
            $scope.clientes = response.data;
        });
    };
    listarClientes();

    // Utiliza requisição do service do animal
    var listarAnimais = function () {
        animalService.listAnimal().then(function (response) {
            $scope.animais = response.data;
        });
    };
    listarAnimais();





});