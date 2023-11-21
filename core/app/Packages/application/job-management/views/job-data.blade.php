@extends('layouts.master') @section('title','Add Job Master Data')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/build.css')}}">
    <style type="text/css">

        .box-header, .box-body {
            padding: 20px;
        }

        .dd {
            position: relative;
            display: block;
            margin: 0;
            padding: 0;
            width: 100%;
            list-style: none;
            font-size: 13px;
            line-height: 20px;
        }

        .dd-list {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .dd-list .dd-list {
            padding-left: 30px
        }

        .dd-collapsed .dd-list {
            display: none;
        }

        .dd-item,
        .dd-empty,
        .dd-placeholder {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            min-height: 20px;
            font-size: 13px;
            line-height: 20px;
        }

        .dd-handle {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd-handle:hover {
            color: #2ea8e5;
            background: #fff;
        }

        .dd-item > button {
            display: block;
            position: relative;
            cursor: pointer;
            float: left;
            width: 25px;
            height: 20px;
            margin: 5px 0;
            padding: 0;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 0;
            background: transparent;
            font-size: 12px;
            line-height: 1;
            text-align: center;
            font-weight: bold;
            margin-left: 30px;
        }

        .dd-item > button:before {
            content: '+';
            display: block;
            position: absolute;
            width: 100%;
            text-align: center;
            text-indent: 0;
        }

        .dd-item > button[data-action="collapse"]:before {
            content: '-';
        }

        .dd-placeholder,
        .dd-empty {
            margin: 5px 0;
            padding: 0;
            min-height: 30px;
            background: #f2fbff;
            border: 1px dashed #b6bcbf;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd-empty {
            border: 1px dashed #bbb;
            min-height: 100px;
            background-color: #e5e5e5;
            background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
            linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-size: 60px 60px;
            background-position: 0 0, 30px 30px;
        }

        .dd-dragel {
            position: absolute;
            pointer-events: none;
            z-index: 9999;
        }

        .dd-dragel > .dd-item .dd-handle {
            margin-top: 0;
        }

        .dd-dragel .dd-handle {
            -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
            box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
        }

        /**
         * Nestable Extras
         */

        .nestable-lists {
            display: block;
            clear: both;
            padding: 30px 0;
            width: 100%;
            border: 0;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
        }

        #nestable-menu {
            padding: 0;
            margin: 20px 0;
        }

        #nestable-output,
        #nestable2-output {
            width: 100%;
            height: 7em;
            font-size: 0.75em;
            line-height: 1.333333em;
            font-family: Consolas, monospace;
            padding: 5px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        #nestable2 .dd-handle {
            color: #fff;
            border: 1px solid #999;
            background: #bbb;
            background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
            background: -moz-linear-gradient(top, #bbb 0%, #999 100%);
            background: linear-gradient(top, #bbb 0%, #999 100%);
        }

        #nestable2 .dd-handle:hover {
            background: #bbb;
        }

        #nestable2 .dd-item > button:before {
            color: #fff;
        }

        .dd-hover > .dd-handle {
            background: #2ea8e5 !important;
        }

        /**
         * Nestable Draggable Handles
         */

        .dd3-content {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px 5px 40px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd3-content:hover {
            color: #2ea8e5;
            background: #fff;
        }

        .dd-dragel > .dd3-item > .dd3-content {
            margin: 0;
        }

        .dd3-item > button {
            margin-left: 30px;
        }

        .dd3-handle {
            position: absolute;
            margin: 0;
            left: 0;
            top: 0;
            cursor: pointer;
            width: 30px;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 1px solid #aaa;
            background: #ddd;
            background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
            background: -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
            background: linear-gradient(top, #ddd 0%, #bbb 100%);
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .dd3-handle:before {
            content: '≡';
            display: block;
            position: absolute;
            left: 0;
            top: 3px;
            width: 100%;
            text-align: center;
            text-indent: 0;
            color: #fff;
            font-size: 20px;
            font-weight: normal;
        }

        .dd3-handle:hover {
            background: #ddd;
        }

        .drag_disabled{
            pointer-events: none;
        }

        .drag_enabled{
            pointer-events: all;
        }
    </style>
@stop
@section('content')

    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Job Management</a>
        </li>
        <li class="active">Add Job Master Data</li>
    </ol>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Job Master Data</strong>
                    <div class="panel-tools pull-right">

                        <button class="btn btn-default btn-sm" data-action="expand-all" style="margin-top: 2px;">
                            Expand All
                        </button>
                        <button class="btn btn-default btn-sm" data-action="collapse-all" style="margin-top: 2px;">
                            Collapse all
                        </button>

                        <button type="button" class="btn btn-primary btn-sm"
                                onclick="window.location.href='{{url('job/data/add')}}'">Add Job Data
                        </button>
                    </div>
                </div>


                <div class="panel-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="panel">

                                <div class="panel-body">
                                    <div class="dd" id="nestable">
                                        {!!$tree!!}
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('js')
    <script src="{{asset('assets/vendor/nestable/jquery.nestable.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var data;

            $(".chosen").chosen();
            $('#permissions').multiSelect();

            $('#nestable').nestable('serialize');

            $('.panel-tools').on('click', function (e) {
                //console.log('click');
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            $('#nestable').on('change', function () {
                //console.log('change');
                var depth = 0,
                    list = $(this);
                console.log(list);
                step = function (level, depth) {

                    //console.log(level);
                    var i = 1;
                    var array = [],
                        items = level.children('li');
                    items.each(function () {
                        var li = $(this);
                        if (li.attr('data-type') == 1) {

                            var item = {
                                    'id': li.attr('data-id')
                                    //'ordering': i,
                                    //'type': li.attr('data-type')
                                },
                                sub = li.children('ol');
                            if (sub.length) {
                                item.children = step(sub, depth + 1);
                            }
                            array.push(item);
                            i++;
                        }
                    });
                    return array;
                };
                data = step(list.find('ol').first(), depth);

                console.log(data);

            });

            $('#nestable .menu-delete').click(function (e) {
                e.preventDefault();
                id = $(this).data('id');
                sweetAlertConfirm('Delete data', 'Are you sure?', 2, deleteFunc);
            });


            $('.box-tools').on('click', '.btn-save', function () {

                var tree = window.JSON.stringify(data);

                $.ajax({
                    url: "{{url('admin/product-category/update/hierarchy')}}",
                    type: "post",
                    data: {'tree': tree},
                    success: function (data) {
                        console.log(data);
                        if (data.status == 'success') {
                            sweetAlert('Category updated', 'Category updated successfully!', 0);
                            location.reload();
                        } else {
                            sweetAlert('Error Occured', 'Please try again!', 3);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

            /**
             * Delete the menu
             * Call to the ajax request menu/delete.
             */
            function deleteFunc() {
                ajaxRequest('{{url('job/data/delete')}}', {'id': id}, 'post', handleData);
            }

            /**
             * Delete the menu return function
             * Return to this function after sending ajax request to the menu/delete
             */
            function handleData(data) {
                if (data.status == 'success') {
                    sweetAlert('Delete Success', 'Record Deleted Successfully!', 0);
                    location.reload();
                } else if (data.status == 'invalid_id') {
                    sweetAlert('Delete Error', 'Menu Id doesn\'t exists.', 3);
                } else {
                    sweetAlert('Error Occured', 'Please try again!', 3);
                }
            }


        });
    </script>
@stop
