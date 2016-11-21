(function () {
    'user-strict';
    angular.module("tcc").controller("animalController", function ($scope, animalService, pedidoService, vacinaService, racaoService) {

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
                    $scope.model = {};
                    $scope.list = {};
                    listAnimal();
                }
            });
        };

        var statusVenda = {
            0: 'Aberto',
            1: 'Vendido'
        };
        $scope.getStatusVenda = function (id) {
            return statusVenda[id];
        };


        $scope.filterListAnimal = {};
        $scope.$watch('filterListAnimal', function (val) {
            if (val.statusVenda == null) {
                delete($scope.filterListAnimal.statusVenda);
            }
        }, true);

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

        $scope.deletar = function (id) {
            var rs = confirm("Deseja realmente excluir este animal?");
            if (rs) {
                animalService.deletarAnimal(id).then(function (response) {
                    if (response.data) {
                        Materialize.toast('Animal Cod. ' + id + ' excluído com sucesso!', 4000, 'toast-success');
                        listAnimal();
                    } else {
                        Materialize.toast('Impossível excluir animal Cod. ' + id + ', pois há pedidos ligados à ele.', 5000, 'toast-error');
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
                $scope.details = response.data;
            });

            $('#details-modal').openModal({close_esc: true});
        };

        $scope.closeModal = function () {
            $('#newAnimal').closeModal();
            $('#details-modal').closeModal();
            $('#sell-modal').closeModal();
        };

//        $scope.details = function (id) {
//            window.location = '/animal/details/' + id;
//        };
        $('#newAnimal').openModal();

        $scope.aba = 2;
        $scope.alterAba = function (aba) {
            $scope.aba = aba;
        };

        // Depois criar função que traz somente o nome e id da ração para otimizar
        var listarRacao = function () {
            racaoService.listarRacao().then(function (response) {
                $scope.racoes = response.data;
            });
        };
        listarRacao();

        $scope.addRacao = function () {
            racaoService.addRacao
        };



    });
})();