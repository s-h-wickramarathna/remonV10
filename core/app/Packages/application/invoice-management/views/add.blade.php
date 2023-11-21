@extends('layouts.master') @section('title','Add Invoice')
@section('css')
    <link rel="stylesheet"
          type="text/css"
          href="{{asset('assets/vendor/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.css')}}">
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

        .pager {
            margin: 0px 0;
        }

        .ctrl {
            border-radius: 30px;
        }

        .ctrl:hover {
            border-radius: 30px;
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
        <li>
            <a href="{{url('invoice/customerlist')}}">Customer List</a>
        </li>
        <li class="active">Add Invoice</li>
    </ol>
    <section ng-app="AppModule">
        <div class="row">
            <div class="col-xs-12 ">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col">
                            <div class="col-xs-9">
                                <div class="panel refresh panel-refreshing">
                                    <div class="panel-body">
                                        <wizard on-finish="finishedWizard()">
                                            <wz-step wz-title="Invoicing" canexit="isValid">
                                                <div class="row">
                                                    <div class="col-xs-12" style="padding-top: 10px;">
                                                        <div class="panel panel-bordered"
                                                             style="min-height: 700px; max-height: 700px;">
                                                            <div class="panel-body form-horizontal">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <button class="btn btn-info pull-right ctrl"
                                                                                id="next_1" name="next_1" wz-next
                                                                                onclick="calcDiscount()">Next <i
                                                                                    class="fa fa-angle-double-right"
                                                                                    aria-hidden="true"></i></button>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="col-sm-1 text-center"
                                                                         style="margin-top: 1%;margin-bottom: 1%;">
                                                                        #
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <select data-placeholder="Choose a Product"
                                                                                name="product"
                                                                                class="chosen-select chosen-select-product"
                                                                                tabindex="2"
                                                                                title="Short Code - Product Name - Description - Pack Size - Price - Stock Qty"
                                                                                style='width:100%;font-family:FontAwesome'>
                                                                            @foreach($productList as $product)
                                                                                <?php $stock = sizeof($product->stock) > 0 ? $product->stock[0]['qty'] : 0;?>
                                                                                <option value="{{$product->id.'-'.$product->price_book['id']}}">{{$product->product_name.' - '.$product->description.' '.$product->pack_size.' - '.$product->price_book['price'].'Rs - '.$stock}}</option>
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
                                                                    <div class="col-sm-1 text-center"
                                                                         style="margin-top: 1%;margin-bottom: 1%;">

                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <select data-placeholder="Choose Editing Charges"
                                                                                name="e_product"
                                                                                id="e_product"
                                                                                class="chosen-select"
                                                                                tabindex="2"
                                                                                style='width:100%;font-family:FontAwesome'>
                                                                            @foreach($e_productList as $product)
                                                                                <option value="{{$product->id}}">{{$product->product_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <select data-placeholder="Choose Editor"
                                                                                name="editor"
                                                                                id="editor"
                                                                                class="chosen-select"
                                                                                tabindex="2"
                                                                                style='width:100%;font-family:FontAwesome'>
                                                                            <option value="0">Select Editor</option>
                                                                            @foreach($editorList as $editor)
                                                                                <option value="{{$editor->id}}">{{$editor->first_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="text"
                                                                               class="form-control @if($errors->has('e_price')) error @endif"
                                                                               name="e_price" placeholder="Chargers"
                                                                               required
                                                                               value="{{Input::old('e_price')}}">
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <input class="btn btn-primary"
                                                                               style="width: 160px"
                                                                               id="addChargers"
                                                                               type="button"
                                                                               value="Add Chargers"/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <table id="datatable"
                                                                               class="table table-bordered bordered table-striped table-condensed datatable">
                                                                            <thead style="background: rgba(204, 204, 204, 0.21);">
                                                                            <tr>
                                                                                <th rowspan="2" class="text-center"
                                                                                    width="4%">#
                                                                                </th>
                                                                                <th rowspan="2" class="text-center"
                                                                                    width="40%"
                                                                                    style="font-weight:normal;">
                                                                                    Product
                                                                                </th>
                                                                                <th rowspan="2" class="text-center"
                                                                                    width="10%"
                                                                                    style="font-weight:normal;">
                                                                                    U/Price
                                                                                </th>
                                                                                <th rowspan="2" class="text-center"
                                                                                    width="10%"
                                                                                    style="font-weight:normal;">Qty
                                                                                </th>
                                                                                <th rowspan="2" class="text-center"
                                                                                    width="10%"
                                                                                    style="font-weight:normal;">Disc
                                                                                </th>
                                                                                <th rowspan="2" class="text-center"
                                                                                    width="20%"
                                                                                    style="font-weight:normal;">
                                                                                    L/Total
                                                                                </th>
                                                                                <th colspan="2" class="text-center"
                                                                                    width="6%"
                                                                                    style="font-weight:normal;">
                                                                                    Action
                                                                                </th>
                                                                            </tr>
                                                                            <tr style="display: none;">
                                                                                <th style="display: none;"
                                                                                    width="2%"></th>
                                                                                <th style="display: none;"
                                                                                    width="2%"></th>
                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </wz-step>
                                            <wz-step wz-title="Discount">
                                                <div class="row">
                                                    <div>
                                                        <div class="col-xs-12" style="padding-top: 10px;">
                                                            <div class="panel panel-bordered"
                                                                 style="min-height: 700px; max-height: 700px;">
                                                                <div class="panel-body form-horizontal">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <button class="btn btn-info pull-right ctrl"
                                                                                    name="next_3" wz-next
                                                                                    onclick="printView()">Next <i
                                                                                        class="fa fa-angle-double-right"
                                                                                        aria-hidden="true"></i></button>
                                                                            <button class="btn btn-info pull-right ctrl"
                                                                                    name="back_2" wz-previous
                                                                            ><i
                                                                                        class="fa fa-angle-double-left"
                                                                                        aria-hidden="true"></i> Back
                                                                            </button>
                                                                        </div>
                                                                    </div>


                                                                    <div class="form-group discount"
                                                                         style="min-height: 620px; max-height: 620px;overflow-y:scroll">
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
                                                                <div class="panel-body form-horizontal">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <button class="btn btn-info pull-right ctrl"
                                                                                    name="save" onclick="save()">Save <i
                                                                                        class="fa fa-floppy-o"
                                                                                        aria-hidden="true"></i></button>
                                                                            <button class="btn btn-info pull-right ctrl"
                                                                                    name="save" onclick="save_pay()">
                                                                                Payment <i
                                                                                        class="fa fa-money"
                                                                                        aria-hidden="true"></i></button>
                                                                            <button class="btn btn-info pull-right ctrl"
                                                                                    name="back_2" wz-previous
                                                                            ><i
                                                                                        class="fa fa-angle-double-left"
                                                                                        aria-hidden="true"></i> Back
                                                                            </button>

                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group finalView"
                                                                         style="min-height: 620px; max-height: 620px">
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
                                <div class="panel panel-bordered" style="min-height: 770px; max-height: 770px;">
                                    <div class="panel-body ">
                                        <div class="col orderID" style="text-align: left">
                                            <input type="hidden" name="outlet_id" value="{{$outlet->id}}">
                                            {{--<input type="hidden" name="job_no" value="">
                                            <input type="hidden" id="couple_name" name="couple_name" value="">--}}
                                            <input type="hidden" name="outlet_name"
                                                   value="{{$outlet->f_name .' '. $outlet->l_name}}">
                                            <input type="hidden" id="marketeer_credit_limit"
                                                   value="{{$marketeer->credit_limit}}">
                                            <input type="hidden" name="outlet_addess" value="{{$outlet->address}}">
                                            <h4><strong>{{$outlet->f_name .' '. $outlet->l_name}}</strong></h4>
                                            <h5>Payment Type - <span id="payment_type">Cash</span></h5>
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

        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Edit Line</h4>
                    </div>
                    <div class="modal-body form-horizontal">
                        <div class="row form-group modalRow ">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary saveEdit"><i class="fa fa-floppy-o"
                                                                                  aria-hidden="true"></i> Save
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="modal discount_fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Edit Discount</h4>
                    </div>
                    <div class="modal-body form-horizontal">
                        <div class="row form-group modalDiscount">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary saveEdit"><i class="fa fa-floppy-o"
                                                                                  aria-hidden="true"></i> Save
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </section>
@stop
@section('js')
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-switch-master/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/vendor/jquery-toast-plugin-master/dist/jquery.toast.min.js')}}"></script>
    <script src="{{url('assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{url('assets/mgonto-angular-wizard/angular-wizard.js')}}"></script>
    <script src="{{url('/assets/ag_grid/ag-grid.js')}}"></script>
    <script src="{{url('/assets/ag_grid/grid/grid.js')}}"></script>
    <script src="{{url('/assets/js/ejs.js')}}"></script>
    <script type="text/javascript">
        var module = angular.module('AppModule', ['mgo-angular-wizard']);
        $(document).ready(function () {

            window.App = {
                invoice: {},
                invoiceDetail: [],
                invoiceDiscount: [],
                invoiceFreeIssue: [],
                counter: 1,
                tempDisc: [],
                guarded: [],
                outstanding: 0,
                creditLimit: 0,
                freeguarded: [],
                openFreeCounter: 1,
                payment_type:'cash'
            }

            var tpl_overview = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_overview')}}'});


            $('.form-validation').validate();
            $('.datatable').DataTable({
                "columnDefs": [
                    {"orderable": false, "targets": [0, 1, 2, 3, 4, 5, 6]},
                    {"className": "text-center vertical-align-middle", "targets": [0, 6]},
                    {"className": "text-right vertical-align-middle", "targets": [2, 3, 4, 5]}
                ],
                bFilter: false,
                bLengthChange: false,
                bPaginate: false,
                scrollCollapse: false

            });


            var outstanding = {{$outstanding}};
            var creditLimit = {{$creditlimit}};
            var marketeer_credit_limit = {{$marketeer->credit_limit}};
            var max_discount = 0;
            var max_freeissue = 0;
            window.App.outstanding = outstanding;
            window.App.creditLimit = creditLimit;
            window.App.maxDiscount = max_discount;
            window.App.maxFreeissue = max_freeissue;

            if (outstanding > creditLimit) {
                showToast('info', 'Information', 'This outlet credit limit over...');
            }

            $.confirm({
                title: 'Confirm!',
                content: 'Select Payment Type',
                buttons: {
                    cash: function () {
                        window.App.payment_type = 'cash';
                        $('#payment_type').text('Cash');
                    },
                    credit: function () {
                        window.App.payment_type = 'credit';
                        $('#payment_type').text('Credit');
                    }
                }
            });

            var qty = $('input[name="qty"]');
            qty.keyup(function () {
                var product = $('select[name="product"]');
                var qty = $('input[name="qty"]');
                var productVal = product.val();
                var productText = $('select[name="product"] option[value="' + productVal + '"]').text();
                productText = productText.split(' - ');
                var lPrice = productText[(productText.length - 2)];
                var lSQty = productText[(productText.length - 1)];

                if (qty.val() > 0) {
                    if (isInteger($(this).val())) {
                        $('.lPrice').text(formatCurrency(Number($(this).val()) * lPrice));
                    } else {
                        $(this).val('');
                    }
                } else {
                    $(this).val('');
                    showToast('info', 'information', 'can\'t add zero quantity..');
                }

            });


            var t = $('.datatable').DataTable();
            $('#addBtn').on('click', function () {
                //$('input[name="qty"]').prop('disabled',true);
                $('.refresh').addClass('panel-refreshing');
                //  showToast('info', 'Information', 'it\'s not ready yet');
                var product = $('select[name="product"]');
                var qty = $('input[name="qty"]');
                if (qty.val() > 0) {
                    var productVal = product.val();
                    var productText = $('select[name="product"] option[value="' + productVal + '"]').text();
                    // productVal = productVal.split('-');
                    productText = productText.split(' - ');
                    console.log(productText);
                    var lPrice = productText[(productText.length - 1)];
                    //var lSQty = productText[(productText.length - 1)];
                    var total = Number(qty.val()) * castToNumber(lPrice);

                    var detail = {
                        batch_id: productVal,
                        free_qty: 0,
                        group_id: 0,
                        price_book_detail_id: productVal,
                        product_id: productVal,
                        qty: qty.val(),
                        discount: 0,
                        unit_price: castToNumber(lPrice),
                        product_name: productText[0] + ' - ' + productText[1]
                    };
                    window.App.invoiceDetail.push(detail);

                    //console.log(window.App.invoiceDetail);

                    t.row.add([
                        window.App.counter,
                        productText[0] + ' - ' + productText[1],
                        formatCurrency(castToNumber(lPrice)),//4 correct
                        qty.val(),
                        '<a href="#" class="addDiscount ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Add Discount" ><span class="badge" style="background:#0081c2"><i class="fa fa-plus"> </i> discount</span></a>',
                        formatCurrency(total),
                        '<a href="#"  class="editRow ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Edit Row" ><i class="fa fa-pencil"></i></a>',
                        '<a href="#"  class="delRow ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Delete Row" ><i class="fa fa-trash"></i></a>'
                    ]).draw(false);
                    t.order([0, 'desc']).draw();
                    window.App.counter++;

                    var lineTot = 0, discount = 0;
                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });

                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency(discount),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot - discount),
                        type: 'g_tot'
                    }));
                    window.App.guarded.push(productVal[0]);
                    qty.val('');
                } else {
                    showToast('info', 'information', 'please enter valid quantity..');
                }
                if (!validateNext()) {
                    $('#next_1').addClass('disabled');
                } else {
                    $('#next_1').removeClass('disabled');
                }

                //$('.refresh').addClass('panel-refreshing');
                $('.refresh').removeClass('panel-refreshing');
                //selectBrand();
            });

            $('input[name="qty"]').keyup(function (e) {
                if (e.keyCode == 13) {
                    $('#addBtn').click();
                }
            });

            $('#addChargers').on('click', function () {
                //$('input[name="qty"]').prop('disabled',true);

                $('.refresh').addClass('panel-refreshing');
                //  showToast('info', 'Information', 'it\'s not ready yet');
                var product = $('select[name="product"]');

                var editor = $('select[name="editor"]');

                var qty = $('input[name="e_price"]');
                if (qty.val() > 0) {
                    if (editor.val() > 0 || $('select[name="e_product"]').val() != 85) {
                        var productVal = $('select[name="e_product"]').val();

                        var detail = {
                            batch_id: productVal,
                            free_qty: editor.val(),
                            group_id: 0,
                            price_book_detail_id: productVal,
                            product_id: productVal,
                            qty: 1,
                            unit_price: castToNumber(qty.val()),
                            discount: 0,
                            product_name: 'Editing Chargers'
                        };
                        window.App.invoiceDetail.push(detail);

                        //console.log(window.App.invoiceDetail);

                        if ($('select[name="e_product"]').val() == 85) {
                            t.row.add([
                                window.App.counter,
                                'Editing Chargers',
                                formatCurrency(castToNumber(qty.val())),//4 correct
                                1,
                                '-',
                                formatCurrency(qty.val()),
                                '<a href="#"  class="editRow ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Edit Row" ><i class="fa fa-pencil"></i></a>',
                                '<a href="#"  class="delRow ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Delete Row" ><i class="fa fa-trash"></i></a>'
                            ]).draw(false);
                            t.order([0, 'desc']).draw();
                        } else {
                            t.row.add([
                                window.App.counter,
                                'Transport Chargers',
                                formatCurrency(castToNumber(qty.val())),//4 correct
                                1,
                                '-',
                                formatCurrency(qty.val()),
                                '<a href="#"  class="editRow ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Edit Row" ><i class="fa fa-pencil"></i></a>',
                                '<a href="#"  class="delRow ' + window.App.counter + '" data-toggle="tooltip" data-placement="top" title="Delete Row" ><i class="fa fa-trash"></i></a>'
                            ]).draw(false);
                            t.order([0, 'desc']).draw();
                        }
                        window.App.counter++;

                        var lineTot = 0, discount = 0;
                        $('#datatable').children('tbody').children('tr').each(function () {
                            lineTot += convertToNumber($(this).children('td').eq(5).text());
                        });

                        addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                        $('.overviewItem').append(tpl_overview.render({
                            text: "TOTAL DISC",
                            value: "RS " + formatCurrency(discount),
                            type: 'disc'
                        }));

                        $('.overviewItem').append(tpl_overview.render({
                            text: "GRAND TOTAL",
                            value: "RS " + formatCurrency(lineTot - discount),
                            type: 'g_tot'
                        }));
                        qty.val('');
                    } else {
                        showToast('info', 'information', 'please select editor..');
                    }
                } else {
                    showToast('info', 'information', 'please enter valid price..');
                }
                if (!validateNext()) {
                    $('#next_1').addClass('disabled');
                } else {
                    $('#next_1').removeClass('disabled');
                }

                $('.refresh').removeClass('panel-refreshing');
                // selectBrand();
            });

            $('input[name="e_price"]').keyup(function (e) {
                if (e.keyCode == 13) {
                    $('#addChargers').click();
                }
            });

            var product = $('select[name="product"]');
            product.change(function (e) {
                //console.log($('input[name="qty"]'));
                focusInput();
                //$('input[name="qty"]').focus();
            });

            $('input[name="qty"]').focus();

            $('.chosen-select').chosen();

            //call on ready
            // selectBrand();

            var brand = $('select[name="brand"]');
            brand.change(function (e) {
                isEmpty();
            });

            var category = $('select[name="category"]');
            category.change(function (e) {
                $('.refresh').addClass('panel-refreshing');
                selectCategory();
            });

            var free_category = $('select[name="free_category"]');
            free_category.change(function (e) {
                $('.refresh').addClass('panel-refreshing');
                selectCategoryForFree();
            });

            var range = $('select[name="range"]');
            range.change(function (e) {
                $('.refresh').addClass('panel-refreshing');
                selectRange();
            });

            var free_range = $('select[name="free_range"]');
            free_range.change(function (e) {
                $('.refresh').addClass('panel-refreshing');
                selectRangeForFreeIssue();
            });

            var addBtn = $('input[name="addBtn"]');
            addBtn.click(function () {

            });

            /*$('#tot').html('<h3>'+100000+'Rs </h3>');*/

            // $('.col-xs-12').removeClass('panel-refreshing');

            if (!validateNext()) {
                $('#next_1').addClass('disabled');
            } else {
                $('#next_1').removeClass('disabled');
            }
            selectBrand();

            $('.refresh').removeClass('panel-refreshing');

            $('#datatable tbody').on('click', 'tr td a', function () {
                console.log($(this).attr('class'));
                var option = $(this).attr('class').split(' ')[0];
                if (option == 'editRow') {
                    var tpl_row_edit = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_row_edit')}}'});

                    var productName = $(this).parent('td').parent('tr').children('td')[1].innerHTML;
                    var uPrice = $(this).parent('td').parent('tr').children('td')[2].innerHTML;
                    var qty = $(this).parent('td').parent('tr').children('td')[3].innerHTML;
                    var lPrice = $(this).parent('td').parent('tr').children('td')[5].innerHTML;
                    var index = $(this).attr('class').split(' ')[1];
                    //$('.refresh').addClass('panel-refreshing');
                    $('.fade').modal('show');
                    $('.modalRow input').focus();
                    $('.modalRow').html(tpl_row_edit.render({
                        product_name: productName + ' - ' + uPrice,
                        stock: 0,
                        qty: qty,
                        lPrice: (convertToNumber(uPrice) * qty),
                        gPrice: (convertToNumber(uPrice) * qty),
                        index: index
                    }));

                    $('.qty').keyup(function () {
                        if (isInteger($(this).val())) {
                            $('.lPrice').text(formatCurrency(Number($(this).val()) * convertToNumber(uPrice)));
                            $('.gPrice').text(formatCurrency(Number($(this).val()) * convertToNumber(uPrice)));
                            lPrice = $('.lPrice').text();
                        } else {
                            $(this).val('');
                            $('.lPrice').html(formatCurrency(0));
                            $('.gPrice').html(formatCurrency(0));
                        }
                    });

                    $('.disc_per').keyup(function () {
                        if (isInteger($(this).val()) && $(this).val() < 100 && $(this).val() > 0) {
                            console.log(lPrice);
                            $('.gPrice').text(formatCurrency(convertToNumber(lPrice) - ((Number($(this).val()) * convertToNumber(lPrice)) / 100)));
                        } else {
                            $(this).val('');
                            $('.gPrice').html(formatCurrency(lPrice));
                        }
                    });

                    $('.disc_val').keyup(function () {
                        if (isInteger($(this).val()) && $(this).val() < convertToNumber(lPrice) && $(this).val() > 0) {
                            console.log(lPrice);
                            $('.gPrice').text(formatCurrency(convertToNumber(lPrice) - Number($(this).val())));
                        } else {
                            $(this).val('');
                            $('.gPrice').html(formatCurrency(lPrice));
                        }
                    });

                    $('input[name="type"]').change(function () {
                        console.log($(this).val());
                        if ($(this).val() == 'Percentage') {
                            $('.per').toggle();
                            $('.val').toggle();
                        } else {
                            $('.val').toggle();
                            $('.per').toggle();
                        }
                        $('.gPrice').html(formatCurrency(lPrice));
                        $('.disc_val').val('');
                        $('.disc_per').val('');
                    });


                    $('.saveEdit').click(function () {
                        if ($('.qty').val() > 0) {
                            var rowCount = 0;
                            $('#datatable').children('tbody').children('tr').each(function () {
                                ++rowCount;
                            });
                            var arrayIndex = $('.index').val();
                            var table = $('.datatable').DataTable();
                            var index = 0;
                            if (rowCount == 1 && rowCount < arrayIndex) {
                                index = (arrayIndex - rowCount) - 1;
                            } else {
                                index = eval(arrayIndex) - 1;
                            }
                            var data = table.row(index).data();

                            var line_discount = $('.disc_val').val() > 0 ? formatCurrency($('.disc_val').val()) : formatCurrency((Number($('.disc_per').val()) * convertToNumber(lPrice)) / 100);

                            data[3] = $('.qty').val();
                            data[4] = line_discount;
                            data[5] = $('.gPrice').text();

                            table.row(index).data(data).draw();

                            lineTot = 0, discount = 0;
                            $('#datatable').children('tbody').children('tr').each(function () {
                                lineTot += convertToNumber($(this).children('td').eq(5).text());
                            });

                            window.App.invoiceDetail[eval(arrayIndex) - 1].qty = $('.qty').val();
                            window.App.invoiceDetail[eval(arrayIndex) - 1].discount = convertToNumber(line_discount);

                            addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                            $('.overviewItem').append(tpl_overview.render({
                                text: "TOTAL DISC",
                                value: "RS " + formatCurrency(discount),
                                type: 'disc'
                            }));

                            $('.overviewItem').append(tpl_overview.render({
                                text: "GRAND TOTAL",
                                value: "RS " + formatCurrency(lineTot - discount),
                                type: 'g_tot'
                            }));
                            $(".fade").modal('hide');
                        } else {
                            showToast('info', 'information', 'can\'t add zero quantity..');
                        }
                    });
                } else if (option == 'delRow') {
                    $('.refresh').addClass('panel-refreshing');
                    var t = $('.datatable').DataTable();
                    t.row($(this).parent('td').parent('tr'))
                        .remove()
                        .draw();

                    var index = $(this).attr('class').split(' ')[1];
                    delete window.App.invoiceDetail[(index - 1)];
                    delete window.App.guarded[(index - 1)];

                    var lineTot = 0, discount = 0;
                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency(discount),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot - discount),
                        type: 'g_tot'
                    }));
                    if (!validateNext()) {
                        $('#next_1').addClass('disabled');
                    } else {
                        $('#next_1').removeClass('disabled');
                    }
                    $('.refresh').removeClass('panel-refreshing');
                    //selectBrand();

                } else {
                    var tpl_row_edit = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_row_discount')}}'});

                    var productName = $(this).parent('td').parent('tr').children('td')[1].innerHTML;
                    var uPrice = $(this).parent('td').parent('tr').children('td')[2].innerHTML;
                    var qty = $(this).parent('td').parent('tr').children('td')[3].innerHTML;
                    var lPrice = $(this).parent('td').parent('tr').children('td')[5].innerHTML;
                    var index = $(this).attr('class').split(' ')[1];
                    //$('.refresh').addClass('panel-refreshing');
                    $('.fade').modal('show');
                    $('.modalRow input').focus();
                    $('.modalRow').html(tpl_row_edit.render({
                        product_name: productName + ' - ' + uPrice,
                        lAmount: (convertToNumber(uPrice) * qty),
                        lPrice: lPrice,
                        index: index
                    }));

                    $('.disc_per').keyup(function () {
                        if (isInteger($(this).val()) && $(this).val() < 100 && $(this).val() > 0) {
                            console.log(lPrice);
                            $('.lPrice').text(formatCurrency(convertToNumber(lPrice) - ((Number($(this).val()) * convertToNumber(lPrice)) / 100)));
                        } else {
                            $(this).val('');
                            $('.lPrice').html(formatCurrency(lPrice));
                        }
                    });

                    $('.disc_val').keyup(function () {
                        if (isInteger($(this).val()) && $(this).val() < convertToNumber(lPrice) && $(this).val() > 0) {
                            console.log(lPrice);
                            $('.lPrice').text(formatCurrency(convertToNumber(lPrice) - Number($(this).val())));
                        } else {
                            $(this).val('');
                            $('.lPrice').html(formatCurrency(lPrice));
                        }
                    });

                    $('input[name="type"]').change(function () {
                        console.log($(this).val());
                        if ($(this).val() == 'Percentage') {
                            $('.per').toggle();
                            $('.val').toggle();
                        } else {
                            $('.val').toggle();
                            $('.per').toggle();
                        }
                        $('.lPrice').html(formatCurrency(lPrice));
                        $('.disc_val').val('');
                        $('.disc_per').val('');
                    });


                    $('.saveEdit').click(function () {
                        if ($('.disc_val').val() > 0 || $('.disc_per').val() > 0) {
                            var rowCount = 0;
                            $('#datatable').children('tbody').children('tr').each(function () {
                                ++rowCount;
                            });
                            var arrayIndex = $('.index').val();
                            var table = $('.datatable').DataTable();
                            var index = 0;
                            if (rowCount == 1 && rowCount < arrayIndex) {
                                index = (arrayIndex - rowCount) - 1;
                            } else {
                                index = eval(arrayIndex) - 1;
                            }
                            var data = table.row(index).data();

                            //console.log($('.disc_val').val() > 0);
                            var line_discount = $('.disc_val').val() > 0 ? formatCurrency($('.disc_val').val()) : formatCurrency((Number($('.disc_per').val()) * convertToNumber(lPrice)) / 100);

                            data[4] = line_discount;
                            data[5] = $('.lPrice').text();

                            table.row(index).data(data).draw();

                            lineTot = 0, discount = 0;
                            $('#datatable').children('tbody').children('tr').each(function () {
                                lineTot += convertToNumber($(this).children('td').eq(5).text());
                            });

                            window.App.invoiceDetail[eval(arrayIndex) - 1].discount = convertToNumber(line_discount);

                            addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                            $('.overviewItem').append(tpl_overview.render({
                                text: "TOTAL DISC",
                                value: "RS " + formatCurrency(discount),
                                type: 'disc'
                            }));

                            $('.overviewItem').append(tpl_overview.render({
                                text: "GRAND TOTAL",
                                value: "RS " + formatCurrency(lineTot - discount),
                                type: 'g_tot'
                            }));
                            $(".fade").modal('hide');
                        } else {
                            showToast('info', 'information', 'can\'t add zero discount..');
                        }
                    });
                }

            });


            $('#max').html('<strong>Max Percentage : ' + window.App.maxFreeissue + '%</strong>');

            $('#lock').on('click', function () {
                toggleLock();
            });

            $('input[name="percentage"]').keyup(function (e) {
                if (e.keyCode == 13) {
                    toggleLock();
                }
            });

            $('input[name="free_qty"]').keyup(function (e) {
                var product = $('select[name="free_product"]');
                var productVal = product.val();
                var productText = $('select[name="free_product"] option[value="' + productVal + '"]').text();
                productText = productText.split(' - ');
                var lPrice = productText[(productText.length - 2)];
                var lSQty = productText[(productText.length - 1)];
                console.log(productText);
                if (lSQty >= Number($(this).val())) {
                    if (lSQty > 0) {
                        if (isInteger($(this).val())) {
                            //$('.lPrice').text(formatCurrency(Number($(this).val()) * lPrice));
                        } else {
                            $(this).val('');
                        }
                    } else {
                        $(this).val('');
                        showToast('info', 'information', 'can\'t add zero quantity..');
                    }
                } else {
                    $(this).val('');
                    showToast('info', 'information', 'can\'t add more than stock quantity..');
                }
                if (e.keyCode == 13) {
                    $('#freeAddBtn').click();
                }
            });


            $('#freeAddBtn').on('click', function () {
                $('.refreshFree').addClass('panel-refreshing');
                var t = $('.datatable2').DataTable();
                var product = $('select[name="free_product"]');
                var qty = $('input[name="free_qty"]');
                var lineTot = 0;
                var is_added = $('#ava').text().split(' : ')[1];
                var access = $('#acc').text().split(' : ')[1];

                if (product.val() !== null) {
                    if (qty.val() > 0) {
                        var productVal = product.val();
                        var productText = $('select[name="free_product"] option[value="' + productVal + '"]').text();
                        productVal = productVal.split('-');
                        productText = productText.split(' - ');
                        var lPrice = productText[(productText.length - 2)];
                        var lSQty = productText[(productText.length - 1)];
                        var total = Number(qty.val()) * castToNumber(lPrice);

                        $('#datatable2').children('tbody').children('tr').each(function () {
                            lineTot += convertToNumber($(this).children('td').eq(5).text());
                        });


                        if (convertToNumber(is_added) >= (Number(lineTot) + total)) {
                            var free_issue = {
                                gorup_id: 0,
                                product_id: productVal[0],
                                mrp: productVal[1],
                                qty: qty.val(),
                                product_name: productText[0] + ' - ' + productText[1]
                            };
                            window.App.invoiceFreeIssue.push(free_issue);

                            t.row.add([
                                window.App.openFreeCounter,
                                productText[0] + ' - ' + productText[1],
                                lSQty,//5 correct
                                formatCurrency(castToNumber(lPrice)),//4 correct
                                qty.val(),
                                formatCurrency(total),
                                '<a href="#"  class="delRow ' + window.App.openFreeCounter + '" data-toggle="tooltip" data-placement="top" title="Delete Row" ><i class="fa fa-trash"></i></a>'
                            ]).draw(false);
                            t.order([0, 'desc']).draw();
                            window.App.openFreeCounter++;

                            window.App.freeguarded.push(productVal[0]);
                            $('#acc').html('<strong>Access : ' + formatCurrency(convertToNumber(access) - total) + '</strong>');
                            qty.val('');
                        } else {
                            qty.val('');
                            showToast('info', 'information', 'Free Issue Limit Over...');
                        }
                    } else {
                        qty.val('');
                        showToast('info', 'information', 'please enter valid quantity..');
                    }
                } else {
                    qty.val('');
                    showToast('info', 'information', 'Invalid Product..');
                }
                $('.refreshFree').removeClass('panel-refreshing');
                //selectBrandForFree();
            });

            $('#datatable2 tbody').on('click', 'tr td a', function () {
                var option = $(this).attr('class').split(' ')[0];

                $('.refresh').addClass('panel-refreshing');
                var t = $('.datatable2').DataTable();
                t.row($(this).parent('td').parent('tr'))
                    .remove()
                    .draw();

                var index = $(this).attr('class').split(' ')[1];
                delete window.App.invoiceFreeIssue[(index - 1)];
                delete window.App.freeguarded[(index - 1)];

                var lineTot = 0;
                $('#datatable2').children('tbody').children('tr').each(function () {
                    lineTot += convertToNumber($(this).children('td').eq(5).text());
                });

                var is_added = $('#ava').text().split(' : ')[1];
                $('#acc').html('<strong>Access : ' + formatCurrency(convertToNumber(is_added) - lineTot) + '</strong>');
                selectBrandForFree();
            });

            var e_product = $('select[name="e_product"]');
            $('#e_product').on('change', function () {
                if ($(this).val() != 85) {
                    console.log($(this).val());
                    $('select[name="editor"]').prop('disabled', true).trigger("chosen:updated");
                } else {
                    $('select[name="editor"]').prop('disabled', false).trigger("chosen:updated");
                }
            });


        });


        function selectBrand() {
            var product = $('select[name="product"]');
            var outlet = $('input[name="outlet_id"]');
            //$('.refresh').removeClass('panel-refreshing');
            $.get(
                '{{url("invoice/getProductByBrand")}}',
                {'outlet': outlet.val(), 'expectProduct': window.App.guarded.toString()},
                function (data) {
                    var strProduct = [];

                    for (var i = 0; i < data.length; i++) {

                        if (data[i].mrp != null) {
                            var currentProduct = '<option value="' + data[i].id + '">' + data[i].product_name + ' - ' + data[i].sizes.sizes + ' - ' + data[i].mrp.mrp + 'Rs' + '</option>';
                            if (strProduct.indexOf(currentProduct) < 0) {
                                strProduct.push(currentProduct);
                            }
                        }
                    }

                    //category
                    product.html(strProduct.join());
                    product.trigger("chosen:updated");

                    $('.refresh').removeClass('panel-refreshing');

                });

        }


        function selectCategory() {
            var range = $('select[name="range"]');
            var brand = $('select[name="brand"]');
            var category = $('select[name="category"]');
            var product = $('select[name="product"]');
            var outlet = $('input[name="outlet_id"]');
            $.get(
                '{{url("invoice/getProductByCategory")}}',
                {
                    'category': category.val(),
                    'outlet': outlet.val(),
                    'brand': brand.val(),
                    'expectProduct': window.App.guarded.toString()
                },
                function (data) {
                    var strRange = [];
                    var strProduct = [];
                    var currentBrand = '<option value="0">All</option>';
                    if (strRange.indexOf(currentBrand) < 0) {
                        strRange.push(currentBrand);
                    }
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].range != undefined) {
                            var currentBrand = '<option value="' + data[i].range.id + '">' + data[i].range.range_name + '</option>';
                            if (strRange.indexOf(currentBrand) < 0) {
                                strRange.push(currentBrand);
                            }
                        }
                        var stock_qty = 0;
                        var batch = 0;
                        if (data[i].stock.length > 0) {
                            for (var j = 0; j < data[i].stock.length; j++) {
                                stock_qty += data[i].stock[j].qty !== undefined ? Number(data[i].stock[j].qty) : 0;
                                batch = data[i].stock[j].batch_id;
                            }
                            if (data[i].custom_price_book.length > 0) {
                                var currentProduct = '<option value="' + data[i].id + "-" + data[i].custom_price_book[0].id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + data[i].custom_price_book[0].price + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            } else {
                                var currentProduct = '<option value="' + data[i].id + "-" + data[i].stranded_price_book[0].id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + data[i].stranded_price_book[0].price + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            }
                        }

                    }
                    //range
                    range.html(strRange.join());
                    range.trigger("chosen:updated");

                    //category
                    product.html(strProduct.join());
                    product.trigger("chosen:updated");

                    $('.refresh').removeClass('panel-refreshing');
                });
        }


        function selectRange() {
            var category = $('select[name="category"]');
            var brand = $('select[name="brand"]');
            var range = $('select[name="range"]');
            var product = $('select[name="product"]');
            var outlet = $('input[name="outlet_id"]');
            $.get(
                '{{url("invoice/getProductByRange")}}',
                {
                    brand: brand.val(),
                    'range': range.val(),
                    'outlet': outlet.val(),
                    'category': category.val(),
                    'expectProduct': window.App.guarded.toString()
                },
                function (data) {
                    var strProduct = [];
                    for (var i = 0; i < data.length; i++) {
                        var stock_qty = 0;
                        var batch = 0;
                        if (data[i].stock.length > 0) {
                            for (var j = 0; j < data[i].stock.length; j++) {
                                stock_qty += data[i].stock[j].qty !== undefined ? Number(data[i].stock[j].qty) : 0;
                                batch = data[i].stock[j].batch_id;
                            }
                            if (data[i].custom_price_book.length > 0) {
                                var currentProduct = '<option value="' + data[i].id + "-" + data[i].custom_price_book[0].id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + data[i].custom_price_book[0].price + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            } else {
                                var currentProduct = '<option value="' + data[i].id + "-" + data[i].stranded_price_book[0].id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + data[i].stranded_price_book[0].price + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            }
                        }

                    }
                    product.html(strProduct.join());
                    product.trigger("chosen:updated");

                    $('.refresh').removeClass('panel-refreshing');
                });
        }


        function castToNumber(str) {
            str = str.replace("Rs", "");
            return isNaN == Number(str) ? 0 : Number(str);
        }

        function formatCurrency(str) {
            var n = str.toString().split(".");
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (n.length > 1) {
                if (Number(n[1]) == 0) {
                    return n[0] + '.00';
                } else {
                    n[1] = n[1] < 10 ? n[1] + '0' : n[1] < 100 ? n[1] : n[1].substring(0, 2);
                    return n[0] + '.' + n[1];
                }
            } else {
                return n[0] + '.00';
            }

        }

        function convertToNumber(currency) {
            return Number(currency.replace(/[^0-9\.]+/g, ""));
        }

        function calcFreeIssue() {
            $('.panel').addClass('panel-refreshing');
            var tpl_freeIssue = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_free_issue')}}'});

            var brand = $('select[name="brand"]');
            var outlet_id = $('input[name="outlet_id"]');
            var outlet_name = $('input[name="outlet_name"]');
            var outlet_address = $('input[name="outlet_address"]');
            window.App.invoice = {
                rep_id: 0,
                location_id: outlet_id.val(),
                loading_id: 0,
                brand_id: brand.val(),
                remark: "",
                payment_status: 0,
                manual_id: 0,
                device: 1,
                sales_order_id: 0,
                total: 0,
                created_date: new Date(),
                lat: 0,
                lon: 0,
                region_id: 0,
                area_id: 0,
                print_status: 0,
                delivery_status: 0,
                outlet: outlet_name.val(),
                outlet_address: outlet_address.val(),
                rep: 0
            };


            $.post('{{url("sales-order/getFreeIssue")}}',
                {
                    details: window.App.invoiceDetail,
                    outletId: window.App.invoice.location_id
                }, function (data) {
                    if (data.length === 0) {
                        window.App.invoiceFreeIssue = [];
                        $('button[name="next_2"]').click();
                    } else {
                        window.App.invoiceFreeIssue = [];
                        $('.freeIssue').html(tpl_freeIssue.render({
                            data: data
                        }));
                        $('.calc').keyup(function (e) {
                            if (isNaN($(this).val()) || $(this).val() <= 0) {
                                showToast('info', 'information', 'Please input valid quantity...');
                                $(this).val('');
                            }
                            if (!isInteger($(this).val())) {
                                $(this).val('');
                            }
                            var index = $(this).siblings("input").val();
                            // console.log(available.text());
                            var typeval = 0
                            $(".calc_" + index).each(function () {
                                if (!isNaN($(this).val())) {
                                    typeval += Number($(this).val());
                                }
                            });
                            var totQty = data[index].rules.outQty;
                            if (typeval <= totQty) {
                                $('.available_' + index).html('<strong>Available : ' + (Number(totQty) - typeval) + '</strong>');
                            } else {
                                typeval -= Number($(this).val());
                                $('.available_' + index).html('<strong>Available : ' + (Number(totQty) - typeval) + '</strong>');
                                $(this).val('');
                                showToast('info', 'Information', 'Can\'t select more than total');
                            }

                        });
                    }
                    $('.panel').removeClass('panel-refreshing');
                });
        }

        function calcDiscount() {

            var str = $('#percentage_discount');

            str.focus();

            $('.panel').addClass('panel-refreshing');
            var tpl_discount = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_discount')}}'});
            var tpl_overview = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_overview')}}'});

            var lineTot = 0;
            $('#disc').html('<div class="row " style="text-align: left" id="disc"><h5 style="margin-top: 0;margin-bottom: 0;margin-left: 10px;font-weight: 500 !important;font-size: 15px !important;">RS 0.00</h5></div>');
            $('#datatable').children('tbody').children('tr').each(function () {
                lineTot += convertToNumber($(this).children('td').eq(5).text());
            });

            addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

            $('.overviewItem').append(tpl_overview.render({
                text: "TOTAL DISC",
                value: "RS " + formatCurrency(0),
                type: 'disc'
            }));

            $('.overviewItem').append(tpl_overview.render({
                text: "GRAND TOTAL",
                value: "RS " + formatCurrency(lineTot),
                type: 'g_tot'
            }));

            var data = [];

            window.App.invoiceDiscount = [];
            /* var index = $('.free td').siblings("input").val();
             var free = {};
             window.App.invoiceFreeIssue[0] = free;*/
            window.App.tempDisc = data;
            //console.log(window.App.tempDisc);
            $('.discount').html(tpl_discount.render({
                data: data,
                max_dis: window.App.maxDiscount
            }));
            $('.calc').change(function (e) {
                var index = $(this).siblings('input').val();
                var discount = addDiscount(window.App.tempDisc, index);
                if ($(this).prop('checked')) {

                    var str = $('#disc').text();
                    str = str.split(" ");
                    var oldDisc = convertToNumber(str[1]);
                    var lineTot = 0;
                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });

                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency((oldDisc + discount)),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot - (oldDisc + discount)),
                        type: 'g_tot'
                    }));
                } else {
                    var lineTot = 0;
                    var str = $('#disc').text();
                    str = str.split(" ");
                    var oldDisc = convertToNumber(str[1]);
                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency(oldDisc - discount),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency((lineTot - oldDisc) + discount),
                        type: 'g_tot'
                    }));
                }

            });
            //open discount
            $('#percentage_discount').keyup(function (e) {
                if (e.keyCode == 13) {
                    $('#disc_lock').click();
                }
            });

            $('#value_discount').keyup(function (e) {
                if (e.keyCode == 13) {
                    $('#disc_lock_2').click();
                }
            });

            $('#disc_lock').on('click', function () {
                //toggleDiscount();
                var lineTot = 0;
                var str = $('#percentage_discount');
                var str_2 = $('#value_discount');

                if (str.prop('disabled')) {
                    str.prop('disabled', false);
                    $('#disc_lock').html('<i class="fa fa-lock"></i>');

                    /*str_2.prop('disabled', true);
                     $('#disc_lock_2').html('<i class="fa fa-unlock-alt"></i>');*/


                    var disc = $('#disc').text();
                    disc = disc.split(" ");

                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency(0),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot),
                        type: 'g_tot'
                    }));

                    str.val('');

                } else {
                    str.prop('disabled', true);
                    $('#disc_lock').html('<i class="fa fa-unlock-alt"></i>');

                    /*str_2.prop('disabled', false);
                     $('#disc_lock_2').html('<i class="fa fa-lock"></i>');*/

                    var disc = $('#disc').text();
                    disc = disc.split(" ");

                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency((lineTot * Number(str.val())) / 100),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot - ((lineTot * Number(str.val())) / 100)),
                        type: 'g_tot'
                    }));

                    $('#disc_value').val(((lineTot * Number(str.val())) / 100));

                }
            });

            $('#disc_lock_2').on('click', function () {
                //toggleDiscount();
                var lineTot = 0;
                var str = $('#percentage_discount');
                var str_2 = $('#value_discount');


                if (str_2.prop('disabled')) {
                    //str.prop('disabled', false);
                    //$('#disc_lock').html('<i class="fa fa-lock"></i>');
                    str_2.prop('disabled', false);
                    $('#disc_lock_2').html('<i class="fa fa-lock"></i>');

                    var disc = $('#disc').text();
                    disc = disc.split(" ");
                    var oldDisc = convertToNumber(disc[1]);

                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency(0),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot),
                        type: 'g_tot'
                    }));

                    str_2.val('');

                } else {
                    //str.prop('disabled', true);
                    //$('#disc_lock').html('<i class="fa fa-unlock-alt"></i>');
                    str_2.prop('disabled', true);
                    $('#disc_lock_2').html('<i class="fa fa-unlock-alt"></i>');

                    var disc = $('#disc').text();
                    disc = disc.split(" ");
                    var oldDisc = convertToNumber(disc[1]);

                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    addInvoiceItem("GROSS TOTAL", lineTot, 'tot');

                    $('.overviewItem').append(tpl_overview.render({
                        text: "TOTAL DISC",
                        value: "RS " + formatCurrency(Number(str_2.val())),
                        type: 'disc'
                    }));

                    $('.overviewItem').append(tpl_overview.render({
                        text: "GRAND TOTAL",
                        value: "RS " + formatCurrency(lineTot - (Number(str_2.val()))),
                        type: 'g_tot'
                    }));

                    $('#disc_value_2').val(Number(str_2.val()));

                }
            });
            $('.panel').removeClass('panel-refreshing');


        }


        function printView() {
            $('.panel').addClass('panel-refreshing');
            var tpl_finalView = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_final_view')}}'});

            var outlet_id = $('input[name="outlet_id"]');
            var outlet_name = $('input[name="outlet_name"]');
            var outlet_address = $('input[name="outlet_address"]');
            //var job_no = $('input[name="job_no"]');
            // var couple_name = $('input[name="couple_name"]');

            window.App.invoice = {
                rep_id: 0,
                location_id: outlet_id.val(),
                loading_id: 0,
                brand_id: 0,
                remark: "",
                payment_status: 0,
                manual_id: 0,
                device: 1,
                sales_order_id: 0,
                total: 0,
                created_date: new Date(),
                lat: 0,
                lon: 0,
                region_id: 0,
                area_id: 0,
                print_status: 0,
                delivery_status: 0,
                outlet: outlet_name.val(),
                outlet_address: outlet_address.val(),
                rep: 0,
                job_no: '',
                couple_name: ''
            };

            var discount = {};
            var i = 0;

            /*$(".disc td").each(function () {

             var val = $(this).children('input').val();
             if (i % 4 == 0) {
             discount.group_id = val;
             } else if (i % 4 == 1) {
             discount.rule_id = val;
             } else if (i % 4 == 2) {
             discount.discount = val;
             } else {
             var check = $(this).children('div.checkbox');
             if(check.children('.calc').length > 0) {
             if (check.children('.calc')[0].checked) {
             var index = check.children("input.calcHide").val();
             discount.product = 0;
             discount.discount = addDiscount(window.App.tempDisc, index);
             window.App.invoiceDiscount.push(discount);
             discount = {};
             }
             }
             }
             i++;
             });*/

            var str = $('#percentage_discount');
            var str_2 = $('#value_discount');
            if (str.prop('disabled')) {
                discount.group_id = 0;
                discount.rule_id = 0;
                discount.product = 0;
                discount.discount = $('#disc_value').val();
                window.App.invoiceDiscount[0] = discount;
                discount = {};
            } else if (str_2.prop('disabled')) {
                discount.group_id = 0;
                discount.rule_id = 0;
                discount.product = 0;
                discount.discount = $('#disc_value_2').val();
                window.App.invoiceDiscount[0] = discount;
                discount = {};
            }


            $.get(
                '{{url("invoice/getMarketeer")}}',
                {custID: outlet_id.val()},
                function (data) {
                    var job_list = data.jobs;
                    $('.finalView').html(tpl_finalView.render({
                        data: window.App,
                        marketeerArr: data.marketeer,
                        job_list: data.jobs
                    }));

                    var job_combo = $('select[name="job_no"]');
                    job_combo.change(function (e) {
                        var job_no = $(this).val();
                        $('input[name="couple_name"]').val('');
                        console.log(job_no);
                        if (job_no.length > 2) {
                            $('.alt_job_div').hide();
                            for (var i = 0; i < job_list.length; i++) {
                                if (job_list[i].job_no == job_no) {
                                    $('input[name="couple_name"]').val(job_list[i].couple_name);
                                    break;
                                }
                            }
                        } else if (job_no == 0) {
                            $('.alt_job_div').show();
                        }
                    });


                    $('.panel').removeClass('panel-refreshing');

                    var lineTot = 0;
                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    lineTot += window.App.outstanding;
                    if (window.App.creditLimit < lineTot && window.App.creditLimit != 0) {
                        showToast('warning', 'Information', 'This outlet credit limit over...');
                    }
                    // console.log(window.App);
                    $('.panel').removeClass('panel-refreshing');
                });
            console.log(window.App.invoiceDiscount);

        }

        function showToast(type, heading, text) {
            $.toast({
                heading: heading,
                text: text,
                icon: type,
                loader: true,
                position: {top: '130px', bottom: '-', left: '-', right: '10px'},
                allowToastClose: false,
                showHideTransition: 'slide',// Change it to false to disable loader
                loaderBg: '#9EC600'  // To change the background
            });
        }

        function clearFreeArray() {
            window.App.invoiceFreeIssue = [];
            var table = $('#datatable2').DataTable();
            table.clear().draw();
            window.App.freeguarded = [];
            window.App.openFreeCounter = 1;
            selectBrandForFree();
        }

        function clearDiscArray() {
            $(".disc td").each(function () {
                var check = $(this).children('div.checkbox');
                check.children('input.type').prop('checked', false);
            });
            window.App.invoiceDiscount = [];
        }

        function save() {

            $('button[name="save"]').prop('disabled', true);
            $('.panel').addClass('panel-refreshing');

            var marketeer = $('#marketeer').val();
            //var job_no = $('#job_no').val();
            var couple_name = $('#couple_name').val();

            var comment = $('#comment').val();


            var invoice_date = $('#invoice_date').val();

            var marketeer_credit_limit = $('#marketeer_credit_limit').val();
            window.App.invoice.job_no = $('#job_no').val() == 0 ? $('#alt_job').val().length > 0 ? $('#alt_job').val() : '-' : $('#job_no').val();


            if (window.App.invoice.job_no != '-') {

                var invTot = 0;
                var lineTot = 0;
                $('#datatable').children('tbody').children('tr').each(function () {
                    lineTot += convertToNumber($(this).children('td').eq(5).text());
                    invTot += convertToNumber($(this).children('td').eq(5).text());
                });

                lineTot -= window.App.invoiceDiscount.length > 0 ? window.App.invoiceDiscount[0].discount : 0;
                lineTot += window.App.outstanding;

                // lineTot += window.App.outstanding;

                $.get('getData/' + $('input[name="outlet_id"]').val(), {
                    customer: $('input[name="outlet_id"]').val(),
                    amount: invTot,
                    job_no: window.App.invoice.job_no,
                    outstanding: lineTot,
                    credit_limit: marketeer_credit_limit,
                }, function (data) {
                    if (data.customer.is_credit_limit_block == 1) {

                        if (marketeer_credit_limit < lineTot || (window.App.creditLimit < lineTot && window.App.creditLimit != 0)) {
                            console.log(data.auth);
                            if (data.auth.status == 0 || data.auth.status == -1) {

                                $.confirm({
                                    title: 'Admin authentication!',
                                    content: '' +
                                    '<form action="" class="formName">' +
                                    '<div class="form-group">' +
                                    '<label>Marketeer or Customer credit limit has been exceed</label>' +
                                    '<input type="password" placeholder="Enter admin password" class="name form-control" required />' +
                                    '</div>' +
                                    '</form>',
                                    buttons: {
                                        formSubmit: {
                                            text: 'Submit',
                                            btnClass: 'btn-blue',
                                            action: function () {
                                                var name = this.$content.find('.name').val();
                                                if (!name) {
                                                    $.alert('provide a valid password');
                                                    return false;
                                                }
                                                $.get('{{url('invoice/admin_authentication')}}', {password: name}, function (data) {
                                                    if (data == 1) {
                                                        window.App.invoice.rep_id = marketeer;
                                                        //window.App.invoice.job_no = job_no;
                                                        window.App.invoice.couple_name = couple_name;
                                                        window.App.invoice.comment = comment;
                                                        window.App.invoice.created_date = invoice_date;
                                                        $('.refresh').addClass('panel-refreshing');
                                                        $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {
                                                            var url = "{{url('invoice')}}" + "/print?ids=" + data;
                                                            //window.open(url);
                                                            window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                                            window.location.href = '{{url("invoice/customerlist")}}';
                                                        });
                                                    } else {
                                                        $.alert('Invalid admin password..!');
                                                    }
                                                    return false;
                                                });
                                                // $.alert('Your name is ' + name);
                                            }
                                        },
                                        cancel: function () {
                                            $('button[name="save"]').prop('disabled', false);
                                            return true;
                                        },
                                    },
                                    onContentReady: function () {
                                        // bind to events
                                        var jc = this;
                                        this.$content.find('form').on('submit', function (e) {
                                            // if the user submits the form by pressing enter in the field.
                                            e.preventDefault();
                                            jc.$$formSubmit.trigger('click'); // reference the button and click it
                                        });
                                    }
                                });
                            } else if (data.auth.status == 1) {
                                window.App.invoice.rep_id = marketeer;
                                //window.App.invoice.job_no = job_no;
                                window.App.invoice.couple_name = couple_name;

                                window.App.invoice.comment = comment;
                                window.App.invoice.created_date = invoice_date;

                                $('.refresh').addClass('panel-refreshing');
                                $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {
                                    var url = "{{url('invoice')}}" + "/print?ids=" + data;
                                    //window.open(url);
                                    window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                    window.location.href = '{{url("invoice/customerlist")}}';
                                });
                            }
                            //showToast('warning', 'Information', 'Marketeer credit limit has been exseed...');
                        } else {
                            window.App.invoice.rep_id = marketeer;
                            //window.App.invoice.job_no = job_no;
                            window.App.invoice.couple_name = couple_name;

                            window.App.invoice.comment = comment;
                            window.App.invoice.created_date = invoice_date;

                            $('.refresh').addClass('panel-refreshing');
                            $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {
                                var url = "{{url('invoice')}}" + "/print?ids=" + data;
                                //window.open(url);
                                window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                window.location.href = '{{url("invoice/customerlist")}}';
                            });
                        }

                    } else {
                        window.App.invoice.rep_id = marketeer;
                        //window.App.invoice.job_no = job_no;
                        window.App.invoice.couple_name = couple_name;

                        window.App.invoice.comment = comment;
                        window.App.invoice.created_date = invoice_date;

                        $('.refresh').addClass('panel-refreshing');
                        $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {
                            var url = "{{url('invoice')}}" + "/print?ids=" + data;
                            //window.open(url);
                            window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                            window.location.href = '{{url("invoice/customerlist")}}';
                        });
                    }

                });


                // marketeer_credit_limit;
                /*var lineTot = 0;
                 $('#datatable').children('tbody').children('tr').each(function () {
                 lineTot += convertToNumber($(this).children('td').eq(5).text());
                 });*/

            } else {
                showToast('info', 'Information', 'Please Insert Job No...');
                $('button[name="save"]').prop('disabled', false);
            }
            $('.panel').removeClass('panel-refreshing');
        }

        function save_pay() {

            $('button[name="save"]').prop('disabled', true);
            $('.panel').addClass('panel-refreshing');

            var marketeer = $('#marketeer').val();
            //var job_no = $('#job_no').val();
            var couple_name = $('#couple_name').val();

            var comment = $('#comment').val();

            var invoice_date = $('#invoice_date').val();

            var marketeer_credit_limit = $('#marketeer_credit_limit').val();
            window.App.invoice.job_no = $('#job_no').val() == 0 ? $('#alt_job').val().length > 0 ? $('#alt_job').val() : '-' : $('#job_no').val();


            if (window.App.invoice.job_no != '-') {

                var lineTot = 0;
                $('#datatable').children('tbody').children('tr').each(function () {
                    lineTot += convertToNumber($(this).children('td').eq(5).text());
                });
                lineTot -= window.App.invoiceDiscount.length > 0 ? window.App.invoiceDiscount[0].discount : 0;
                lineTot += window.App.outstanding;

                $.get('getData/' + $('input[name="outlet_id"]').val(),
                    {
                        customer: $('input[name="outlet_id"]').val(),
                        amount: invTot,
                        job_no: window.App.invoice.job_no,
                        outstanding: lineTot,
                        credit_limit: marketeer_credit_limit,
                    },
                    function (data) {
                        if (data.customer.is_credit_limit_block == 1) {

                            if (marketeer_credit_limit < lineTot || (window.App.creditLimit < lineTot && window.App.creditLimit != 0)) {
                                if (data.auth.status == 0 || data.auth.status == -1) {
                                    $.confirm({
                                        title: 'Admin authentication!',
                                        content: '' +
                                        '<form action="" class="formName">' +
                                        '<div class="form-group">' +
                                        '<label>Marketeer or Customer credit limit has been exceed</label>' +
                                        '<input type="password" placeholder="Enter admin password" class="name form-control" required />' +
                                        '</div>' +
                                        '</form>',
                                        buttons: {
                                            formSubmit: {
                                                text: 'Submit',
                                                btnClass: 'btn-blue',
                                                action: function () {
                                                    var name = this.$content.find('.name').val();
                                                    if (!name) {
                                                        $.alert('provide a valid password');
                                                        return false;
                                                    }
                                                    $.get('{{url('invoice/admin_authentication')}}', {password: name}, function (data) {
                                                        if (data == 1) {
                                                            window.App.invoice.rep_id = marketeer;
                                                            //window.App.invoice.job_no = job_no;
                                                            window.App.invoice.couple_name = couple_name;
                                                            window.App.invoice.comment = comment;
                                                            window.App.invoice.created_date = invoice_date;
                                                            // window.App.invoice.job_no = $('#job_no').val() == 0 ? '-' : $('#job_no').val();

                                                            $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {
                                                                //var url = "{{url('invoice')}}" + "/print?ids=" + data;
                                                                //window.open(url);
                                                                //window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                                                var refresh_url = "{{url("payment")}}" + "/new/" + window.App.invoice.location_id + "/1";
                                                                window.location.href = refresh_url;
                                                            });
                                                            return false;
                                                        } else {
                                                            $.alert('Invalid admin password..!');
                                                        }
                                                    });
                                                    // $.alert('Your name is ' + name);
                                                }
                                            },
                                            cancel: function () {
                                                $('button[name="save"]').prop('disabled', false);
                                                return true;
                                            },
                                        },
                                        onContentReady: function () {
                                            // bind to events
                                            var jc = this;
                                            this.$content.find('form').on('submit', function (e) {
                                                // if the user submits the form by pressing enter in the field.
                                                e.preventDefault();
                                                jc.$$formSubmit.trigger('click'); // reference the button and click it
                                            });
                                        }
                                    });
                                } else if (data.auth.status == 1) {
                                    window.App.invoice.rep_id = marketeer;
                                    //window.App.invoice.job_no = job_no;
                                    window.App.invoice.couple_name = couple_name;

                                    window.App.invoice.comment = comment;
                                    window.App.invoice.created_date = invoice_date;

                                    $('.refresh').addClass('panel-refreshing');
                                    $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {
                                        var url = "{{url('invoice')}}" + "/print?ids=" + data;
                                        //window.open(url);
                                        window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                        window.location.href = '{{url("invoice/customerlist")}}';
                                    });
                                }
                                //showToast('warning', 'Information', 'Marketeer credit limit has been exseed...');
                            } else {

                                window.App.invoice.rep_id = marketeer;
                                //window.App.invoice.job_no = job_no;
                                window.App.invoice.couple_name = couple_name;
                                window.App.invoice.comment = comment;
                                window.App.invoice.created_date = invoice_date;
                                console.log(comment);
                                // window.App.invoice.job_no = $('#job_no').val() == 0 ? '-' : $('#job_no').val();
                                $('.refresh').addClass('panel-refreshing');
                                $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {

                                    //window.open(url);
                                    //window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                    var refresh_url = "{{url("payment")}}" + "/new/" + window.App.invoice.location_id + "/1";
                                    window.location.href = refresh_url;
                                });
                            }
                        } else {
                            window.App.invoice.rep_id = marketeer;
                            //window.App.invoice.job_no = job_no;
                            window.App.invoice.couple_name = couple_name;
                            window.App.invoice.comment = comment;
                            window.App.invoice.created_date = invoice_date;
                            console.log(comment);
                            // window.App.invoice.job_no = $('#job_no').val() == 0 ? '-' : $('#job_no').val();
                            $('.refresh').addClass('panel-refreshing');
                            $.post('{{url("invoice/new/add")}}', {invoice: window.App}, function (data) {

                                //window.open(url);
                                //window.open(url, "print", "location=1,status=1,scrollbars=1, width=800,height=800");
                                var refresh_url = "{{url("payment")}}" + "/new/" + window.App.invoice.location_id + "/1";
                                window.location.href = refresh_url;
                            });
                        }

                    });
            } else {
                showToast('info', 'Information', 'Please Insert Job No...');
                $('button[name="save"]').prop('disabled', false);
            }
            $('.panel').removeClass('panel-refreshing');
        }

        function addDiscount(data, index) {
            var value = 0, percentage = data[index].rules.discount;
            if (data[index].discount_rule_type_id == 1) {

                for (var i = 0; i < data[index].groupdetail.length; i++) {
                    var per = 0, is_avalable = false;
                    for (var j = 0; j < window.App.invoiceDetail.length; j++) {
                        if (window.App.invoiceDetail[j] != undefined) {
                            if (window.App.invoiceDetail[j].product_id == data[index].groupdetail[i].product_id) {
                                for (var k = 0; k < data[index].ruledetail.length; k++) {
                                    if (Number(data[index].ruledetail[k].value) <= Number(window.App.invoiceDetail[j].qty)) {
                                        per = data[index].ruledetail[k].discount;
                                        if (k == (data[index].ruledetail.length - 1)) {
                                            is_avalable = true;
                                        }
                                    } else {
                                        is_avalable = true;
                                        break;
                                    }
                                }
                                if (is_avalable) {
                                    var amount = ((window.App.invoiceDetail[j].qty * window.App.invoiceDetail[j].unit_price) / 100) * per;
                                    value += amount;
                                }
                            }
                        }
                    }
                }
                return value;
            } else if (data[index].discount_rule_type_id == 2) {
                for (var i = 0; i < data[index].groupdetail.length; i++) {
                    var per = 0, is_avalable = false;
                    for (var j = 0; j < window.App.invoiceDetail.length; j++) {
                        if (window.App.invoiceDetail[j] != undefined) {
                            if (window.App.invoiceDetail[j].product_id == data[index].groupdetail[i].product_id) {
                                for (var k = 0; k < data[index].ruledetail.length; k++) {
                                    if (Number(data[index].ruledetail[k].value) <= Number(window.App.invoiceDetail[j].qty * window.App.invoiceDetail[j].unit_price)) {
                                        per = data[index].ruledetail[k].discount;
                                        if (k == (data[index].ruledetail.length - 1)) {
                                            is_avalable = true;
                                        }
                                    } else {
                                        is_avalable = true;
                                        break;
                                    }
                                }
                                if (is_avalable) {
                                    var amount = ((window.App.invoiceDetail[j].qty * window.App.invoiceDetail[j].unit_price) / 100) * per;
                                    value += amount;
                                }
                            }
                        }
                    }
                }
                return value;
            }

            for (var i = 0; i < data[index].groupdetail.length; i++) {
                for (var j = 0; j < window.App.invoiceDetail.length; j++) {
                    if (window.App.invoiceDetail[j] != undefined) {
                        if (window.App.invoiceDetail[j].product_id == data[index].groupdetail[i].product_id) {
                            value += (window.App.invoiceDetail[j].qty * window.App.invoiceDetail[j].unit_price);
                        }
                    }
                }
            }
            return (value / 100) * percentage;
        }

        function addInvoiceItem(text, value, type) {
            var tpl_overview = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_overview')}}'});
            $('.overviewItem').html(tpl_overview.render({
                text: text,
                value: "RS " + formatCurrency(value),
                type: type
            }));
        }

        function changeBrand(id, name) {
            var brand = $('select[name="brand"]');
            var currentBrand = '<option value="' + id + '">' + name + '</option>';
            brand.html(currentBrand);
            brand.trigger("chosen:updated");
            selectCategory();
        }

        function validateNext() {
            var table = $('#datatable').DataTable();
            return table.data().length == 0 ? false : true;
        }

        function isReload() {
            sweetAlertConfirm('Are you sure?', 'Will be lost your data..?', 'info', function () {
                window.location.reload();
            });
        }

        function isEmpty() {
            sweetAlertConfirm('Are you sure?', 'Will be lost your data..?', 'info', function () {
                $('.refresh').addClass('panel-refreshing');
                var lineStock = 0;
                $('#datatable').children('tbody').children('tr').each(function () {
                    lineStock = convertToNumber($(this).children('td').eq(0).text());
                });
                if (lineStock > 0) {
                    window.location.reload();
                }
                selectBrand();
                $('.refresh').removeClass('panel-refreshing');
            });
        }

        function focusInput() {
            $('input[name="qty"]').focus();
        }

        function removeItemList() {
            for (var i = 0; i < window.App.guarded.length; i++) {
                $('select[name="product"] option[value="' + window.App.guarded[i] + '"]').remove();
            }
            $('select[name="product"]').trigger('chosen:updated');
        }

        function isInteger(x) {
            return x % 1 === 0;
        }

        //for open free issue
        function selectBrandForFree() {
            var brand = $('select[name="brand"]');
            var range = $('select[name="free_range"]');
            var category = $('select[name="free_category"]');
            var product = $('select[name="free_product"]');
            var outlet = $('input[name="outlet_id"]');
            $('.refresh').removeClass('panel-refreshing');
            $.get(
                '{{url("invoice/getProductByBrand")}}',
                {'brand': brand.val(), 'outlet': outlet.val(), 'expectProduct': window.App.freeguarded.toString()},
                function (data) {
                    var strRange = [];
                    var strCategory = [];
                    var strProduct = [];
                    var currentBrand = '<option value="0">All</option>';
                    var currentCategory = '<option value="0">All</option>';
                    if (strRange.indexOf(currentBrand) < 0) {
                        strRange.push(currentBrand);
                    }
                    if (strCategory.indexOf(currentCategory) < 0) {
                        strCategory.push(currentCategory);
                    }
                    for (var i = 0; i < data.length; i++) {


                        var currentBrand = '<option value="' + data[i].range.id + '">' + data[i].range.range_name + '</option>';
                        var currentCategory = '<option value="' + data[i].category.id + '">' + data[i].category.category_name + '</option>';
                        var stock_qty = 0;
                        var batch = 0;


                        var invQty = 0;
                        for (var j = 0; j < window.App.invoiceDetail.length; j++) {
                            if (window.App.invoiceDetail[j] !== undefined) {
                                if (data[i].id == window.App.invoiceDetail[j].product_id) {
                                    invQty = window.App.invoiceDetail[j].qty;
                                    break;
                                }
                            }
                        }

                        var mrp_id = 0, mrp = 0;
                        if (data[i].stock.length > 0) {

                            for (var j = 0; j < data[i].stock.length; j++) {
                                stock_qty += data[i].stock[j].qty !== undefined ? Number(data[i].stock[j].qty) : 0;
                                batch = data[i].stock[j].batch_id;
                                mrp_id = data[i].stock[j].mrp_id;
                                mrp = data[i].stock[j].mrp;
                            }
                            stock_qty -= invQty;


                            if (mrp_id > 0) {
                                var currentProduct = '<option value="' + data[i].id + "-" + mrp_id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + mrp + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            }

                        }

                        if (strRange.indexOf(currentBrand) < 0) {
                            strRange.push(currentBrand);
                        }

                        if (strCategory.indexOf(currentCategory) < 0) {
                            strCategory.push(currentCategory);
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
            brand.prop('editable', false);

        }

        function selectCategoryForFree() {
            var range = $('select[name="free_range"]');
            var brand = $('select[name="brand"]');
            var category = $('select[name="free_category"]');
            var product = $('select[name="free_product"]');
            var outlet = $('input[name="outlet_id"]');
            $.get(
                '{{url("invoice/getProductByCategory")}}',
                {
                    'category': category.val(),
                    'outlet': outlet.val(),
                    'brand': brand.val(),
                    'expectProduct': window.App.freeguarded.toString()
                },
                function (data) {
                    var strRange = [];
                    var strProduct = [];
                    var currentBrand = '<option value="0">All</option>';
                    if (strRange.indexOf(currentBrand) < 0) {
                        strRange.push(currentBrand);
                    }
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].range != undefined) {
                            var currentBrand = '<option value="' + data[i].range.id + '">' + data[i].range.range_name + '</option>';
                            if (strRange.indexOf(currentBrand) < 0) {
                                strRange.push(currentBrand);
                            }
                        }
                        var invQty = 0;
                        for (var j = 0; j < window.App.invoiceDetail.length; j++) {
                            if (data[i].id == window.App.invoiceDetail[j].product_id) {
                                invQty = window.App.invoiceDetail[j].qty;
                                break;
                            }
                        }

                        var stock_qty = 0;
                        var batch = 0;
                        var mrp_id = 0, mrp = 0;
                        if (data[i].stock.length > 0) {

                            for (var j = 0; j < data[i].stock.length; j++) {
                                stock_qty += data[i].stock[j].qty !== undefined ? Number(data[i].stock[j].qty) : 0;
                                batch = data[i].stock[j].batch_id;
                                mrp_id = data[i].stock[j].mrp_id;
                                mrp = data[i].stock[j].mrp;
                            }
                            stock_qty -= invQty;


                            if (mrp_id > 0) {
                                var currentProduct = '<option value="' + data[i].id + "-" + mrp_id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + mrp + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            }

                        }

                    }
                    //range
                    range.html(strRange.join());
                    range.trigger("chosen:updated");

                    //category
                    product.html(strProduct.join());
                    product.trigger("chosen:updated");

                    $('.refresh').removeClass('panel-refreshing');
                });
        }

        function selectRangeForFreeIssue() {
            var category = $('select[name="free_category"]');
            var brand = $('select[name="brand"]');
            var range = $('select[name="free_range"]');
            var product = $('select[name="free_product"]');
            var outlet = $('input[name="outlet_id"]');
            $.get(
                '{{url("invoice/getProductByRange")}}',
                {
                    brand: brand.val(),
                    'range': range.val(),
                    'outlet': outlet.val(),
                    'category': category.val(),
                    'expectProduct': window.App.freeguarded.toString()
                },
                function (data) {
                    var strProduct = [];
                    for (var i = 0; i < data.length; i++) {

                        var invQty = 0;
                        for (var j = 0; j < window.App.invoiceDetail.length; j++) {
                            if (data[i].id == window.App.invoiceDetail[j].product_id) {
                                invQty = window.App.invoiceDetail[j].qty;
                                break;
                            }
                        }

                        var stock_qty = 0;
                        var batch = 0;
                        var mrp_id = 0, mrp = 0;
                        if (data[i].stock.length > 0) {

                            for (var j = 0; j < data[i].stock.length; j++) {
                                stock_qty += data[i].stock[j].qty !== undefined ? Number(data[i].stock[j].qty) : 0;
                                batch = data[i].stock[j].batch_id;
                                mrp_id = data[i].stock[j].mrp_id;
                                mrp = data[i].stock[j].mrp;
                            }
                            stock_qty -= invQty;


                            if (mrp_id > 0) {
                                var currentProduct = '<option value="' + data[i].id + "-" + mrp_id + "-" + batch + '">' + data[i].short_code + ' - ' + data[i].product_name + ' - ' + mrp + 'Rs - ' + stock_qty + '</option>';
                                if (strProduct.indexOf(currentProduct) < 0) {
                                    strProduct.push(currentProduct);
                                }
                            }

                        }

                    }
                    product.html(strProduct.join());
                    product.trigger("chosen:updated");

                    $('.refresh').removeClass('panel-refreshing');
                });
        }

        function toggleLock() {
            var lock = $('#lock');
            var percentage = $('input[name="percentage"]');
            $('.panel').addClass('panel-refreshing');

            if (window.App.maxFreeissue > 0 && window.App.maxFreeissue >= Number(percentage.val())) {
                var is_added = $('#ava').text().split(' : ')[1];
                var lineTot = 0;
                console.log(convertToNumber(is_added));
                if (convertToNumber(is_added) > 0) {
                    $('#ava').html('<strong>Available : 0.00</strong>');
                    $('#acc').html('<strong>Access : 0.00</strong>');
                    percentage.prop('disabled', false);
                    $('#freeAddBtn').prop('disabled', true);
                    $('#free_qty').prop('disabled', true);
                    lock.html('<i class="fa fa-lock"></i>');
                    var table = $('#datatable2').DataTable();
                    table.clear().draw();
                    window.App.freeguarded = [];
                    window.App.openFreeCounter = 1;
                    clearFreeArray();
                } else {
                    $('#datatable').children('tbody').children('tr').each(function () {
                        lineTot += convertToNumber($(this).children('td').eq(5).text());
                    });
                    var free = Number(lineTot) / 100 * Number(percentage.val());
                    $('#ava').html('<strong>Available : ' + formatCurrency(free) + '</strong>');
                    $('#acc').html('<strong>Access : ' + formatCurrency(free) + '</strong>');
                    percentage.prop('disabled', true);
                    $('#freeAddBtn').prop('disabled', false);
                    $('#free_qty').prop('disabled', false);
                    lock.html('<i class="fa fa-unlock-alt"></i>');
                    selectBrandForFree();
                }
            } else {
                showToast('info', 'Information', 'Can\'t Grater than open free issue limit...');
            }
            $('.panel').removeClass('panel-refreshing');

        }

        function calcManualFreeIssue() {
            window.App.invoiceFreeIssue = [];
            var lineTot = 0;
            var lock = $('#lock');
            var percentage = $('input[name="percentage"]');
            $('#datatable').children('tbody').children('tr').each(function () {
                lineTot += convertToNumber($(this).children('td').eq(5).text());
            });
            var free = Number(lineTot) / 100 * Number(percentage.val());
            $('#ava').html('<strong>Available : ' + formatCurrency(free) + '</strong>');
            $('#acc').html('<strong>Access : ' + formatCurrency(free) + '</strong>');
            var i = 0;
            var free = {};
            $(".free td").each(function () {
                var val = $(this).children('input').val();
                if (i % 3 == 0) {
                    free.gorup_id = val;
                } else if (i % 3 == 1) {
                    val = val.split('-');
                    free.product_id = val[0];
                    free.mrp = 0;
                    free.product_name = val[2] + ' - ' + val[1];
                } else {
                    if (val > 0) {
                        free.qty = val;
                        window.App.invoiceFreeIssue.push(free);
                    }
                    free = {};
                }
                i++;
            });
            //$('button[name="next_2_1"]').click();
        }

        function validValue(feild, event) {
            var regex = /^\d+(?:\.\d{2})$/;
            if (feild.value.length == 0) {
                return false || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57);
            }
            if (regex.test(feild.value)) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
            }
            if (feild.value.length == 10) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40;
            }
            if ((!isInt(feild.value) && (!isFloat(feild.value)))) {
                return false || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || (event.keyCode <= 105 && event.keyCode >= 96) || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode == 110 || event.keyCode == 190;
            }
            return true;
        }

        function isInt(n) {
            return Number(n) === n && n % 1 === 0;
        }

        function isFloat(n) {
            return Number(n) === n && n % 1 !== 0;
        }

        //open discount
        function toggleDiscount() {
            $('.panel').addClass('panel-refreshing');
            var tpl_overview = new EJS({url: '{{url('core/app/Packages/application/sales-order-management/views/template/tpl_overview')}}'});
            var lineTot = 0;
            var str = $('#percentage_discount').text();
            var per = document.getElementById("percentage_discount");
            if (per.isDisabled) {
                $('#percentage_discount').prop('disabled', false);
                $('#disc_lock').html('<i class="fa fa-unlock-alt"></i>');
            }
            $('.panel').removeClass('panel-refreshing');
        }
    </script>
@stop