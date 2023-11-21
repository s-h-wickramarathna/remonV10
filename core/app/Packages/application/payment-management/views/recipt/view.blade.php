@extends('layouts.master') @section('title','Menu List')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/ag_grid/themes/theme-material.css')}}">
    <style type="text/css">


        .btn-grid-sm {
            background: none;
            color: #3C3C3C;
            border: none;
            font-weight: 800;
            font-size: 15px;
            padding: 5px;
        }

        .btn-grid-sm:hover {
            color: red;
        }

        .grid-align {
            text-align: center;
        }

        table > thead {
            background: #9DD84F;
        }

        .totals {
            background: #ddd;
            padding-bottom: 5px;
            padding-top: 5px;
        }


    </style>
@stop
@section('content')
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}"><i class="fa fa-home mr5"></i>Home</a>
        </li>
        <li>
            <a href="javascript:;">Payment Management</a>
        </li>
        <li class="active">Receipt Details</li>
    </ol>

    <section>
        <div class="col-xs-12">
            <div class="panel panel-bordered">
                <div class="panel-heading border">
                    <div class="row">
                        <div class="col-xs-4"><h6 style="font-weight: 600">Receipt - {{$receipt->recipt_no}}</h6></div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-right">
                            <a target="_blank" href="{{url('payment/receipt/print/')}}/{{$receipt->id}}"
                               class="btn btn-info">Print</a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left">
                            <h3 style="margin: 2px;color:#A6DC5E;font-weight: 600">RECEIPT</h3>
                            <h5 style="margin: 2px;font-weight: 600;color: #ddd">#{{$receipt->recipt_no}}</h5>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right  ">
                            <address>
                                {{--<p>
                                   <span style="font-weight: 600;">{{$distributor->first_name.' '.$distributor->last_name}}</span><br>
                                   {{$distributor->address}}<br>
                                   {{$distributor->mobile?$distributor->mobile:'-'}}
                                </p>--}}
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left">
                            <address>
                                <p>
                                    <span style="font-weight: 600;">Customer : {{$receipt->outlet->f_name .' '. $receipt->outlet->l_name}}
                                        .</span><br>
                                    Tel No : {{$receipt->outlet->telephone .' ' . $receipt->outlet->mobile}}<br>
                                    Email : {{$receipt->outlet->email ? $receipt->outlet->email : 'n/a'}} <br>
                                    Receipt Date : {{$receipt->recipt_date}}.<br>
                                    Payment Method : {{$receipt->types->name}}.
                                </p>
                            </address>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <table class="table-bordered bordered dataTable" >
                                <thead align="right">
                                <tr>
                                    <th width="15%" class="border-top" style="border-bottom-style:solid;">Invoice No </th>
                                    <th width="15%" class="border-top" style="border-bottom-style:solid;" >Invoice Date </th>
                                    <th width="25%" class="border-top" style="border-bottom-style:solid;" align="right">Invoice Total </th>
                                    <th width="25%" class="border-top" style="border-bottom-style:solid;" align="right">Paid For Invoice</th>
                                    <th width="20%" class="border-top" style="border-bottom-style:solid;" align="right">Invoice Due</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php $total = 0; $invoice_due = 0;?>
                                @foreach($receipt->details as $key=>$detail)
                                    <?php $invoice_total = 0; ?>
                                    @foreach($detail->invoice->details as $inv_detail)
                                        <?php $invoice_total += (($inv_detail->unit_price * $inv_detail->qty) - $inv_detail->discount); ?>
                                    @endforeach
                                    <?php $total += $detail->payment_amount; ?>
                                    <?php $invoice_due += $detail->invoice_due; ?>
                                    <?php if ($detail->invoice->discounts) {
                                        $invoice_total -= $detail->invoice->discounts->discount;
                                    } ?>
                                    <tr style="line-height: 2">
                                        <td>#{{$detail->invoice->manual_id}}</td>
                                        <td>{{date("d-m-Y", strtotime($detail->invoice->created_date))}}</td>
                                        <td align="right">Rs.{{number_format($invoice_total,2)}}</td>
                                        <td align="right">Rs.{{number_format($detail->payment_amount,2)}}</td>
                                        <td align="right">Rs.{{number_format($detail->invoice_due,2)}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="right">Total</td>
                                    <td align="right">Rs.{{number_format($total,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="right">Paid Amount</td>
                                    <td align="right">Rs.{{number_format($receipt->amount,2)}}</td>
                                </tr>
                                <tr>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                    <td class="border-top"></td>
                                    <td class="border-top" align="right">Invoice(s) Due</td>
                                    <td class="border-top" align="right">Rs.{{number_format($invoice_due,2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="right">Overpaid</td>
                                    <td align="right">
                                        Rs.@if($receipt->amount > $total) {{number_format(floatval($receipt->amount)-floatval($total), 2, '.', '')}}  @else
                                            0.00 @endif</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="text-left">
                                @if($receipt->cheque)
                                    <span>
                                     <h6>Cheque No   - {{$receipt->cheque->cheque_no}}</h6>
                                     <h6>Cheque Date - {{$receipt->cheque->cheque_date}}</h6>
                                     <h6>Bank  - {{$receipt->cheque->bank['name']}}</h6>
                                </span>
                                @endif
                                <p style="margin-top: 10px"><i>NOTE : -</i></p>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="margin-top: 20px">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <h5 style="font-weight: 600;color: #ddd">Its pleasure doing business with you.</h5>
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
         * Craft by mac
         * Print is not just ink its a art, art by new and old
         *
         */
        $(document).ready(function () {


        });
    </script>
@stop
