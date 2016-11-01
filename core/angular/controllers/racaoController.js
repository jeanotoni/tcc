(function () {
    'user-strict';
    angular.module("tcc").controller("racaoController", function ($scope, vacinaService, racaoService) {

        // Listagem das vacinas cadastradas
        var listarRacao = function () {
            racaoService.listarRacao().then(function (response) {
                $scope.racoes = response.data;
            });
        };
        listarRacao();

        $scope.closeModal = function () {
            $('#newVaccine').closeModal();
            $('#aplicar-vacina-modal').closeModal();
        };

        $scope.modal = function (racao) {
            $scope.chekboxInsert = false;
            $scope.model = {};
            if (racao) {
                $scope.model = angular.copy(racao);
            } else {
                $scope.model = {};
            }
            $('#newRacao').openModal();
        };



        // Insere vacina no banco
        $scope.salvarRacao = function () {
            racaoService.salvarRacao($scope.model).then(function (response) {
                if (response.data.id) {
                    listarRacao();
                }
            });
        };

        $scope.delete = function (id) {
            var rs = confirm("Deseja realmente excluir esta vacina?");
            if (rs) {
                vacinaService.deleteVaccine(id).then(function (response) {
                    if (response.data) {
                        Materialize.toast(response.data, 4000, 'toast-success');
                        listVaccine();
                    }
                });
            }
        };

    });
})();