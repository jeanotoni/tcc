(function () {
    'user-strict';
    angular.module("tcc").controller("animalController", function ($scope, animalService, pedidoService, vacinaService, racaoService) {

        $scope.insertMultiple = function () {
            animalService.insertMultiple($scope.edit).then(function (response) {
                if (response.data) {
                    listAnimal();
                }
            });
        };

        var listAnimal = function () {
            animalService.listAnimal().then(function (response) {
                $scope.animais = response.data;
            });
        };
        listAnimal();

        $scope.list = [];
        $scope.$watch('list', function (val) {
            console.log(val);
        }, true);

        $scope.addPedido = function () {
            var params = {
                itens: $scope.list,
                model: $scope.model
            };
            pedidoService.salvarPedido(params).then(function (response) {
                if (response.data) {
                    $scope.model = {};
                    $scope.list = {};
                    listAnimal();
                }
            });
        };

        var statusVenda = {
            0: 'Aberto',
            1: 'Vendido'
        };
        $scope.getStatusVenda = function (id) {
            return statusVenda[id];
        };


        $scope.filterListAnimal = {};
        $scope.$watch('filterListAnimal', function (val) {
            if (val.statusVenda == null) {
                delete($scope.filterListAnimal.statusVenda);
            }
        }, true);

        $scope.modal = function (animal) {
            $scope.chekboxInsert = false;
            $scope.listRacoes = null;
            $scope.edit = {};
            $scope.model = {
                dataInicial: new Date()
            };
            $scope.alterAba(1);
            if (animal) {
                $scope.titleModal = 'Editar Animal';
                $scope.btnIcon = 'pencil';
                $scope.btnSalvar = 'EDITAR';
                $scope.edit = angular.copy(animal);
                $scope.edit.dataNascimento = new Date($scope.edit.dataNascimento);

                listRacaoByAnimal($scope.edit.id);
                detailsVaccine($scope.edit.id);
            } else {
                $scope.titleModal = 'Inserir Animal';
                $scope.btnIcon = 'check';
                $scope.btnSalvar = 'SALVAR';
                $scope.edit = {
                    dataNascimento: new Date()
                };
            }
            $('#newAnimal').openModal();
        };

        var detailsVaccine = function (idAnimal) {
            var param = {
                idAnimal: idAnimal
            };
            vacinaService.getDadosAplicacao(param).then(function (response) {
                $scope.details = response.data;
            });
        };

        /**
         * Salvar animal.  Salva animal, verifica se foi tem lastId, exibe feedback, atribui o lastId para a váriavél 
         * $scope.edit.id para que no método addRacao ele possa pegar este mesmo id para atribuir uma ração à um animal
         * sem ter que fechar o modal e abrir de novo, e logo em seguida o próprio botão pressionado para salvar o animal
         * troca de aba para aba 'Ração' somente quando for uma inserção.
         */
        $scope.addAnimal = function () {
            animalService.addAnimal($scope.edit).then(function (response) {
                if (response.data.id) {
                    Materialize.toast('Animal Cod. ' + response.data.id + ' salvo com sucesso!', 4000, 'toast-success');
                    $scope.edit.id = response.data.id;
                    listAnimal();
                } else {
                    Materialize.toast('Falha ao salvar animal Cod. ' + response.data.id, 4000, 'toast-error');
                }
                if (!response.data.updated) {
                    $scope.alterAba(2);
                }
            });
        };

        $scope.deletar = function (id) {
            var rs = confirm("Deseja realmente excluir este animal?");
            if (rs) {
                animalService.deletarAnimal(id).then(function (response) {
                    if (response.data) {
                        Materialize.toast('Animal Cod. ' + id + ' excluído com sucesso!', 4000, 'toast-success');
                        listAnimal();
                    } else {
                        Materialize.toast('Impossível excluir animal Cod. ' + id + ', pois há pedidos ligados à ele.', 5000, 'toast-error');
                    }
                });
            }
        };

        // Lista os clientes para efetuar uma venda
        var listarClientes = function () {
            pedidoService.listarClientes().then(function (response) {
                $scope.clientes = response.data;
            });
        };
        listarClientes();


        $scope.openSellModal = function () {
            $('#sell-modal').openModal({close_esc: true});
        };

//        $scope.openDetailsModal = function (idAnimal) {
//            var param = {
//                idAnimal: idAnimal
//            };
//            vacinaService.getDadosAplicacao(param).then(function (response) {
//                $scope.details = response.data;
//            });
//
//            $('#details-modal').openModal({close_esc: true});
//        };

        $scope.closeModal = function () {
            $('#newAnimal').closeModal();
//            $('#details-modal').closeModal();
            $('#sell-modal').closeModal();
        };

//        $scope.details = function (id) {
//            window.location = '/animal/details/' + id;
//        };
//        $('#newAnimal').openModal();


        /*----------------------------------------------------------------------
         *          ABA DE RAÇÃO
         * ---------------------------------------------------------------------*/
        $scope.aba = 1;
        $scope.alterAba = function (aba) {
            $scope.aba = aba;
        };

        // Depois criar função que traz somente o nome e id da ração para otimizar
        var listarRacao = function () {
            racaoService.listarRacao().then(function (response) {
                $scope.racoes = response.data;
            });
        };
        listarRacao();

        /**
         * Função para atribuir uma ração à um animal dentro do modal do animal na aba 'Rações', se der certo lista os animais
         * sem fechar o modal e limpa os campos da ração($scope.model) estando assim pronta para outra inserção de ração
         */
        $scope.addRacao = function () {
            if ($scope.formRacao.$invalid) {
                console.log($scope.formRacao);
                Materialize.toast('Preencha os campos obrigatórios!', 3000, 'toast-error');
            } else {
                var params = {
                    model: $scope.model,
                    id: $scope.edit.id
                };
                racaoService.addRacaoByAnimal(params).then(function (response) {
                    if (response.data > 0) {
                        Materialize.toast('Ração adicionada com sucesso!', 4000, 'toast-success');
                        listRacaoByAnimal($scope.edit.id);
                        $scope.model = {dataInicial: new Date()};
                    } else {
                        Materialize.toast('Falha ao adicionar ração.', 4000, 'toast-error');
                    }
                });
            }
        };

        $scope.interromperRacao = function (idRacaoItem) {
            var params = {
                idRacaoItem: idRacaoItem,
                dataFinal: new Date()
            };
            racaoService.interromperRacao(params).then(function (response) {
                console.log(response.data);
                if (response.data) {
                    Materialize.toast('Ração - Alimento foi interrompido com sucesso!', 4000, 'toast-success');
                } else {
                    Materialize.toast('Falha ao interromper Ração - Alimento.', 4000, 'toast-error');
                }
            });
        };

        var listRacaoByAnimal = function (idAnimal) {
            racaoService.listRacaoByAnimal(idAnimal).then(function (response) {
                $scope.listRacoes = response.data;
            });
        };

    });
})();