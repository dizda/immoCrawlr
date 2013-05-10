angular.module('accommodationServices', ['ngResource']).
factory('Accommodation', function($resource){
    return $resource('api/:accommodationsId:ext/:id/:operation', {}, {
        query: {method:'GET', params: {accommodationsId:'accommodations', ext:'.json'}, isArray:true},
        favorite: {method:'PATCH', params: {accommodationsId:'accommodations', id:'@id' , operation:'favorite'}},
        viewed: {method:'PATCH', params: {accommodationsId:'accommodations', id:'@id' , operation:'viewed'}},
        comment: {method:'POST', params: {accommodationsId:'accommodations', id:'@id' , operation:'comment'}, isArray:true},
        delete: {method:'DELETE', params: {accommodationsId:'accommodations', id:'@id'}}
    });
});