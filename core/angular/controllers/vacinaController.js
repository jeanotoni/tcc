(function () {
    'user-strict';
    angular.module("tcc").controller("vacinaController", function ($scope, vacinaService, animalService) {

        // Listagem das vacinas cadastradas
        var listVaccine = function () {
            vacinaService.listVaccine().then(function (response) {
                $scope.vacinas = response.data;
            });
        };
        listVaccine();

        // Abre modal de aplicação de vacina
        $scope.openAplicarVacinaModal = function () {
            $('#aplicar-vacina-modal').openModal({close_esc: true});
        };

        $scope.closeModal = function () {
            $('#newVaccine').closeModal();
            $('#aplicar-vacina-modal').closeModal();
        };

        $scope.modal = function (vacina) {
            $scope.chekboxInsert = false;
            $scope.model = {};
            if (vacina) {
                $scope.model = angular.copy(vacina);
            } else {
                $scope.model = {};
            }
            $('#newVaccine').openModal();
        };



        // Insere vacina no banco
        $scope.addVaccine = function () {
            vacinaService.addVaccine($scope.model).then(function (response) {
                if (response.data.id) {
                    listVaccine();
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



        /*----------------------------------------------------------------------*\
         *
         * * TODA A PARDE DE APLICAÇÃO DE VACINAS
         *
         *-----------------------------------------------------------------------*/

        $scope.modalAplicacao = function (aplicacao) {
            $scope.model = {};
            if (aplicacao) {
                //////// SE FIZER ISTO PARA APARECER A DATA NO MODAL ELE BUGA A LISTAGEM POIS JÁ ESTÁ COM O FILTRO TCCDATE, AI 
                // ELE PEGA E COLOCA UM DIA A MENOS..... e também ver a questão de trazer os animais($scope.list) selecionados na edição
                // e também está bugando se vc seleciona um animal na edição, e vai abrir uma nova aplicação ele tbm vem selecionado
                // aplicacao.dataAplicacao = new Date(aplicacao.dataAplicacao);
                $scope.model = angular.copy(aplicacao);
            } else {
                $scope.list = [];
                $scope.model = {};
            }
            $('#aplicar-vacina-modal').openModal();
        };

        // Utilizada para listar os animais e na view printo eles dentro dos modais
        var listAnimals = function () {
            animalService.listAnimal().then(function (response) {
                $scope.animais = response.data;
            });
        };
        listAnimals();

        // Utilizada para buscar todas as aplicações de vacinas para listar na tela
        var getVaccineApplication = function () {
            vacinaService.getVaccineApplication().then(function (response) {
                console.log(response);
                $scope.vacinaAplicacao = response.data;
            });
        };
        getVaccineApplication();


        // Crio array que conterá os animais que receberão a vacina
        $scope.list = [];
        $scope.$watch('list', function (val) {
            console.log(val);
        }, true);

        // Utiliza-se para chamar método que salva a aplicação de uma vacina, atualiza listagem e limpa os $scopes
        $scope.vaccinateAnimals = function () {
            var params = {
                itens: $scope.list,
                model: $scope.model
            };
            vacinaService.vaccinateAnimals(params).then(function (response) {
                if (response.data) {
                    getVaccineApplication();
                    $('#aplicar-vacina-modal').closeModal();
                    Materialize.toast('Aplicação realizada com sucesso!', 4000, 'toast-success');
                    $scope.list = {};
                    $scope.model = {};
                }
            });
        };

    });
})();