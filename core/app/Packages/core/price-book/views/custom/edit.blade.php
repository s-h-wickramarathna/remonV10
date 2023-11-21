@extends('layouts.master') @section('title','Add Custom Price Book')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/file/bootstrap-fileinput-master/css/fileinput.css')}}"media="all" />
    <style type="text/css">
        .panel.panel-bordered {
            border: 1px solid #ccc;
        }

        .btn-primary {
            color: white;
            background-color: #C51C6A;
            border-color: #C51C6A;
        }

        .chosen-container{
            font-family: 'FontAwesome', 'Open Sans',sans-serif;
        }
    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Custom Price Book Management</a>
        </li>
        <li class="active">Add Custom Price Book</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Add Price Book</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <input type="hidden" name="user_id" id="user_id" value="{{$details->id}}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">SBU</label>
                            <div class="col-sm-10">
                                {!! Form::select('sbu', $sbu, '1',['class'=>'chosen','style'=>'width:100%;','id'=>'sub_id','data-placeholder'=>'']) !!}
                                @if($errors->has('sbu'))
                                    <label id="label-error" class="error" for="label">{{$errors->first('sbu')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Price Book For</label>
                            <div class="col-sm-10">
                                {!! Form::select('book_category', $sale_party, $details->category,['class'=>'chosen','style'=>'width:100%;','id'=>'book_category','required','data-placeholder'=>'']) !!}
                                @if($errors->has('book_category'))
                                    <label id="label-error" class="error" for="label">{{$errors->first('book_category')}}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Users</label>
                            <div class="col-sm-6" id="user_div" name="user_div">
                                @if($errors->has('user[]'))
                                    {!! Form::select('user[]',[], null,['class'=>'error', 'multiple','id'=>'user','style'=>'width:100%;','required']) !!}
                                    <label id="label-error" class="error" for="label">{{$errors->first('user[]')}}</label>
                                @else
                                    {!! Form::select('user[]',[], Input::old('user[]'),['multiple'=>'multiple','id'=>'user','style'=>'width:100%;','required']) !!}
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <a href='#' id='select-all-user' style="font-size: 16px;color: green">Select All</a>
                                </div>
                                <div class="row">
                                    <a href='#' id='deselect-all-user' style="font-size: 16px;color: red">Deselect All</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Price Book</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @if($errors->has('name')) error @endif"
                                       name="name" id="name" placeholder="Price Book Name" required value="{{$details->name}}">
                                @if($errors->has('name'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('name')}}</label>
                                @endif
                            </div>
                            <label class="col-sm-2 control-label required">Effective Date</label>
                            <div class="col-sm-4">
                                <div class="input-daterange" id="datepicker">
                                    <input type="text" class="input-sm form-control @if($errors->has('effective_date')) error @endif required" name="effective_date" id="effective_date" placeholder="Effective Date"  value="{{$details->details[0]->effective_date}}">
                                    @if($errors->has('effective_date'))
                                        <label id="effective_date_label-error" class="error" for="label">{{$errors->first('effective_date','The effective date field is required.')}}</label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div id="product_div" name="product_div">
                            <div class="form-group">
                                <label class="col-sm-2 control-label required">Product Category</label>
                                <div class="col-sm-10">
                                    @if($errors->has('category'))
                                        {!! Form::select('category',$category, Input::old('category'),['class'=>'chosen error','style'=>'width:100%;','required','data-placeholder'=>'Choose Category','id'=>'category']) !!}
                                        <label id="label-error" class="error"
                                               for="label">{{$errors->first('category')}}</label>
                                    @else
                                        {!! Form::select('category',$category, Input::old('category'),['class'=>'chosen','style'=>'width:100%;','required','data-placeholder'=>'Choose Category','id'=>'category']) !!}
                                    @endif

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required">Product</label>
                                <div class="col-sm-6" id="pro_div" name="pro_div">
                                    @if($errors->has('product[]'))
                                        {!! Form::select('product[]',[], null,['class'=>'error', 'multiple','id'=>'product','style'=>'width:100%;','required']) !!}
                                        <label id="label-error" class="error" for="label">{{$errors->first('product[]')}}</label>
                                    @else
                                        {!! Form::select('product[]',[], Input::old('product[]'),['multiple'=>'multiple','id'=>'product','style'=>'width:100%;','required']) !!}
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <a href='#' id='select-all' style="font-size: 16px;color: green">Select All</a>
                                    </div>
                                    <div class="row">
                                        <a href='#' id='deselect-all' style="font-size: 16px;color: red">Deselect All</a>
                                    </div>
                                    <div class="row" style="margin-top: 36%">
                                        <div class="pull-left">
                                            <button type="button" class="btn btn-warning" onclick="get_product()"><i class="fa fa-plus"></i> Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required">Product Details</label>
                                <div class="col-sm-10">
                                    <table class="table table-bordered bordered table-striped table-condensed" id="edit_table">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" width="4%">#</th>
                                            <th rowspan="2" class="text-center" style="font-weight:normal;">Short Code</th>
                                            <th rowspan="2" class="text-center" style="font-weight:normal;">Name</th>
                                            <th rowspan="2" class="text-center" style="font-weight:normal;">Description</th>
                                            <th rowspan="2" class="text-center" style="font-weight:normal;">Category</th>
                                            <th rowspan="2" class="text-center" style="font-weight:normal;">MRP</th>
                                            <th rowspan="2" class="text-center" style="font-weight:normal;">Selling Price</th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody" name="tbody">
                                        <?php  $x=1; ?>
                                        @foreach( $details->details as $val)

                                        <tr>
                                            <td>{{$x}}</td>
                                            <input type="hidden" id="detail_id_{{$x}}" name="detail_id_{{$x}}" value="{{$val->id}}"/>
                                            <td>{{$val->product->short_code}}</td>
                                            <input type="hidden" id="product_id_{{$x}}" name="product_id_{{$x}}" value="{{$val->product->id}}"/>
                                            <td>{{$val->product->product_name}}</td>
                                            <td>{{$val->product->description}}</td>
                                            <td>{{$val->getProductCategory->category_name}}</td>
                                            <input type="hidden" id="category_id_{{$x}}" name="category_id_{{$x}}" value="{{$val->getProductCategory->id}}"/>
                                            <td>{{$val->product->mrp->mrp}}</td>
                                            <td><input type="number" min="0" class="form-control" width="100%" id="product_price_{{$x}}" name="product_price_{{$x}}" value="{{$val->price}}"></td>
                                        </tr>
                                        <?php $x++; ?>
                                        @endforeach
                                        </tbody>
                                        <input type="hidden" id="row_count" name="row_count" value="{{$x}}">
                                    </table>

                                </div>
                            </div>
                        </div>


                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" onclick="save_data()"><i class="fa fa-floppy-o"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/file/bootstrap-fileinput-master/js/fileinput.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#sub_id').prop('disabled', true).trigger("chosen:updated");
            $('#book_category').prop('disabled', true).trigger("chosen:updated");
            $('.form-validation').validate();
            $('#product').multiSelect();

            $('#user').multiSelect();

            $('#select-all-user').click(function(){
                $('#user').multiSelect('select_all');
                return false;
            });
            $('#deselect-all-user').click(function(){
                $('#user').multiSelect('deselect_all');
                return false;
            });

            $('#select-all').click(function(){
                $('#product').multiSelect('select_all');
                return false;
            });
            $('#deselect-all').click(function(){
                $('#product').multiSelect('deselect_all');
                return false;
            });

            $("#product_div").show();

            $("#excel_radio").attr('checked','checked');

            load_product();
            load_user();

            $('#sbu').change(function(){
                load_category();
            });


            $('#book_category').change(function(){
                load_user();
            });

            $('#category').change(function(){
                load_product();
            });

            $('#effective_date').change(function(){
                if($('#effective_date').val()==""){
                    $('#effective_date').removeClass("success");
                    $('#effective_date').addClass("error");
                }else{
                    $('#effective_date').removeClass("error");
                    $('#effective_date').addClass("success");
                }
            });

            $('#name').change(function(){
                if($('#name').val()==""){
                    $('#name').removeClass("success");
                    $('#name').addClass("error");
                }else{
                    $('#name').removeClass("error");
                    $('#name').addClass("success");
                }
            });

            $('.input-daterange').datepicker({
                format: "yyyy-mm-dd",
                startDate: '0d',
                daysOfWeekHighlighted: "0,6",
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                toggleActive: true
            });

        });

        function load_user(){
            $('.panel').addClass('panel-refreshing');
            $("#user_div").html("");
            $("#user_div").append('<select multiple="multiple" name="user[]" id="user" style="width: 100%" required></select>');
            $('#user').multiSelect('refresh');

            $.ajax({
                url: "{{url('price/custom/json/customUserList')}}",
                type: 'GET',
                data: {
                    'book_category': $("#book_category").val(),
                    'user_id':$("#user_id").val()
                },
                success: function(data) {
                    
                    $.each(data.users, function( key, value ) {
                        i=0;

                        text="";
                        if($("#book_category").val()==1){
                            text = value.first_name+" "+value.last_name;
                        }else if($("#book_category").val()==2,3,4){
                            text = value.outlet_name;
                        }

                        $.each(data.selected, function( index, val ) {
                            if($("#book_category").val()==1){
                                if(val.user_id==value.id){
                                    $('#user').append('<option selected value="'+value.id+'">'+text+'</option>');
                                    i++;
                                }
                            }else if($("#book_category").val()==2,3,4){
                                if(val.user_id==value.loc_id){
                                    $('#user').append('<option selected value="'+value.loc_id+'">'+text+'</option>');
                                    i++;
                                }
                            }
                        });

                        if(i==0){
                            if($("#book_category").val()==1){
                                $('#user').append('<option value="'+value.id+'">'+text+'</option>');
                            }else if($("#book_category").val()==2,3,4){
                                $('#user').append('<option value="'+value.loc_id+'">'+text+'</option>');
                            }
                        }

                        $('#user').multiSelect('refresh');
                    });
                    $('.panel').removeClass('panel-refreshing');
                },error: function(data){

                }
            });
        }

        function load_product(){
            $('.panel').addClass('panel-refreshing');
            $("#pro_div").html("");
            $("#pro_div").append('<select multiple="multiple" name="product[]" id="product" style="width: 100%" required></select>');
            $('#product').multiSelect('refresh');

            $.ajax({
                url: "{{url('price/standerd/json/list')}}",
                type: 'GET',
                data: {'category': $("#category").val()},
                success: function(data) {
                    $.each( data[0], function( key, value ) {
                        $('#product').append('<option value="'+value.id+'">'+value.product_name+'</option>');
                        $('#product').multiSelect('refresh');
                    });
                    $('.panel').removeClass('panel-refreshing');
                },error: function(data){

                }
            });
        }

        i=1;
        function get_product(){

            i=$('#row_count').val();
            if($("#product").val()!=null) {
                var x = $("#product").val()
                var y = $("#edit_table > tbody > tr").length;
                var value = true;
                for (var t = 1; y >= t; t++) {
                    var prduct = $("#product_id_" + t).val();
                    if (jQuery.inArray(prduct, x) !== -1) {
                        value = false;
                        load_product();
                    }
                }
                $('.panel').addClass('panel-refreshing');
                if (value) {
                $.ajax({
                    url: "{{url('price/standerd/json/productList')}}",
                    type: 'GET',
                    data: {'product': $("#product").val()},
                    success: function (data) {
                        $.each(data, function (key, value) {

                            if(value.mrp && value.mrp.mrp!="0.00"){
                                $('#tbody').append(
                                    '<tr>'
                                    + '<td>' + i + '</td>'
                                    + '<input type="hidden" id="detail_id_' + i + '" name="detail_id_' + i + '" value="0"/>'
                                    + '<td>' + value.short_code + '</td>'
                                    + '<input type="hidden" id="product_id_' + i + '" name="product_id_' + i + '" value="' + value.id + '"/>'
                                    + '<td>' + value.product_name + '</td>'
                                    + '<td>' + value.description + '</td>'
                                    + '<input type="hidden" id="category_id_' + i + '" name="category_id_' + i + '" value="' + value.category.id + '"/>'
                                    + '<td>' + value.category.category_name + '</td>'
                                    +'<td>'+value.mrp.mrp+'</td>'
                                    + '<td><input type="number" class="form-control" width="100%" id="product_price_' + i + '" name="product_price_' + i + '"/></td>'
                                    + '</tr>'
                                );
                                i++;
                            }                            
                        });
                        $('#row_count').val(i);
                        $('#product').multiSelect('deselect_all');
                        $('.panel').removeClass('panel-refreshing');
                    }, error: function (data) {

                    }
                });
            }else {
                    swal('Oops!','Some Products are Duplicate..','error');
                }
            }else{
                swal('Oops!','Please select product','error');
            }
        }

