(function () {
    'user-strict';
    angular.module("tcc").controller("animalController", function ($scope, animalService, pedidoService) {

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

        $scope.sell = [];
        $scope.$watch('sell', function (val) {
            console.log(val);
        }, true);

        $scope.sellAnimals = function () {
            pedidoService.sellAnimal($scope.sell).then(function (response) {
                console.log(response);
                listAnimal();
            });
        };

        $scope.modal = function (animal) {
            $scope.chekboxInsert = false;
            $scope.edit = {};
            if (animal) {
                $scope.edit = angular.copy(animal);
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



    });
})();