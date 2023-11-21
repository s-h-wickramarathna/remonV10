@extends('layouts.master') @section('title','Add Invoice')
@section('css')
    <link rel="stylesheet"
          type="text/css"
          href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/mgonto-angular-wizard/angular-wizard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/build.css')}}">
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #CC1A6C;
            border-color: #CC1A6C;
        }

        .chosen-container {
            font-family: 'FontAwesome', 'Open Sans', sans-serif;
        }

        b, strong {
            font-weight: bold;
        }

        .force-to-bottom {
            position: absolute;
            bottom: 0%;
            width: 100%;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Invoice Management</a>
        </li>
        <li class="active">Add Invoice</li>
    </ol>
    <section ng-app="AppModule">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col">
                            <div class="col-xs-9">
                                <div class="panel">
                                    <div class="panel-body">
                                        <wizard on-finish="finishedWizard()">
                                            <wz-step wz-title="Invoicing">
                                                <div class="row">
                                                    <div>
                                                        <div class="col-xs-12" style="padding-top: 10px;">
                                                            <div class="panel panel-bordered"
                                                                 style="min-height: 800px; max-height: 800px;">
                                                                <div class="panel-body form-horizontal">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-1 control-label">OrderId</label>

                                                                        <div class="col-sm-4">
                                                                            {!! Form::select('orderId', [], Input::old('orderId'),['class'=>'chosen','style'=>'width:100%;font-family:\'FontAwesome\'','data-placeholder'=>'Select Order ID']) !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-1 control-label required">Brand</label>

                                                                        <div class="col-sm-4">
                                                                            <select data-placeholder="Choose a Brand"
                                                                                    name="brand" class="chosen-select"
                                                                                    tabindex="2"
                                                                                    style='width:100%;font-family:FontAwesome'>
                                                                                @foreach($brandList as $brand)
                                                                                    <option value="{{$brand->id}}">{{$brand->brand}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-1 control-label ">Category</label>

                                                                        <div class="col-sm-4">
                                                                            <select data-placeholder="Choose a Category"
                                                                                    name="category"
                                                                                    class="chosen-select" tabindex="2"
                                                                                    style='width:100%;font-family:FontAwesome'>
                                                                                @foreach($categoryList as $category)
                                                                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <label class="col-sm-1 control-label text-right">Range</label>

                                                                        <div class="col-sm-4 col-md-offset-1">
                                                                            <select data-placeholder="Choose a Range"
                                                                                    name="range" class="chosen-select"
                                                                                    tabindex="2"
                                                                                    style='width:100%;font-family:FontAwesome'>
                                                                                @foreach($rangeList as $range)
                                                                                    <option value="{{$range->id}}">{{$range->range_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-1 text-center"
                                                                             style="margin-top: 1%;margin-bottom: 1%;">
                                                                            #
                                                                        </div>
                                                                        <div class="col-sm-8">
                                                                            <select data-placeholder="Choose a Paroduct"
                                                                                    name="product"
                                                                                    class="chosen-select chosen-select-product"
                                                                                    tabindex="2"
                                                                                    style='width:100%;font-family:FontAwesome'>
                                                                                @foreach($productList as $product)
                                                                                    <option value="{{$product->id.'-'.$product->price_book['id']}}">{{$product->product_name.' - '.$product->description.' '.$product->pack_size.' - '.$product->price_book['price'].'Rs - '.$product->stock['qty']}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="text"
                                                                                   class="form-control @if($errors->has('qty')) error @endif"
                                                                                   name="qty" placeholder="Qty" required
                                                                                   value="{{Input::old('qty')}}">
                                                                        </div>

                                                                        <div class="col-sm-1">
                                                                            <input class="btn btn-primary pull-right add"
                                                                                   id="addBtn"
                                                                                   type="button"
                                                                                   value="Add"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12" >
                                                                            <table class="table table-bordered bordered table-striped table-condensed datatable">
                                                                                <thead style="background: rgba(204, 204, 204, 0.21);">
                                                                                <tr>
                                                                                    <th rowspan="2" class="text-center" width="4%">#</th>
                                                                                    <th rowspan="2" class="text-center" width="40%" style="font-weight:normal;">Product</th>
                                                                                    <th rowspan="2" class="text-center" width="4%" style="font-weight:normal;">S/Qty</th>
                                                                                    <th rowspan="2" class="text-center" width="10%" style="font-weight:normal;">U/Price</th>
                                                                                    <th rowspan="2" class="text-center" width="4%" style="font-weight:normal;">Qty</th>
                                                                                    <th rowspan="2" class="text-center" width="15%" style="font-weight:normal;">L/Total</th>
                                                                                    <th colspan="1" class="text-center" width="4%" style="font-weight:normal;">Action</th>
                                                                                </tr>
                                                                                <tr style="display: none;">
                                                                                    <th style="display: none;" width="2%"></th>
                                                                                </tr>
                                                                                </thead>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group force-to-bottom">
                                                                        <div class="col-sm-12">
                                                                            <input class="btn btn-info pull-right"
                                                                                   type="submit"
                                                                                   wz-next value="Next"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </wz-step>
                                            <wz-step wz-title="Free Issue">
                                                <div class="row">
                                                    <div>
                                                        <div class="col-xs-12" style="padding-top: 10px;">
                                                            <div class="panel panel-bordered"
                                                                 style="min-height: 700px; max-height: 700px;">
                                                                <div class="panel-body form-horizontal">
                                                                    <div class="form-group freeIssue"
                                                                         style="min-height: 620px; max-height: 620px;overflow-y:scroll">
                                                                    </div>
                                                                    <div class="form-group force-to-bottom">
                                                                        <div class="col-sm-12">
                                                                            <input class="btn btn-info pull-left"
                                                                                   type="submit"
                                                                                   wz-previous value="Back"/>
                                                                            <input class="btn btn-info pull-right"
                                                                                   type="submit"
                                                                                   wz-next value="Next"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </wz-step>
                                            <wz-step wz-title="Discount">
                                                <div class="row">
                                                    <div >
                                                        <div class="col-xs-12" style="padding-top: 10px;">
                                                            <div class="panel panel-bordered"
                                                                 style="min-height: 700px; max-height: 700px;">
                                                                <div class="form-group discount"
                                                                     style="min-height: 620px; max-height: 620px;overflow-y:scroll">
                                                                </div>
                                                                <div class="form-group force-to-bottom">
                                                                    <div class="col-sm-12">
                                                                        <input class="btn btn-info pull-left"
                                                                               type="submit"
                                                                               wz-previous value="Back"/>
                                                                        <input class="btn btn-info pull-right"
                                                                               type="submit"
                                                                               wz-next value="Next"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </wz-step>
                                            <wz-step wz-title="Print">
                                                <div class="row">
                                                    <div>
                                                        <div class="col-xs-12" style="padding-top: 10px;">
                                                            <div class="panel panel-bordered"
                                                                 style="min-height: 700px; max-height: 700px;">
                                                                <div class="form-group force-to-bottom">
                                                                    <div class="col-sm-12">
                                                                        <input class="btn btn-info pull-left"
                                                                               type="submit"
                                                                               wz-previous value="Back"/>
                                                                        <input class="btn btn-primary pull-right"
                                                                               type="submit"
                                                                               wz-next value="Save"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </wz-step>
                                        </wizard>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="col-xs-3">
                                <div class="panel panel-bordered" style="min-height: 870px; max-height: 870px;">
                                    <div class="panel-body ">
                                        <div class="col text-center">
                                            <h2><strong>INVOICE 01</strong></h2>
                                        </div>
                                        <div class="col text-center form-group overviewItem">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('js')
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-switch-master/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{url('assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{url('assets/mgonto-angular-wizard/angular-wizard.js')}}"></script>
    <script src="{{url('/assets/ag_grid/ag-grid.js')}}"></script>
    <script src="{{url('/assets/ag_grid/grid/grid.js')}}"></script>
    <script src="{{url('/assets/js/ejs.js')}}"></script>
    <script type="text/javascript">
        var module = angular.module('AppModule', ['mgo-angular-wizard']);
        $(document).ready(function () {

            window.App={
                invoice:{},
                invoiceDetail:[],
                invoiceDiscount:[]
            }
            var tpl_freeIssue = new EJS({url: '{{url('core/app/Packages/application/invoice-management/views/template/tpl_free_issue')}}'});
            var tpl_discount = new EJS({url: '{{url('core/app/Packages/application/invoice-management/views/template/tpl_discount')}}'});
            var tpl_overview = new EJS({url: '{{url('core/app/Packages/application/invoice-management/views/template/tpl_overview')}}'});

            $('.form-validation').validate();
            $('.datatable').DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": [0,1,2,3,4,5,6] },
                    {"className":"text-center vertical-align-middle", "targets":[0,6]},
                    {"className":"text-right vertical-align-middle", "targets":[2,3,4,5]}
                ],
                paging: true,
                pageLength: 9,
                bFilter: false,
                bLengthChange: false,
                scrollCollapse: true,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]

            });

            var counter = 1;
            var t = $('.datatable').DataTable();
            $('#addBtn').on( 'click', function () {
                var product = $('select[name="product"]');
                var qty = $('input[name="qty"]');
                var productVal = product.val();
                var productText = $('select[name="product"] option[value=' + productVal + ']').text();
                productVal = productVal.split('-');
                productText = productText.split(' - ');
                var total = Number(qty.val())*castToNumber(productText[3]);

                console.log(total);

                t.row.add( [
                    counter ,
                    productText[0]+' - '+productText[1]+' - '+productText[2],
                    productText[4],//5 correct
                    formatCurrency(castToNumber(productText[3])),//4 correct
                    qty.val(),
                    formatCurrency(total),
                    '<a href="#" class="disabled" onclick="" data-toggle="tooltip" data-placement="top" title="Edit Row" ><i class="fa fa-pencil"></i></a>   '+
                    '<a href="#" class="disabled" onclick="" data-toggle="tooltip" data-placement="top" title="Delete Row" ><i class="fa fa-trash"></i></a>'
                ] ).draw( false );
                t.order([0, 'desc']).draw();

                counter++;
            } );

            $('.freeIssue').html(tpl_freeIssue.render({
                id: 2,
                data: {groupName: ['Group 01', 'Group 02']}
            }));

            $('.discount').html(tpl_discount.render({
                id: 2,
                data: {discountName: ['Line Discount', 'Category Dicount']}
            }));

            $('.overviewItem').append(tpl_overview.render({
                text: "LINE TOTAL",
                value: "10,800.00 Rs",
                type: 'tot'
            }));

            $('.overviewItem').append(tpl_overview.render({
                text: "TOTAL DISCOUNT",
                value: "1,580.00 Rs",
                type:'disc'
            }));

            $('.chosen-select').chosen();

            //call on ready
            selectBrand();

            var brand = $('select[name="brand"]');
            brand.change(function (e) {
                selectBrand();
            });

            var category = $('select[name="category"]');
            category.change(function (e) {
                selectCategory();
            });

            var addBtn = $('input[name="addBtn"]');
            addBtn.click(function () {

            });

            /*$('#tot').html('<h3>'+100000+'Rs </h3>');*/

        });

        function selectBrand() {
            var brand = $('select[name="brand"]');
            var range = $('select[name="range"]');
            var category = $('select[name="category"]');
            var product = $('select[name="product"]');
            $.get(
                    '{{url("invoice/getProductByBrand")}}',
                    {'brand': brand.val()},
                    function (data) {
                        var strRange = [];
                        var strCategory = [];
                        var strProduct = [];
                        for (var i = 0; i < data.length; i++) {
                            var currentBrand = '<option value="' + data[i].range[0].id + '">' + data[i].range[0].range_name + '</option>';
                            var currentCategory = '<option value="' + data[i].category[0].id + '">' + data[i].category[0].category_name + '</option>';
                            var currentProduct = '<option value="' + data[i].id + "-" + data[i].price_book.id + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + data[i].description + ' - ' + data[i].pack_size + ' - ' + data[i].price_book.price + 'Rs - ' + data[i].stock.qty + '</option>';
                            if (strRange.indexOf(currentBrand) < 0) {
                                strRange.push(currentBrand);
                            }
                            if (strCategory.indexOf(currentCategory) < 0) {
                                strCategory.push(currentCategory);
                            }
                            if (strProduct.indexOf(currentProduct) < 0) {
                                strProduct.push(currentProduct);
                            }

                        }
                        //range
                        range.html(strRange.join());
                        range.trigger("chosen:updated");

                        //category
                        category.html(strCategory.join());
                        category.trigger("chosen:updated");

                        //category
                        product.html(strProduct.join());
                        product.trigger("chosen:updated");


                    });
        }

        function selectCategory() {
            var range = $('select[name="range"]');
            var category = $('select[name="category"]');
            var product = $('select[name="product"]');
            $.get(
                    '{{url("invoice/getProductByCategory")}}',
                    {'category': category.val()},
                    function (data) {
                        var strRange = [];
                        var strProduct = [];
                        for (var i = 0; i < data.length; i++) {
                            var currentBrand = '<option value="' + data[i].range[0].id + '">' + data[i].range[0].range_name + '</option>';
                            var currentProduct = '<option value="' + data[i].id + "-" + data[i].price_book.price + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + data[i].description + ' - ' + data[i].pack_size + ' - ' + data[i].price_book.price + 'Rs - ' + data[i].stock.qty + '</option>';
                            if (strRange.indexOf(currentBrand) < 0) {
                                strRange.push(currentBrand);
                            }

                            if (strProduct.indexOf(currentProduct) < 0) {
                                strProduct.push(currentProduct);
                            }

                        }
                        //range
                        range.html(strRange.join());
                        range.trigger("chosen:updated");

                        //category
                        product.html(strProduct.join());
                        product.trigger("chosen:updated");


                    });
        }

        function castToNumber(str){
            str = str.replace("Rs", "");
            return isNaN == Number(str)?0:Number(str);
        }

        function formatCurrency(str){
            var n= str.toFixed(2).toString().split(".");
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return n.join(".");
        }

    </script>
@stop
