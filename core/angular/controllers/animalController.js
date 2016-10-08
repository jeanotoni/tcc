(function () {
    'user-strict';
    angular.module("tcc").controller("animalController", function ($scope, animalService) {

        $scope.insertMultiple = function () {
            animalService.insertMultiple($scope.edit).then(function (response) {
                if(response.data){
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
        
        $scope.sell = [];
        $scope.$watch('sell', function(val){
            console.log(val);
        }, true);
        

        $scope.modal = function (animal) {
            $scope.chekboxInsert = false;
            $scope.edit = {};
            if (animal) {
                $scope.edit = angular.copy(animal);
            } else {
                $scope.edit = {
                    dataNascimento: new Date()
                };
            }
            $('#newAnimal').openModal();
        };

        $scope.addAnimal = function () {
            animalService.addAnimal($scope.edit).then(function (response) {
                if (response.data.id) {
                    listAnimal();
                }
            });
        };

        $scope.excluir = function (id) {
            var rs = confirm("Deseja realmente excluir este animal?");
            if (rs) {
                animalService.excluirAnimal(id).then(function (response) {
                    listAnimal();
                });
            }
        };



    });
})();