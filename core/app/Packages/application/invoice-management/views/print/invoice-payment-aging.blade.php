<style type="text/css">

    td {
        font-weight: 400;
        font-size: 10px;
    }

    .details, .details > th, .details > td {
        border-collapse: collapse;
        padding: 5px;
    }

    .border {
        border-width: 1px;
        border-style: solid;
    }

    .border-left {
        border-left-style: solid;
        border-width: 1px;
    }

    .border-right {
        border-right-style: solid;
        border-width: 1px;
    }

    .border-top {
        border-top-style: solid;
        border-width: 1px;
    }

    .border-bottom: {
        border-bottom-style: solid;
        border-width: 1px;
    }

</style>


<table width="100%" style="padding-bottom: 10px">
    <tbody>
    <tr><!--row1-->
        <td colspan="4" style="vertical-align: top">

        </td>

        <td colspan="3" style="text-align:right">
            <span style="font-weight: 900;font-size: 12px"><b>Ramon Album</b></span><br>
            <span style="font-weight: 800;font-size: 9px"><b>Maharagama</b></span><br>
            <span style="font-weight: 800;font-size: 9px"><b>Tel : 011 2089448 / 011 3098756</b></span><br>
            <!--<span style="font-weight: 800;font-size: 9px"><b>chandimal@gmail.com</b></span><br>-->
            <span style="font-weight: 800;font-size: 9px"><b>www.ramonalbum.com</b></span><br>
        </td>

    </tr>

    <tr><!--row1-->
        <td colspan="7">
            <table>
                <tr>
                    <td width="50%">
                        <!--CUSTOMER SECTION-->
                        <table>
                            <tr>
                                <td colspan="3" style="font-weight: bold;font-size: 12px;">
                                    Invoice Payment Aging - Details
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    Marketeer
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    <b>{{$page_header['marketeer']}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    Customer
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    <b>{{$page_header['customer']}}</b>
                                </td>
                            </tr>

                            <tr>
                                <td width="25%">
                                    Telephone
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    {{$page_header['cus_all']}}
                                </td>
                            </tr>
                        </table>


                    </td>

                    <td width="15%">
                        <!--SPACE-->
                    </td>

                    <td width="50%">
                        <!--DETAILS-->
                        <table>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    Invoice NO
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{$page_header['no']}}
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    From
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    <b>{{$page_header['from']}}</b>
                                </td>
                            </tr>

                            <tr>
                                <td width="40%">
                                    To
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    <b>{{$page_header['to']}}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table width="100%">
    <tbody>
    <tr>
        <td width="100%">
            <table class="details" width="100%" style="border-bottom-style:solid">
                <thead style="background-color:rgba(52, 73, 94,0.5);color:#fff;">
                <tr>
                    <th width="13%" class="border-top">Invoice No</th>
                    <th width="20%" class="border-top">Customer</th>
                    <th align="left" width="20%" class="border-top">Receipt</th>
                    <th align="left" width="15%" class="border-top">Type</th>
                    <th align="left" width="12%" class="border-top">Date</th>
                    <th align="right" width="20%" class="border-top">Invoice/Paid Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <?php $total = 0;$inv_total = 0;?>
                    <tr>
                        <td class="border-top">{{$order->manual_id}}</td>
                        <td colspan="4"
                            class="border-top">{{$order->customer->f_name.' '.$order->customer->l_name}}</td>
                        @foreach($order->details as $detail)
                            <?php $inv_total +=  (($detail->unit_price * $detail->qty ) - $detail->discount)?>

                        @endforeach
                        <td class="border-top"
                            style="text-align: right;font-weight: bold;">{{number_format($inv_total - (is_object($order->discounts) ? $order->discounts->discount : 0),2)}}</td>
                    </tr>
                    @foreach($order->recipt as $detail)
                        <tr>
                            <td class="border-bottom"></td>
                            <td class="border-bottom"></td>
                            <td class="border-bottom">{{$detail->bill->recipt_no}}</td>
                            <td class="border-bottom">{{$detail->bill->types->name}}</td>
                            <td class="border-bottom">{{$detail->bill->recipt_date}}</td>
                            <td class="border-bottom"
                                style="text-align: right;font-weight: bold;">{{number_format($detail->payment_amount,2)}}</td>
                        </tr>
                        <?php $total += $detail->payment_amount;?>
                    @endforeach
                    <tr>
                        <td colspan="5" style="font-size: 11px;border-top-style: dotted;" align="right"><b>Invoice Total:</b>
                        </td>
                        <td align="right" style="font-size: 11px;border-top-style: dotted;">
                            <b>{{number_format($inv_total - (is_object($order->discounts) ? $order->discounts->discount : 0),2)}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size: 11px" align="right"><b>Total Paid:</b></td>
                        <td align="right" style="font-size: 11px">
                            <b>{{number_format($total,2)}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size: 11px" align="right"><b>Due:</b></td>
                        <td align="right" style="font-size: 11px">
                            <b>{{number_format((($inv_total - (is_object($order->discounts) ? $order->discounts->discount : 0)) - $total),2)}}</b></td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </td>
    </tr>
    </tbody>
</table>
