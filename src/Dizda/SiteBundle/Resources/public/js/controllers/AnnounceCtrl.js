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

        Accommodation.favorite({id:$scope.a.id}, function() {

            if ($scope.isReaded != 'favorite') {
                $scope.isReaded  = 'favorite';
                $scope.starState = 'disabled';
            } else {
                $scope.isReaded  = '';
                $scope.starState = '';
            }

        });

    }
});
