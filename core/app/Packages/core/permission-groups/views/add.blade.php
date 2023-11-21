@extends('layouts.master') @section('title','Add Permission Group')
@section('css')
    
    <style type="text/css">
    
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color:#CC1A6C;
            border-color: #CC1A6C;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }
        b, strong {
            font-weight: bold;
        }
        .repeat:hover { 
            background-color:#EFEFEF;
        }
        #scrollArea {
            height: 200px;
            overflow: auto;
        }
    </style>
@stop
@section('content')

    <ol class="breadcrumb">
        <li>
            <a href="{{{url('/')}}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Permission</a>
        </li>
        <li>
            <a href="javascript:;">Groups</a>
        </li>
        <li class="active">Add</li>
    </ol>

    <div class="row" ng-app="permissionApp" ng-controller="permissionController">

        <div class="col-xs-12">

            <div class="panel panel-bordered">

                <div class="panel-heading border">
                    <strong>Add Group</strong>
                </div>

                <div class="panel-body">
                    <form role="form" name="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <br>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required" required>Group Name</label>
                            <div class="col-sm-7">
                                <input name="groupName" type="text" class="form-control" placeholder="Group Name" ng-model="groupName" novalidate>
                                    <label id="label-error" class="error"
                                           for="label" ng-bind = "groupNameError">
                                    </label> 
                            </div>
                        </div> 
                        
                        <div class="row">
                            <label class="col-sm-3 control-label required">Permissions</label>
                            <div class="col-sm-3" style="padding-right:0px;padding-left:6px;">
                                <input class="form-control" type="text" name="searchText" placeholder="Search.." ng-model="searchText">
                            </div>
                        </div>
                        <br>
                        <div class="form-group">

                            <div class="well col-sm-3 col-sm-offset-3" style="padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:5px">
                                <div id="scrollArea" ng-controller="ScrollController">
                                    <ul class="list-group">
                                        <li style="height:30px;" class="list-group-item repeat" 
                                        ng-repeat="listItem in list | filter:{name : searchText}" ng-click="push(listItem,permissionList,selectedPermissionList)">@{{listItem.name}}</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="col-sm-1" style="position:relative;display:block;">
                                <div style="position:absolute;margin-top:100%;margin-left:25%">
                                    <img src="{{asset('assets/images/switch.png')}}" alt=""> 
                                </div>
                                
                            </div>

                            <div class="well col-sm-3" style="padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:5px">
                                <div id="scrollArea" ng-controller="ScrollController">
                                    <ul class="list-group">
                                        <li style="height:30px;" class="list-group-item repeat" 
                                        ng-repeat="selectedItem in selectedPermissionList" ng-click="push(selectedItem,selectedPermissionList,permissionList) | orderBy : selectedItem">@{{selectedItem.name}}</li>
                                    </ul>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-sm-offset-9">
                            <div class="col-sm-5">
                                <button class="pull-right btn btn-primary" class="btn btn-primary" ng-click="savePermissionGroup()"><i class="fa fa-floppy-o"></i> Save</button>
                            </div> 
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('js')
    
    <script src="{{asset('assets/angular/angular/angular.min.js')}}"></script>
    <script type="text/javascript">
    /**
     * JS
     */
     var permissionArr = <?php echo json_encode($permissionArr)?>;

     console.log();

    /**
     * Jquery
     */
    $(document).ready(function(){
        
    });

    /**
     * Angular
     */
    var app = angular.module("permissionApp",[]);
    //controller
    app.controller("permissionController", function($scope,$http){
        $scope.groupNameError = "";
        //permission list
        $scope.permissionList = [];
        //user selected permission list
        $scope.selectedPermissionList = [];

        $scope.list = [
            {name:"option 1",id:"1"},
            {name:"option 2",id:"2"},
            {name:"option 3",id:"3"},
            {name:"option 4",id:"4"}
        ];

        $scope.perFilter  = function (item){
            if(item.name==$scope.searchText){
                return true;
            }
        };

        //get permission list - not using 
        function getPermissionList(){
            $.ajax({
                url:'{{url('permission/groups/list')}}',
                type: 'GET',
                data: {},
                success : function(Response){
                    console.log("GET permission list success");
                    createList(Response,$scope.permissionList);
                },
                error: function(Response){
                    console.log("GET permission list failed");
                }
            });
        }

        //save permissoin group
        $scope.savePermissionGroup = function(){
            //console.log("success");
            $.ajax({
                url: '{{url('permission/groups/add')}}',
                type: 'POST',
                data: {groupName:$scope.groupName,searchText:$scope.searchText},
                success: function(Response){
                    console.log("permission/groups/add success");
                },
                error: function( Response ){
                    var errors = $.parseJSON(Response.responseText);
                    console.log();
                    $scope.groupNameError = errors.groupName;
                    $scope.$digest();
                    
                }
            });
        }
        //push item to list  and remove from other list
        $scope.push = function (item,from,to){
            insertLast(item,to);
            deleteItem(item,from);
            //$scope.permissionList.splice(item,1);
        }

        //initialize list variable
        function createList(data){
           $scope.permissionList = data;
        }
        //add item to end of the list
        function insertLast (item,to){
            to.push(item);
        }
        //delete item from permission list
        function deleteItem (item,from){
            from.splice(item,1);
        }

        //sort list by name
        function sortList(list){

        }

        //function calls
        createList(permissionArr);
    });//end controller

    //scroll controller 
    app.controller('ScrollController', ['$scope', '$location', '$anchorScroll',
        function($scope, $location, $anchorScroll){

        }
    ]);
    </script>
@stop
