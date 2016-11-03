(function () {
    'user-strict';
    angular.module("tcc").controller("animalController", function ($scope, animalService, pedidoService, vacinaService) {

        $scope.insertMultiple = function () {
            animalService.insertMultiple($scope.edit).then(function (response) {
                if (response.data) {
                    listAnimal();
                }
            });
        };
        
        var listAnimal = function () {
            animalService.listAnimal().then(function (response) {
                $scope.animais = response.data;
            });
        };
        listAnimal();

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
                    console.log(response);
                    $scope.model = {};
                    $scope.list = {};
                    listAnimal();
                }
            });
        };

        $scope.modal = function (animal) {
            $scope.chekboxInsert = false;
            $scope.edit = {};
            if (animal) {
                $scope.edit = angular.copy(animal);
                $scope.edit.dataNascimento = new Date($scope.edit.dataNascimento);
            } else {
                $scope.edit = {
                    dataNascimento: new Date()
                };
            }
            $('#newAnimal').openModal();
        };

        $scope.addAnimal = function () {
            animalService.addAnimal($scope.edit).then(function (response) {
                if (response.data.id) {
                    listAnimal();
                }
            });
        };

        $scope.excluir = function (id) {
            var rs = confirm("Deseja realmente excluir este animal?");
            if (rs) {
                animalService.excluirAnimal(id).then(function (response) {
                    if (response.data) {
                        Materialize.toast(response.data, 4000, 'toast-success');
                        listAnimal();
                    }
                });
            }
        };

        // Lista os clientes para efetuar uma venda
        var listarClientes = function () {
            pedidoService.listarClientes().then(function (response) {
                $scope.clientes = response.data;
            });
        };
        listarClientes();


        $scope.openSellModal = function () {
            $('#sell-modal').openModal({close_esc: true});
        };

        $scope.openDetailsModal = function (idAnimal) {
            var param = {
                idAnimal: idAnimal
            };
            vacinaService.getDadosAplicacao(param).then(function (response) {
                console.log(response);
                $scope.details = response.data;
            });
            
            $('#details-modal').openModal({close_esc: true});
        };

        $scope.closeModal = function () {
            $('#details-modal').closeModal();
            $('#sell-modal').closeModal();
        };

        // to com problemas pra listar os dados dos animais dentro da view animalDetails
//        $scope.details = function (id) {
//            window.location = '/animal/details/' + id;
//        };




    });
})();