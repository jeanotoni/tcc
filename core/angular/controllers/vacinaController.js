(function () {
    'user-strict';
    angular.module("tcc").controller("vacinaController", function ($scope, vacinaService) {

        // Listagem das vacinas cadastradas
        var listVacina = function () {
            vacinaService.listVacina().then(function (response) {
                $scope.vacinas = response.data;
            });
        };
        listVacina();

        // Abre modal de aplicação de vacina
        $scope.openAplicarVacinaModal = function () {
            $('#aplicar-vacina-modal').openModal({close_esc: true});
        };

        $scope.modal = function (vacina) {
            $scope.chekboxInsert = false;
            $scope.model = {};
            if (vacina) {
                $scope.model = angular.copy(vacina);
            } else {
                $scope.model = {};
            }
            $('#newVacina').openModal();
        };
        // Insere vacina no banco
        $scope.addAnimal = function () {
            vacinaService.addVacina($scope.model).then(function (response) {
                if (response.data.id) {
                    listVacina();
                }
            });
        };
        
        

        $scope.excluir = function (id) {
            var rs = confirm("Deseja realmente excluir este animal?");
            if (rs) {
                vacinaService.excluirVacina(id).then(function (response) {
                    if (response.data) {
                        Materialize.toast(response.data, 4000, 'toast-success');
                        listVacina();
                    }
                });
            }
        };






    });
})();