//        var sCSV;
//
//        function get_excel_data(oEvent){
//            var oFile = oEvent.target.files[0];
//            var sFilename = oFile.name;
//            // Create A File Reader HTML5
//            var reader = new FileReader();
//
//            // Ready The Event For When A File Gets Selected
//            reader.onload = function(e) {
//                var data = e.target.result;
//                var cfb = XLS.CFB.read(data, {type: 'binary'});
//                var wb = XLS.parse_xlscfb(cfb);
//                // Loop Over Each Sheet
//                wb.SheetNames.forEach(function(sheetName) {
//                    // Obtain The Current Row As CSV
//                    //sCSV = XLS.utils.make_csv(wb.Sheets[sheetName]);
//                    //var oJS = XLS.utils.sheet_to_row_object_array(wb.Sheets[sheetName]);
//                    sCSV = XLS.utils.sheet_to_row_object_array(wb.Sheets[sheetName]);
//
//                });
//            };
//
//            // Tell JS To Start Reading The File.. You could delay this if desired
//            reader.readAsBinaryString(oFile);
//        }

        function save_data(){
            $('.panel').addClass('panel-refreshing');
            k=0;

            if($("#name").val()==""){
                $("#name").removeClass("valid");
                $("#name").addClass("error");
                k++;
            }
            if($("#effective_date").val()==""){
                $("#effective_date").removeClass("valid");
                $("#effective_date").addClass("error");
                k++;
            }

            basic=[];
            tmp={};
            tmp['price_book_type']=2;
            tmp['price_book_category']=$("#book_category").val();
            tmp['price_book_name']=$("#name").val();

            basic.push(tmp);
            detail=[];
            l=0;

            for (var j = 1; j <$('#row_count').val(); j++) {
                tmp={};
                if($("#product_id_"+j)){
                    tmp['category_id']=$("#category_id_"+j).val();
                    tmp['detail_id']=$("#detail_id_"+j).val();
                    tmp['pro_id']=$("#product_id_"+j).val();
                    tmp['price']=$("#product_price_"+j).val();
                    tmp['effective_date']=$("#effective_date").val();
                    if($("#product_price_"+j).val()==""){
                        $("#product_price_"+j).addClass("error")
                        l++;
                    }
                }
                detail.push(tmp);
            }
            
            if(detail.length>0 && $("#name").val()!="" && $("#effective_date").val()!="" && l==0){

                $.ajax({
                    url: "{{url('price/custom/edit')}}",
                    type: 'POST',
                    data: {
                        'basic':basic,
                        'detail': detail,
                        'users': $("#user").val(),
                        'price_book_id':$("#user_id").val()
                    },
                    success: function(data) {                            
                        if(data==1){
                            $('.panel').removeClass('panel-refreshing');
                            swal({
                                title: "Hooray",
                                text: "Successfully Created",
                                type: "success"
                            },function(){
                                window.location.href = "{{url('price/custom/list')}}";
                            });
                        }else{
                            $('.panel').removeClass('panel-refreshing');
                            swal('Oops!','Something went wrong','error');
                        }
                    },error: function(data){

                    }
                });

            }else if($("#name").val()==""){
                $('.panel').rempveClass('panel-refreshing');
                swal('Oops!','No Valid Name for Price Book','error');
            }else if($("#effective_date").val()==""){
                $('.panel').rempveClass('panel-refreshing');
                swal('Oops!','No Valid Effective Date for Price Book','error');
            }else if(detail.length!=0 && l!=0){
                $('.panel').rempveClass('panel-refreshing');
                swal('Oops!','Invalid Price','error');
            }

        }
    </script>
@stop

