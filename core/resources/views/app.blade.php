<!doctype html>
<html ng-app="app">
@section('app')
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/ui-grid.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css')}}">
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/angular.min.js')}}"></script>
    <script src="{{asset('assets/js/angular-touch.min.js')}}"></script>
    <script src="{{asset('assets/js/angular-animate.js')}}"></script>
    <script src="{{asset('assets/js/csv.js')}}"></script>
    <script src="{{asset('assets/js/pdfmake.js')}}"></script>
    <script src="{{asset('assets/js/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/js/api-check.min.js')}}"></script>
    <script src="{{asset('assets/js/ui-grid.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <style>
        .grid-msg-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        .grid-msg-overlay .msg {
            opacity: 1;
            position: absolute;
            top: 20%;
            left: 20%;
            width: 60%;
            height: 50%;
            background-color: #eee;
            border-radius: 4px;
            border: 1px solid #555;
            text-align: center;
            font-size: 24px;
            display: table;
        }

        .grid-msg-overlay .msg span {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
    <body ng-controller="MainCtrl as vm">

    <div class="modal fade deleteModel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title msgtit">Warning....</h4>
                </div>
                <div class="modal-body msgBody">
                    <p>Are You Sure...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary saveBtn">OK</button>
                    <button type="button" class="btn btn-default closeBtn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade viewModel" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title msgtit">View...</h4>
                </div>
                <div class="panel-body ">
                    <div class="form-group viewBody" ng-controller="viewController as vm">

                    </div>
                    <div class="form-group ">
                        <div class="modal-footer col-sm-12" align="right">
                            <button type="button" class="btn btn-primary editSaveBtn" style="visibility: hidden;">
                                Save
                            </button>
                            <button type="button" class="btn btn-default closeBtn" data-dismiss="modal">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    @yield('content')
    </body>
</html>