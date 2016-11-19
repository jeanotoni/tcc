angular.module("tcc").controller("pedidoController", function ($scope, pedidoService, animalService, clienteService) {

    var listPedido = function () {
        pedidoService.listPedido().then(function (response) {
            $scope.pedidos = response.data;
        });
    };
    listPedido();

    // Utiliza requisição do service do cliente para listar os clientes no combobox
    var listarClientes = function () {
        clienteService.listarIdNomeCliente().then(function (response) {
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

    var getAnimalByPedido = function (idPedido) {
        pedidoService.getAnimalByPedido(idPedido).then(function (response) {
            // valido antes para não atribuir nulo à $scope.list caso não haja nada em selecteds
            $scope.list = response.data.selected;
            $scope.animais = response.data.animal;
            console.log($scope.list);
        });
    };

    // Crio array que conterá os animais que estão marcados como pago
    $scope.list = {};
    $scope.$watch('list', function (val) {
        console.log(val);
    }, true);

    $scope.openModal = function (pedido) {
        if (pedido) {
            $scope.model = {};
            $scope.btnIcon = 'pencil';
            $scope.titleModal = "Editar Pedido";
            $scope.btnSalvar = "EDITAR";
            $scope.model = angular.copy(pedido);
            $scope.model.dataEmissao = new Date($scope.model.dataEmissao);
            getAnimalByPedido($scope.model.id);
        } else {$scope.btnIcon = 'check';
            $scope.titleModal = "Inserir Pedido";
            $scope.btnSalvar = "SALVAR";
            $scope.model = {
                dataEmissao: new Date()
            };
            $scope.list = {};
            listAnimal();
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

    $scope.estornarCancelarPedido = function (cod) {
        var param = {
            itens: $scope.list,
            model: $scope.model,
            situacao: cod
        };
        pedidoService.estornarCancelarPedido(param).then(function (response) {
            if (response.data) {
                var label = '';
                if (cod == 3) {
                    label = 'CANCELADO';
                } else if (cod == 4) {
                    label = 'ESTORNADO';
                }
                Materialize.toast('Pedido Cod. ' + $scope.model.id + ' ' + label + ' com sucesso!', 5000, 'toast-success');
                listPedido();
            } else {
                Materialize.toast('Falha ao atualizar pedido Cod. ' + $scope.model.id + '!', 5000, 'toast-error');
            }
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