/**
 * Description of genarateTable
 *
 * @author G.G.Chathura Sri Chandimal
 * @email chandimal59@gmail.com
 */

function _genarateTable(url, column, permission, routes){
	var app = angular.module('app', ['ngTouch', 'ui.grid', 'ui.grid.pagination', 'ui.grid.selection', 'ui.grid.exporter', 'ngAnimate']);

    app.run(['$templateCache', '$rootScope', function ($templateCache, $rootScope) {
        $rootScope.test = 'test';
        $rootScope.buildJason = 'test 2';

        $templateCache.put('ui-grid/uiGridHeaderCell',
            "<div ng-class=\"{ 'sortable': sortable }\"><div class=\"ui-grid-cell-contents\" col-index=\"renderIndex\"><span>{{ col.displayName CUSTOM_FILTERS }}</span><span ui-grid-visible=\"col.sort.direction\" ng-class=\"{ 'ui-grid-icon-up-dir': col.sort.direction == asc, 'ui-grid-icon-down-dir': col.sort.direction == desc, 'ui-grid-icon-blank': !col.sort.direction }\">&nbsp;</span></div><div class=\"ui-grid-column-menu-button\" ng-if=\"grid.options.enableColumnMenus && !col.isRowHeader  && col.colDef.enableColumnMenu !== false\" ng-click=\"toggleMenu($event)\" ng-class=\"{'ui-grid-column-menu-button-last-col': isLastCol}\"><i class=\"ui-grid-icon-angle-down\">&nbsp;</i></div><div ng-if=\"filterable\" class=\"ui-grid-filter-container\" ng-repeat=\"colFilter in col.filters\"><grid-filter type=\"{{colFilter.type}}\"></grid-filter><div class=\"ui-grid-filter-button\" ng-click=\"colFilter.term = null\"><i class=\"ui-grid-icon-cancel\" ng-show=\"!!colFilter.term\">&nbsp;</i><!-- use !! because angular interprets 'f' as false --></div></div></div>"
        );

        $templateCache.put('ui-grid-filters/text',
            "<input type=\"text\" class=\"ui-grid-filter-input\" ng-model=\"colFilter.term\" ng-attr-placeholder=\"{{colFilter.placeholder || ''}}\">"
        );

    }]);

    app.directive('gridFilter', ['$templateCache', '$compile', '$http', '$rootScope', function ($templateCache, $compile, $http, $rootScope) {
            return {
                restrict: 'AE',
                replace: true,
                link: function (scope, elem, attrs) {
                    var type = attrs['type'] || 'text';
                    var grid = scope.$parent.$parent.grid;

                    var filter = function (val) {
                        $rootScope.data = [{name:scope.$parent.col.name,value:elem[0].value}];
                        $http.get(routes.filter,{
                            params: {
                                data:JSON.stringify([{name:scope.$parent.col.name,value:elem[0].value}]),
                                currentPage: 1,
                                pageSize: 25,
                                filter:1
                            }
                        })
                            .success(function (data){
                                $rootScope.gridOptions.totalItems = data.count;
                                $rootScope.gridOptions.data = data.data;
                                $rootScope.filter=val;
                            });
                    };

                    var template = $compile($templateCache.get('ui-grid-filters/' + type))(scope);
                    elem.replaceWith(template);
                    elem = template;

                    elem.keyup(function (e) {
                        if (e.which == 13) {
                            filter(1);
                        }
                    });

                    scope.$watch('$parent.colFilter.term', function (newVal, oldVal) {
                        if (newVal === null && oldVal !== null) {
                            filter(0);
                        }
                    });
                }
            }
        }]
    );

    if((permission.viewable && permission.editable)&& permission.deletable){
        column.push({
            name: 'Action',
            enableFiltering: false,
            enableSorting: false,
            cellTemplate:url+'/core/resources/views/template/all-button.html',
            width: 100
        });
    }else{
        if((permission.viewable && permission.editable)&& !permission.deletable){
            column.push({
                name: 'Action',
                enableFiltering: false,
                enableSorting: false,
                cellTemplate:url+'/core/resources/views/template/view-edit-button.html',
                width: 70
            });
        }else if((permission.viewable && permission.deletable)&& !permission.editable){
            column.push({
                name: 'Action',
                enableFiltering: false,
                enableSorting: false,
                cellTemplate:url+'/core/resources/views/template/view-delete-button.html',
                width: 70
            });
        }else if((permission.editable && permission.deletable)&& !permission.viewable){
            column.push({
                name: 'Action',
                enableFiltering: false,
                enableSorting: false,
                cellTemplate:url+'/core/resources/views/template/delete-edit-button.html',
                width: 70
            });
        }else{
            if (permission.viewable) {
                column.push({
                    name: 'Action',
                    enableFiltering: false,
                    enableSorting: false,
                    cellTemplate:url+'/core/resources/views/template/view-button.html',
                    width: 70
                });
            }

            if (permission.editable) {
                column.push({
                    name: 'Action',
                    enableFiltering: false,
                    enableSorting: false,
                    cellTemplate: url+'/core/resources/views/template/edit-button.html',
                    width: 70
                });
            }

            if (permission.deletable) {
                column.push({
                    name: 'Action',
                    enableFiltering: false,
                    enableSorting: false,
                    cellTemplate: url+'/core/resources/views/template/delete-button.html',
                    width: 70
                });
            }
        }
    }

    app.controller('MainCtrl', [
        '$scope', '$http', 'uiGridConstants', '$rootScope', '$interval', function ($scope, $http, uiGridConstants, $rootScope, $interval) {

            var paginationOptions = {
                pageNumber: 1,
                pageSize: 25,
                sort: null
            };
            var date = new Date();
            $rootScope.gridOptions = {
                paginationPageSizes: [25, 50, 75],
                paginationPageSize: 25,
                useExternalPagination: true,
                enableFiltering: true,
                useExternalFiltering: true,
                useExternalSorting: true,
                columnDefs: column,
                enableGridMenu: true,
                enableSelectAll: true,
                exporterCsvFilename: date.getFullYear()+''+date.getMonth()+''+date.getDate()+''+date.getTime()+'.csv',
                exporterPdfFooter: function (currentPage, pageCount) {
                    return {text: currentPage.toString() + ' of ' + pageCount.toString(), style: 'footerStyle'};
                },
                exporterPdfCustomFormatter: function (docDefinition) {
                    docDefinition.styles.headerStyle = {fontSize: 22, bold: true};
                    docDefinition.styles.footerStyle = {fontSize: 10, bold: true};
                    return docDefinition;
                },
                exporterPdfPageSize: 'A4',
                exporterCsvLinkElement: angular.element(document.querySelectorAll(".custom-csv-link-location")),
                onRegisterApi: function (gridApi) {
                    $scope.gridApi = gridApi;
                }
                ,
                onRegisterApi: function (gridApi) {
                    $scope.gridApi = gridApi;
                    $scope.gridApi.core.on.sortChanged($scope, function (grid, sortColumns) {
                        if (sortColumns.length == 0) {
                            paginationOptions.sort = null;
                        } else {
                            paginationOptions.sort = sortColumns[0].sort.direction;
                        }
                        getPage();
                    });
                    gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
                        paginationOptions.pageNumber = newPage;
                        paginationOptions.pageSize = pageSize;
                        getPage();
                    });
                    getPage();
                }
            };

            var getPage = function () {
                var url;
                var page = paginationOptions.pageNumber;
                var pageSize = paginationOptions.pageSize;

                switch (paginationOptions.sort) {
                    case uiGridConstants.ASC:
                        url = routes.data;
                        break;
                    case uiGridConstants.DESC:
                        url = routes.data;
                        break;
                    default:
                        url = routes.data;
                        break;
                }
                url = $rootScope.filter>0?routes.filter:routes.data;
                $http
                        .get(url, {
                            params: {
                                data :JSON.stringify($rootScope.data),
                                currentPage: page,
                                pageSize: pageSize,
                                filter:$rootScope.filter
                            }
                        })
                        .success(function (data) {
                        $rootScope.gridOptions.totalItems = data.count;
                        $rootScope.gridOptions.data = data.data;
                            $rootScope.test = $scope;
                            $rootScope.buildJason = data.buildJson;
                        });
            };


            $scope.getData = function () {
                return $scope.buildJson;
            }

            $scope.change = function () {
                getPage();
            };
        }
    ]);

    app.controller('viewController', ['$scope', '$rootScope', ViewController]);
    function ViewController($scope, $rootScope) {
        $scope.viewRow = function (row) {
            var str = [], buildJason = $rootScope.buildJason, obj = row.entity;
            for (var i = 0; i < buildJason.length; i++) {
                temp = buildJason[i] === 'id' | buildJason[i] === 'updated_at' | buildJason[i] === 'deleted_at' | buildJason[i] === 'created_at' ? 'style="visibility: hidden;"' : ' ';
                str.push('<label class="col-sm-4 control-label required">' + buildJason[i].toUpperCase().replace(/_/g, " ") + '</label>'
                        + '<div class="col-sm-8"><input type="text" class="form-control" readonly="readonly" id="data_' + i + '"  value="' + obj[buildJason[i]] + '"  ' + temp + '  /></div>'
                        + '<div class="col-md-12">&nbsp</div>'
                        + '</div>'
                        + '</div>');
            }
            $('.viewBody').html(str.join().replace(/,/g, ''));

            $('.saveBtn').css("visibility", "hidden");
            $('.viewModel').modal('show');
            return;
        }

        $scope.editRow = function (row) {
            console.log(row.entity);
            var str = [], buildJason = $rootScope.buildJason, obj = row.entity;
            for (var i = 0; i < buildJason.length; i++) {
                temp = buildJason[i] === 'id' | buildJason[i] === 'updated_at' | buildJason[i] === 'deleted_at' | buildJason[i] === 'created_at' ? 'style="visibility: hidden;"' : ' ';
                str.push('<label class="col-sm-4 control-label required">' + buildJason[i].toUpperCase().replace(/_/g, " ") + '</label>'
                    + '<div class="col-sm-8"><input type="text" class="form-control" id="data_' + i + '"  value="' + obj[buildJason[i]] + '"  ' + temp + ' /></div>'
                    + '<div class="col-md-12">&nbsp</div>'
                    + '</div>'
                    + '</div>');
            }
            $('.viewBody').html(str.join().replace(/,/g, ''));
            $('.viewModel').modal('show');
            $('.saveBtn').css("visibility", "visible");
            return;
        }

        $scope.deleteRow = function (row) {
            $('.deleteModel').modal('show');
            $('.saveBtn').show();
            $('.msgtit').text('Confirm...');
            $('.msgBody').text('Are You Sure...');
            $('.saveBtn').click(function () {
                $('.deleteModel').modal('hide');
                $rootScope.test.change();
                console.log($rootScope.test);
                console.log(row.entity.sequece);
            });
        }
    }
}