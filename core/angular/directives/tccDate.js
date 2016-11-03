
/**
 
 * 
 * Fazendo diretiva para tratar data no input, mas por enquanto não foi necessária pois o tratamento foi feito no controllerjs mesmo
 */
angular.module("tcc").directive('tccDate', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        
        link: function ($scope, $element, $attr, $ngModel){
                        
            console.log(typeof $ngModel.$modelValue);
            console.log($ngModel.$modelValue);
            
            if(typeof $ngModel.$modelValue == 'string'){
                alert('oi');
                $ngModel.$modelValue = new Date($ngModel.$modelValue);
            }
        
        }
    };
});