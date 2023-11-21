agGrid.initialiseAgGridWithAngular1(angular);

var grid_module = angular.module("GridModule", ["agGrid"]);
   
grid_module.service('grid_service', function($http,BASE_URL) {
    return {
        getData: function(_URL,data) {
             //return the promise directly.
             return $.ajax({
                url: BASE_URL+'/'+_URL,
                type: "GET",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                data : data,
                success: function (response) {
                    return response.data;
                },
                error: function (response) {
                    console.log("failed");
                }
            });
        }
        
    }
});

grid_module.controller("GridController", function($scope,BASE_URL,grid_service) {    
    $scope.send_data = {};

    $scope.defaults  = {
        pageSize            : 5,
        enableColResize     : true,
        enableSorting       : true,
        enableFilter        : true,
        rowHeight           : 40,
        headerHeight        : 40,
        rowModelType        : 'pagination',
        angularCompileRows  : true
    }   




             
});