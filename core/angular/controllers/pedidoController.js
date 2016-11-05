angular.module("tcc").controller("pedidoController", function ($scope, pedidoService, animalService) {

    var listPedido = function () {
        pedidoService.listPedido().then(function (response) {
            $scope.pedidos = response.data;
        });
    };
    listPedido();

    // Utiliza requisição do service do cliente para listar os clientes no combobox
    var listarClientes = function () {
        pedidoService.listarClientes().then(function (response) {
            $scope.clientes = response.data;
        });
    };
    listarClientes();

    var listAnimal = function () {
        animalService.listAnimal().then(function (response) {
            console.log(response.data);
            $scope.animais = response.data;
        });
    };

    // Utiliza requisição do service do animal
    var listAnimalByPedido = function (idPedido) {
        pedidoService.listAnimalByPedido(idPedido).then(function (response) {
            $scope.list = response.data.selecteds;
            $scope.animais = response.data.arrAnimais;
        });
    };

    // Crio array que conterá os animais que estão marcados como pago
    $scope.list = [];
    $scope.$watch('list', function (val) {
//        console.log(val);
    }, true);

    $scope.openModal = function (pedido) {
        if (pedido) {
            $scope.titleModal = "Editar Pedido";
            $scope.btnSalvar = "EDITAR";
            $scope.model = angular.copy(pedido);
            listAnimalByPedido($scope.model.id);
        } else {
            $scope.titleModal = "Inserir Pedido";
            $scope.btnSalvar = "SALVAR";
            $scope.model = {};
            $scope.list = [];
            listAnimal();
            // $scope.model.dataCriacao = new Date();
        }
        $('#newPedido').openModal();
    };



    $scope.addPedido = function () {
        var params = {
            itens: $scope.list,
            model: $scope.model
        };
        pedidoService.salvarPedido(params).then(function (response) {
            $('#newPedido').closeModal();
            console.log(response.data);

            if (response.data.lastId) {
                Materialize.toast('Pedido Cod. ' + response.data.lastId + ' SALVO com sucesso!', 4000, 'toast-success');
            } else {
                Materialize.toast('Pedido Cod. ' + $scope.model.id + ' EDITADO com sucesso!', 4000, 'toast-success');
            }
            $scope.model = {};
            $scope.list = [];
            listPedido();
            // $scope.formPedido.$setPristine();
        });
    };

    var situation = {
        1: 'Aberto',
        2: 'Pago',
        3: 'Cancelado',
        4: 'Estornado'
    };
    $scope.getSituation = function (id) {
        return situation[id];
    };


    $scope.updateStatusPedido = function (situacao) {
        var params = {
            situacao: situacao,
            model: $scope.model
        };
        pedidoService.updateStatusPedido(params).then(function (response) {
            $('#newPedido').closeModal();
            if (response.data) {
                if (situacao == 2) {
                    Materialize.toast('Pedido Cod. ' + $scope.model.id + ' FINALIZADO com sucesso!', 4000, 'toast-success');
                } else if (situacao == 3) {
                    Materialize.toast('Pedido Cod. ' + $scope.model.id + ' CANCELADO com sucesso!', 4000, 'toast-success');
                } else if (situacao == 4) {
                    Materialize.toast('Pedido Cod. ' + $scope.model.id + ' ESTORNADO com sucesso!', 4000, 'toast-success');
                }
            } else {
                Materialize.toast('Falha ao atualizar pedido Cod. ' + $scope.model.id + '!', 4000, 'toast-error');
            }
            listPedido();
            $scope.model = {};
            $scope.list = {};
        });
    };


    $scope.excluir = function (id) {
        var rs = confirm("Deseja realmente excluir este animal?");
        if (rs) {
            animalService.excluirAnimal(id).then(function (response) {
//                console.log(response);
//                listAnimal();
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





    $scope.closeModal = function () {
        $('#newPedido').closeModal();
    };







});