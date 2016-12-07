angular.module('tcc').controller("clienteController", function ($scope, clienteService) {
    /**
     * Lista todos os clientes
     */
    var listarCliente = function () {
        clienteService.listarCliente().then(function (response) {
            $scope.clientes = response.data;
        });
    };
    listarCliente();

    /**
     * Usado para abrir o modal tanto para cadastrar novo cliente como para editar os dados de um já existente
     */
    $scope.modal = function (client) {
        $scope.model = {};
        if (client) {
            $scope.titleModal = "Editar Cliente";
            $scope.btnIcon = 'pencil';
            $scope.btnSalvar = 'EDITAR CLIENTE';
            $scope.model = angular.copy(client);
            // Função para carregar cidade ao abrir para edição
            $scope.getCidadeByEstado($scope.model.idEstado);
        } else {
            $scope.titleModal = "Inserir Cliente";
            $scope.btnIcon = 'check';
            $scope.btnSalvar = 'SALVAR CLIENTE';
            $scope.model = {};
        }
        $('#newClient').openModal();
    };

    $scope.closeModal = function () {
        $('#newClient').closeModal();
    };

    // Insere cliente no banco e atualiza a listagem
    $scope.addClient = function () {
        if ($scope.formClient.$invalid) {
            console.log($scope.formClient);
            if ($scope.formClient.$error.required[0]) {
                Materialize.toast('O campo ' + getDataLabel($scope.formClient.$error.required[0].$name) + ' é obrigatório!', 3000, 'toast-error');
            }
        } else {
            clienteService.addClient($scope.model).then(function (response) {
                if (response.data.id) {
                    listarCliente();
                    Materialize.toast('Cliente '+ response.data.id +' adicionado com sucesso!', 4000, 'toast-success');
                    $('#newClient').closeModal();
                } else {
                    Materialize.toast('Falha ao adicionar cliente.', 4000, 'toast-error');
                }
            });
        }
    };

    var getDataLabel = function (name) {
        var label = $('[id="' + name + '"]').attr('data-label');
        return label;
    };

    /**
     * Chama método para deletar cliente
     * Primeiro verifica se o cliente tem algum vínculo com pedidos e se não tiver o deleta.
     */
    $scope.deletar = function (id) {
        var rs = confirm("Deseja realmente excluir este cliente?");
        if (rs) {
            clienteService.deletarCliente(id).then(function (response) {
                if (response.data) {
                    Materialize.toast('Cliente Cod. ' + id + ' excluído com sucesso!', 4000, 'toast-success');
                    listarCliente();
                } else {
                    Materialize.toast('Impossível excluir cliente Cod. ' + id + ', pois há pedidos ligados à ele.', 5000, 'toast-error');
                }
            });
        }
    };

    /**
     * ESTADOS
     */
    var getEstado = function () {
        clienteService.getEstado().then(function (response) {
            $scope.estados = response.data;
        });
    };
    getEstado();

    $scope.getCidadeByEstado = function (idEstado) {
        clienteService.getCidadeByEstado(idEstado).then(function (response) {
            $scope.cidades = response.data;
        });
    };


});