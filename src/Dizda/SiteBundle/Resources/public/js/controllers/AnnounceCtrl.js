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
});
