@extends('layouts.master') @section('title','Add Standerd Price Book')
@section('css')
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
            <a href="javascript:;">Standard Price Book Management</a>
        </li>
        <li class="active">Edit Standard Price Book</li>
    </ol>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <strong>Edit Price Book</strong>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal form-validation" method="post">
                        {!!Form::token()!!}
                        <input type="hidden" id="id" value="{{$dateSet->id}}">
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
                                {!! Form::select('book_category', $sale_party, $dateSet->category,['class'=>'chosen','style'=>'width:100%;','id'=>'book_category','data-placeholder'=>'']) !!}
                                @if($errors->has('book_category'))
                                    <label id="label-error" class="error" for="label">{{$errors->first('book_category')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Price Book</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @if($errors->has('name')) error @endif"
                                       name="name" id="name" placeholder="Price Book Name" required value="{{$dateSet->name}}">
                                @if($errors->has('name'))
                                    <label id="label-error" class="error"
                                           for="label">{{$errors->first('name')}}</label>
                                @endif
                            </div>
                            <label class="col-sm-2 control-label required">Effective Date</label>
                            <div class="col-sm-4">
                                <div class="input-daterange" id="datepicker">
                                    <input type="text" class="input-sm form-control @if($errors->has('effective_date')) error @endif required" name="effective_date" id="effective_date" placeholder="Effective Date"  value="{{$dateSet->details[0]->effective_date}}">
                                    @if($errors->has('effective_date'))
                                        <label id="effective_date_label-error" class="error" for="label">{{$errors->first('effective_date','The effective date field is required.')}}</label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Product Details</label>
                            <div class="col-sm-10">
                                <table id="print_table" name="print_table" class="table table-bordered bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center" width="4%">#</th>
                                        <th rowspan="2" class="text-center" style="font-weight:normal;">Short Code</th>
                                        <th rowspan="2" class="text-center" style="font-weight:normal;">Name</th>
                                        <th rowspan="2" class="text-center" style="font-weight:normal;">Description</th>
                                        <th rowspan="2" class="text-center" style="font-weight:normal;">Category</th>
                                        <th rowspan="2" class="text-center" style="font-weight:normal;">MRP</th>
                                        <th rowspan="2" class="text-center" style="font-weight:normal;">Selling Price</th>
                                    </tr>
                                    <tr style="display: none;">
                                        <th style="display: none;" width="2%"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody" name="tbody">

                                    </tbody>
                                </table>


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
    <script type="text/javascript">
        $(document).ready(function(){

            $('#sub_id').prop('disabled', true).trigger("chosen:updated");
            $('#book_category').prop('disabled', true).trigger("chosen:updated");
            $('.form-validation').validate();
            $('#product').multiSelect();

            $('#select-all').click(function(){
                $('#product').multiSelect('select_all');
                return false;
            });
            $('#deselect-all').click(function(){
                $('#product').multiSelect('deselect_all');
                return false;
            });

            get_product_data();

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

        function get_product_data(){
            $.ajax({
                url: "{{url('price/standerd/json/standardProductList')}}",
                type: 'GET',
                data:{
                    'price': $("#id").val()
                },
                success: function(data) {

                    $.each( data.data, function( key, value ) {

                        $('#tbody').append(
                            '<tr>'
                                +'<td>'+value[0]+'</td>'
                                +'<td>'+value[1]+'</td>'
                                +'<td>'+value[2]+'</td>'
                                +'<td>'+value[3]+'</td>'
                                +'<td>'+value[4]+'</td>'
                                +'<td>'+value[5]+'</td>'
                                +'<td>'+value[6]+'</td>'
                            +'</tr>'
                        );
                    });
                    $('.panel').removeClass('panel-refreshing');
                },error: function(data){

                }
            });
        }

        function save_data(){
            $('.panel').addClass('panel-refreshing');
            i = $("#print_table > tbody > tr").length;

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
            tmp['price_book_type']=1;
            tmp['price_book_category']=$("#book_category").val();
            tmp['price_book_name']=$("#name").val();

            basic.push(tmp);

            detail=[];

            l=0;

            for (var j = 1; j <=i; j++) {
                tmp={};
                if($("#product_id_"+j)){
                    tmp['category_id']=$("#category_id_"+j).val();
                    tmp['details_id']=$("#details_id_"+j).val();
                    tmp['pro_id']=$("#product_id_"+j).val();
                    tmp['price']=$("#product_price_"+j).val();
                    tmp['effective_date']=$("#effective_date").val();
                    if($("#product_price_"+j).val()=="" || $("#product_price_"+j).val()=="0.00"){
                        $("#product_price_"+j).addClass("error")
                        l++;
                    }
                }
                detail.push(tmp);
            }
            //console.log(detail);
            if(detail.length>0 && k==0 && l==0){
                $.ajax({
                    url: "{{url('price/standerd/edit')}}",
                    type: 'POST',
                    data: {
                        'id':$('#id').val(),
                        'basic':basic,
                        'detail': detail
                    },
                    success: function(data) {
                        if(data==1){
                            $('.panel').removeClass('panel-refreshing');
                            swal({
                                title: "Hooray",
                                text: "Successfully Created",
                                type: "success"
                            },function(){
                                window.location.href = "{{url('price/standerd/list')}}";
                            });
                        }else{
                            $('.panel').removeClass('panel-refreshing');
                            swal('Oops!','Something went wrong','error');
                        }
                    },error: function(data){

                    }
                });
            }else if(detail.length==0){
                swal('Oops!','No Product to Price Book','error');
            }else if(k!=0){
                swal('Oops!','Invalid Entries','error');
            }else if(l!=0){
                swal('Oops!','Invalid Price','error');
            }

        }
    </script>
@stop
