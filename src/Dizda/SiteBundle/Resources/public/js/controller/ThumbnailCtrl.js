var app = angular.module('indexApp', ['ADE', 'ngSanitize']).
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
     */
    $scope.viewed = function() {

        // if is already readed we dont send additional http request
        if ($scope.isReaded != 'unreaded') {
            return;
        }

        $http.get(Routing.generate('dizda_site_default_setviewed', {'id':$scope.id})).
        success(function(data) {

            if (data.success) {
                $scope.isReaded = ''; // removing 'unreaded' css class
            }

        });

    }

    $scope.favorite = function() {

        $http.get(Routing.generate('dizda_site_default_setfavorite', {'id':$scope.id})).
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
     * Put an accommodation in trash
     */
    $scope.hidden = function() {

        $http.get(Routing.generate('dizda_site_default_sethidden', {'id':$scope.id})).
        success(function(data) {

            if (data.hidden) {
                $scope.isHidden = true;
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


    /*
    * Sending event through ng-click : ng-click="hi($event)"
    *
    * then :
    *
    * $scope.hi = function (e) {
         var elem = angular.element(e.srcElement);
         elem.css('background', 'blue');
         alert(elem.attr('id'));
      }
    * */

});


