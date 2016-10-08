angular.module('tcc').controller("clienteController", function ($scope, clienteService) {

     var listClientes = function () {
        clienteService.listCliente().then(function (response) {
            $scope.clientes = response.data;
            console.log(response);
        });
    };
    listClientes();


});