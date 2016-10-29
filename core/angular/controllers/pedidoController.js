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
            // $scope.model.dataCriacao = new Date();
        }
        $('#newPedido').openModal();
    };

    // Crio array que conterá os animais que estão marcados como pago
    $scope.list = [];
    $scope.$watch('list', function (val) {
        console.log(val);
    }, true);

    $scope.addPedido = function () {
        var params = {
            itens: $scope.list,
            model: $scope.model
        };
        pedidoService.salvarPedido(params).then(function (response) {
            if (response.data) {
                listPedido();
            }
            $scope.model = {};
            $scope.list = {};
            listAnimals();
//            $scope.formPedido.$setPristine();
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
    var listAnimals = function () {
        animalService.listAnimal().then(function (response) {
            $scope.animais = response.data;
        });
    };
    listAnimals();

    $scope.closeModal = function () {
        $('#newPedido').closeModal();        
    };





});