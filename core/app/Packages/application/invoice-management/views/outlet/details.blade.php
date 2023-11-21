@extends('layouts.master') @section('title','Menu List')
@section('css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
<style type="text/css">

    .info-wrap {
        margin-bottom: 10px !important;
        font-size: 12px;
    }

    .info-wrap .fa{
        font-size: 15px;
    }

    .personel-info {
        background: #fff;
        padding: 8px 0;
        text-align: left;
    }

    .personel-info span.icon {
        font-size: 16px;
        display: inline-block;
        width: 16px;
        height: 16px;
        line-height: 16px;
        margin-right: 16px;
        text-align: center;
        float: left;
    }

    .outlet-name h5{
        font-weight: 600;
        font-size: 15px;
    }

    .outlet-name h6{
      color: #999;
      font-size: 9px;
    }

    .link{
        color: #456eff;
        text-decoration: underline;
    }




    .details-title{
        color: #AFAFAF;
        font-weight: 600;
        margin: 0;
        margin-top: 2px; 
    }

    .details-text{
        margin-top: 5px; 
    }

</style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Outlet List Management</a>
        </li>
        <li>
            <a href="{{url('outlet/list')}}">Outlet List</a>
        </li>
        <li class="active">Outlet details</li>
    </ol>
    <section>
        <div class="row">
            <div>
               <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="row">
                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <div class="panel panel-bordered">
                                        <div class="panel-heading border">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h4 >@if($outlet->outlet_name || $outlet->outlet_name!=""){{$outlet->outlet_name}}@endif</h4>
                                                    <h5 >@if($outlet->short_code || $outlet->short_code!="")(#{{$outlet->short_code}})@endif</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5 class="details-title">Outstanding</h5>  
                                                    <h6 class="details-text" style="font-weight: 600">Rs {{$outlet_invoice_details->outstanding}} ({{$outlet_invoice_details->count}})</h6>                                          
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5 class="details-title">Remaining</h5>  
                                                    <h6>Rs.{{$outlet_inv_remain_details->remain}} ({{$outlet_inv_remain_details->count}})</h6>                                      
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                  </div>
                            </div> 

                            <div class="row">
                                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <div class="panel panel-bordered">
                                        <div class="panel-heading border">
                                            <div class="row">
                                                <div class="col-xs-6" >
                                                    <h6 style="margin: 1px;padding: 0">Details</h6></h6>                                                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            @if($outlet->outlet_tel || $outlet->outlet_tel!="")
                                                <div class="personel-info">
                                                    <span class="icon"><i class="fa fa-phone"></i></span>
                                                    <span>+ {{$outlet->outlet_tel}}</span>
                                                </div>
                                            @endif 
                                              
                                            @if($outlet->outlet_email || $outlet->outlet_email!="")
                                                <div class="personel-info pt-n">
                                                    <span class="icon"><i class="fa fa-envelope"></i></span>
                                                    <span>{{$outlet->outlet_email}}</span>
                                                </div>
                                            @endif

                                            @if($outlet->outlet_fax || $outlet->outlet_fax!="")
                                                <div class="personel-info pt-n">
                                                    <span class="icon"><i class="fa fa-fax"></i></span>
                                                    <span>{{$outlet->outlet_fax}}</span>
                                                </div>
                                            @endif

                                            @if($outlet->outlet_address || $outlet->outlet_address!="")   
                                                <div class="personel-info">
                                                    <span class="icon"><i class="fa fa-map-marker"></i></span>
                                                    <span>{{$outlet->outlet_address}} <a href="{{url('#')}}" class="link">on map</a></span>
                                                </div>
                                            @endif 
                                        </div>
                                    </div>  
                                  </div>
                            </div> 

                            <!-- <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                  <div class="panel panel-bordered">
                                    <div class="panel-heading border">
                                        <div class="row">
                                            <div class="col-xs-6" >
                                             <h6 style="margin: 1px;padding: 0"><span class="badge" style="background-color: #43b014">5</span> Distributors</h6></h6>
                                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div>
                                            @for($i=0;$i<=4;$i++)
                                                @if($outlet->outlet_tel || $outlet->outlet_tel!="")
                                                   <div class="media">
                                                        <a class="media-left" href="#">
                                                            <i class="fa fa-user" style="font-size: 19px;"></i>
                                                        </a>
                                                        <div class="media-body pb-md">
                                                            <h5 class="media-heading">Jesse Watson</h5>
                                                            <h6 style="font-size: 10px"><a href="" class="link">071-289657</a></h6>
                                                        </div>
                                                    </div>
                                                    
                                                @endif 
                                            @endfor

                                           
                                        </div>

                                    </div>
                                </div>  
                              </div>
                            </div>   -->
                        </div>
                    </div>
               </div> 

               <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

                     <div class="panel panel-bordered">
                        <div class="panel-heading border">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <h5><strong>Details</h5>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                                    @if($user->hasAnyAccess($isNewPayament))
                                     <a type="button" class="btn btn-primary" href="{{url('payment/new')}}/{{$outlet->id}}">New Payment</a> 
                                    @endif
                                    <a type="button" class="btn btn-primary" href="{{url('returnManagement/add')}}/{{$outlet->id}}">Return</a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered bordered table-striped table-condensed datatable" id="delivery" name="delivery">
                                <thead>
                                    <tr>
                                        <th  class="text-center" width="4%">#</th>
                                        <th  class="text-center" style="font-weight:normal;">Manual ID</th></th>
                                        <th  class="text-center" style="font-weight:normal;">Invoice Date</th>
                                        <th  class="text-center" style="font-weight:normal;">Total</th>
                                        <th  class="text-center" style="font-weight:normal;">Remain</th>
                                    </tr> 
                                </thead>
                            </table>                        
                        </div>
                    </div>    
               </div> 

            </div>
        </div>
    </section>
    
@stop
@section('js')

   <script src="{{url('/assets/angular/angular/angular.min.js')}}"></script>
    <script src="{{url('/assets/ag_grid/ag-grid.js')}}"></script>
    <script src="{{url('/assets/ag_grid/grid/grid.js')}}"></script>

    <script type="text/javascript">
        /* 
        * A CRAFT from MAC
        * Be a hacker but aint be a thief
        *
        */
       $(document).ready(function () {

            table = generateTable('.datatable', '{{url('outlet/json/getOutletInvoices')}}?location_id={{$outlet["4ever_location_id"]}}', [], [4]);

            table.on('draw.dt', function () {
                $("[data-toggle=tooltip]").tooltip();
            });
        });

        // function successFunc(data) {
        //     table.ajax.reload();
        // }

        // function printTable() {
        //     $('#print_table').print();
        // }

    </script>
@stop
