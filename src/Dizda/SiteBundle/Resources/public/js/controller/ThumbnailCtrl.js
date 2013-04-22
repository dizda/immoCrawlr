var app = angular.module('indexApp', ['ADE']).
    config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('<[');
        $interpolateProvider.endSymbol('>');
    });


app.controller('RootCtrl', function($rootScope) {

});

app.controller('ThumbnailCtrl', function($scope, $http) {

    $scope.isCommented = false;

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


    /**
     * Add/Modify comment
     */
    $scope.$on('ADE-finish', function(e, data) {

        // handle event only if it was not defaultPrevented
        if(e.defaultPrevented) {
            return;
        }

        //$scope.isCommented = false;

        if (data.oldVal == data.newVal) {
            return;
        }

        $http.post(Routing.generate('dizda_site_default_setnote', {'_format':'json'}), {'id':  data.id,
                                                                                        'text':data.newVal}).
        success(function(data) {


        });

        $scope.isCommented = true;

        // mark event as "not handle in children scopes", to avoid many AJAX request as accommodations number
        e.preventDefault();

    });

});


