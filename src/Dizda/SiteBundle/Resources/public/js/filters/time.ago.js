angular.module('indexApp').filter('fromNow', function() {
    return function(date) {
        return moment(date).fromNow();
    }
});