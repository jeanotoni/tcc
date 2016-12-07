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
            if ($scope.formRacao.$invalid) {
                if ($scope.formRacao.$error.required[0]) {
                    Materialize.toast('O campo ' + getDataLabel($scope.formRacao.$error.required[0].$name) + ' é obrigatório!', 3000, 'toast-error');
                }
            } else {
                racaoService.salvarRacao($scope.model).then(function (response) {
                    if (response.data.id) {
                        Materialize.toast('Ração Cod. '+ response.data.id +' inserida com sucesso!', 3000, 'toast-success');
                        $('#newRacao').closeModal();
                        listarRacao();
                    } else {
                        Materialize.toast('Falha ao inserir ração.', 3000, 'toast-error');
                    }
                });
            }
        };

        var getDataLabel = function (name) {
            var label = $('[id="' + name + '"]').attr('data-label');
            return label;
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