(function () {
    'user-strict';
    angular.module("tcc").controller("racaoController", function ($scope, racaoService) {

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
                $scope.titleModal = 'Editar Ração - Alimento';
                $scope.btnIcon = 'pencil';
                $scope.btnSalvar = 'EDITAR';
                $scope.model = angular.copy(racao);
            } else {
                $scope.titleModal = 'Inserir Ração - Alimento';
                $scope.btnIcon = 'check';
                $scope.btnSalvar = 'SALVAR';
                $scope.model = {};
            }
            $('#newRacao').openModal();
        };



        // Insere Ração no banco
        $scope.salvarRacao = function () {
            racaoService.salvarRacao($scope.model).then(function (response) {
                if (response.data.id) {
                    listarRacao();
                }
            });
        };

        $scope.deletar = function (id) {
            var rs = confirm("Deseja realmente excluir esta ração?");
            if (rs) {
                racaoService.deletarRacao(id).then(function (response) {
                    if (response.data) {
                        Materialize.toast(response.data, 4000, 'toast-success');
                        listarRacao();
                    }
                });
            }
        };

    });
})();