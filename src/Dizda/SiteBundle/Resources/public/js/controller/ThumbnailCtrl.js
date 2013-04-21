var app = angular.module('indexApp', ['ADE']).
    config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('<[');
        $interpolateProvider.endSymbol('>');
    });
/*var DemoAppModule = angular.module('DemoApp', ['models']).
    config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('<[');
        $interpolateProvider.endSymbol('>');
    });*/


app.controller('ThumbnailCtrl', function($scope, $http) {


    /**
     * AJAX set thumb viewed on click
     * @param id
     */
    $scope.viewed = function(id) {

        $http.get(Routing.generate('dizda_site_default_setviewed', {'id':id})).
        success(function(data) {

            if (data.success) {
                $scope.isReaded = ''; // removing 'unreaded' css class
            }
        });
    }

    $scope.favorite = function(id) {

        $http.get(Routing.generate('dizda_site_default_setfavorite', {'id':id})).
        success(function(data) {

            if (data.favorite) {
                $scope.isReaded  = 'favorite';
                $scope.starState = 'disabled';
            } else {
                $scope.isReaded  = '';
                $scope.starState = '';
            }
        });
    }


    $scope.$root.$on('ADE-finish', function(e,data) {
        alert('lol');
    });


});


