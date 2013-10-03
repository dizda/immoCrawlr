angular.module('accommodationServices', ['ngResource']).
factory('Accommodation', function($resource){
    return $resource('api/accommodations/:id/:operation', {}, {
        query:    {method:'GET',    params: {id:'@id'}, isArray:true},
        favorite: {method:'PATCH',  params: {id:'@id',  operation:'favorite'}},
        viewed:   {method:'PATCH',  params: {id:'@id',  operation:'viewed'}},
        comment:  {method:'POST',   params: {id:'@id',  operation:'comments'}, isArray:true},
        delete:   {method:'DELETE', params: {id:'@id'}},
        versions: {method:'GET',    params: {id:'@id',  operation:'versions'}, isArray:true}
    });
});