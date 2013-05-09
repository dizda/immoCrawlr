var app = angular.module('indexApp', ['ADE', 'ngSanitize', 'accommodationServices']).
    config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('{[{');
        $interpolateProvider.endSymbol('}]}');
    });

app.controller('AnnouncesCtrl', function($scope, Accommodation) {

    $scope.announces = Accommodation.query();

});


app.controller('AnnounceCtrl', function($scope, Accommodation) {

    $scope.showPhoto = 0;

    // if announce already favorited, we show the glow and disactive button
    if ($scope.a.favorites.indexOf(user) != -1) {
        $scope.isReaded  = 'favorite';
        $scope.starState = 'disabled';
    }

    if ($scope.a.viewed.indexOf(user) == -1) {
        $scope.isReaded  = 'unreaded';
    }

    /**
     * Showing different photo following mouse cursor position
     *
     * @param e Event
     */
    $scope.movePhoto = function(e)
    {
        var nbPhotos = $scope.a.photos.length;
        var x        = e.pageX - $(e.currentTarget).offset().left; //e.offsetX; // Doesn't work on FF
        var width    = e.currentTarget.offsetWidth;

        $scope.showPhoto = parseInt((x * nbPhotos) / width);
    }

    $scope.favorite = function() {

        Accommodation.favorite({id:$scope.a.id}, function(data) {

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
     * AJAX set thumb viewed on click, then put off the red glow
     */
    $scope.viewed = function() {
        // if is already readed we dont send additional http request
        if ($scope.isReaded != 'unreaded') {
            return;
        }

        Accommodation.viewed({id:$scope.a.id}, function(data) {
            if (data.success) {
                $scope.isReaded = ''; // removing 'unreaded' css class
            }
        });
    }


    /**
     * Add/Modify comment
     */
    $scope.$on('ADE-finish', function(e, data) {


        // handle event only if it was not defaultPrevented OR if concern another scope, we skip it
        if(e.defaultPrevented || data.id != $scope.a.id) {
            return;
        }

        if (data.oldVal == data.newVal) {
            return;
        }

        Accommodation.comment({id:$scope.a.id, text:data.newVal}, function(data) {
            $scope.a.notes = data;
        });

        //$scope.isCommented = true;

        // mark event as "not handle in children scopes", to avoid many AJAX request as accommodations number
        e.preventDefault();

    });
});
