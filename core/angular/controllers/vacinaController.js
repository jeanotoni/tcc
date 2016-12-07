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

        $scope.closeModal = function () {
            $('#newVaccine').closeModal();
            $('#aplicar-vacina-modal').closeModal();
        };

        //Abre modal de cadastro da Vacina
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
            if ($scope.formVacina.$invalid) {
                if ($scope.formVacina.$error.required[0]) {
                    Materialize.toast('O campo ' + getDataLabel($scope.formVacina.$error.required[0].$name) + ' é obrigatório!', 3000, 'toast-error');
                }
            } else {
                vacinaService.addVaccine($scope.model).then(function (response) {
                    if (response.data.id) {
                        Materialize.toast('Vacina Cod. ' + response.data.id + ' inserida com sucesso!', 3000, 'toast-success');
                        $('#newVaccine').closeModal();
                        listVaccine();
                    } else {
                        Materialize.toast('Falha ao inserir vacina.', 3000, 'toast-error');
                    }
                });
            }
        };

        var getDataLabel = function (name) {
            var label = $('[id="' + name + '"]').attr('data-label');
            return label;
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
         * * TODA A PARDE DE APLICAÇÃO DE VACINAS
         *-----------------------------------------------------------------------*/

        // Utilizada para listar os animais e na view exibí-los dentro dos modais
        var listAnimal = function () {
            animalService.listAll().then(function (response) {
                $scope.animais = response.data;
            });
        };
        listAnimal();

        // Pega os animais correspondente àquela aplicação e os atribui à $scope.list para virem selecionadas ao abrir modal
        var getAnimalSelected = function (idVacinaAplicacao) {
            vacinaService.getAnimalSelected(idVacinaAplicacao).then(function (response) {
                if (response.data.selected) {
                    $scope.list = response.data.selected;
                }
            });
        };

        // Abre modal de aplicação de vacina
        $scope.modalAplicacao = function (aplicacao) {
            $scope.model = {};
            listAnimal();
            if (aplicacao) {
                $scope.list = {};
                $scope.model = angular.copy(aplicacao);
                $scope.model.dataAplicacao = new Date($scope.model.dataAplicacao);
                getAnimalSelected($scope.model.id);
            } else {
                $scope.list = {};
                $scope.model = {
                    dataAplicacao: new Date()
                };
            }
            $('#aplicar-vacina-modal').openModal();
        };



        // Utilizada para buscar todas as aplicações de vacinas para listar na tela
        var getVaccineApplication = function () {
            vacinaService.getVaccineApplication().then(function (response) {
                console.log(response);
                $scope.vacinaAplicacao = response.data;
            });
        };
        getVaccineApplication();


        // Crio array que conterá os animais que receberão a vacina
        $scope.list = {};
        $scope.$watch('list', function (val) {
            console.log(val);
        }, true);

        // Utiliza-se para chamar método que salva a aplicação de uma vacina, atualiza listagem e limpa os $scopes
        $scope.vaccinateAnimals = function () {
            if ($scope.formAplicarVacina.$invalid) {
                if ($scope.formAplicarVacina.$error.required[0]) {
                    Materialize.toast('O campo ' + getDataLabel($scope.formAplicarVacina.$error.required[0].$name) + ' é obrigatório!', 3000, 'toast-error');
                }
            } else {
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
            }
        };





    });
})();