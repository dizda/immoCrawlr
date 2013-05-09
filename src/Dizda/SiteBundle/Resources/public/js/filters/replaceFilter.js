angular.module('indexApp').filter('stripProtocol', function() {
    return function(text, param) {
        return text.replace(/http:\/\//i, '');
    }
});