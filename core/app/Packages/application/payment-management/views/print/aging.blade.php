<style type="text/css">

    td {
        font-weight: 400;
        font-size: 8px;
    }

    .details, .details > th, .details > td {
        border-collapse: collapse;
        padding: 5px;
    }

    th {
        text-align: left;
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

    .border-bottom {
        border-bottom-style: dotted;
        border-width: 1px;
    }

</style>
<table width="100%" style="padding-bottom: 10px">
    <tbody>
    <tr><!--row1-->
        <td colspan="4" style="vertical-align: top">

        </td>

        <td colspan="3" style="text-align:right">
            <b><span style="font-weight: 900;font-size: 12px">Ramon Album</b></span><br>
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
                                    Customer Receivables Aging - Details
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
                            <tr>
                                <td width="25%">
                                    Marketeer
                                </td>
                                <td width="5%">:</td>
                                <td width="60%">
                                    {{$page_header['marketeer']}}
                                </td>
                            </tr>
                        </table>


                    </td>

                    <td width="15%">
                        <!--SPACE-->
                    </td>

                    <td width="30%">
                        <!--DETAILS-->
                        <table>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    Aging Date
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{$page_header['aging_date']}}
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
                <thead>
                <tr>
                    <th width="12%" class="border-top">Customer</th>
                    <th width="11%" class="border-top">Invoice No</th>
                    <th align="right" width="12%" class="border-top">Total</th>
                    <th align="right" width="12%" class="border-top">Inv Due</th>
                    <th align="right" width="10%" class="border-top">No Of Days (Current Date)</th>
                    <th align="right" width="11%" class="border-top">0 - 15</th>
                    <th align="right" width="11%" class="border-top">16 - 30</th>
                    <th align="right" width="11%" class="border-top">31 - 60</th>
                    <th align="right" width="10%" class="border-top">60+</th>
                </tr>
                </thead>
                <tbody>
                <?php $old_name = ' ';$total = 0;$inv_due = 0;$first = 0;$second = 0;$third = 0;$fourth = 0;?>
                <?php $g_total = 0;$g_inv_due = 0;$g_first = 0;$g_second = 0;$g_third = 0;$g_fourth = 0;?>
                @foreach($orders as $key => $order)
                    @if($order->invoice_due > 0)
                        @if($order->customer_name != $old_name)
                            @if($key > 0 && $inv_due > 0)
                                <tr>
                                    <td style="text-align: right;font-weight: bold;" colspan="2">
                                        Total
                                    </td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($total,2)}}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($inv_due,2)}}</td>
                                    <td style="text-align: right;font-weight: bold;"
                                        colspan="2">{{number_format($first,2)}}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($second,2)}}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($third,2)}}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($fourth,2)}}</td>
                                </tr>
                                <?php $g_total += $total; ?>
                                <?php $g_inv_due += $inv_due; ?>
                                <?php $g_first += $first; ?>
                                <?php $g_second += $second; ?>
                                <?php $g_third += $third; ?>
                                <?php $g_fourth += $fourth; ?>
                                <?php $total = 0;$inv_due = 0;$first = 0;$second = 0;$third = 0;$fourth = 0;?>
                            @endif
                            <tr>
                                <td colspan="10" class="border-top">{{$order->customer_name}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="border-bottom"> {{$order->created_date}}</td>
                            <td class="border-bottom"> {{$order->manual_id}} <br> {{$order->job_no}} <br> {{$order->couple_name}} </td>
                            <td class="border-bottom" style="text-align: right">{{number_format($order->total,2)}}</td>
                            <td class="border-bottom"
                                style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                            <td class="border-bottom" style="text-align: right">{{$order->no_of_days}}</td>
                            @if($order->no_of_days >= 0 && $order->no_of_days <= 15 )
                                <td class="border-bottom"
                                    style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                <td class="border-bottom"></td>
                                <td class="border-bottom"></td>
                                <td class="border-bottom"></td>
                                <?php $first += $order->invoice_due; ?>
                            @elseif($order->no_of_days > 15 && $order->no_of_days <= 30 )
                                <td class="border-bottom"></td>
                                <td class="border-bottom"
                                    style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                <td class="border-bottom"></td>
                                <td class="border-bottom"></td>
                                <?php $second += $order->invoice_due; ?>
                            @elseif($order->no_of_days > 30 && $order->no_of_days <= 60 )
                                <td class="border-bottom"></td>
                                <td class="border-bottom"></td>
                                <td class="border-bottom"
                                    style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                <td class="border-bottom"></td>
                                <?php $third += $order->invoice_due; ?>
                            @else
                                <td class="border-bottom"></td>
                                <td class="border-bottom"></td>
                                <td class="border-bottom"></td>
                                <td class="border-bottom"
                                    style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
                                <?php $fourth += $order->invoice_due; ?>
                            @endif
                        </tr>
                        <?php $total += $order->total; ?>
                        <?php $inv_due += $order->invoice_due; ?>
                        <?php $old_name = $order->customer_name; ?>
                        @if($key == (sizeof($orders) -1)  && $inv_due > 0 )
                            <tr>
                                <td style="text-align: right;font-weight: bold;" colspan="2">Total
                                </td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($total,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($inv_due,2)}}</td>
                                <td style="text-align: right;font-weight: bold;"
                                    colspan="2">{{number_format($first,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($second,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($third,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($fourth,2)}}</td>
                            </tr>
                            <?php $g_total += $total; ?>
                            <?php $g_inv_due += $inv_due; ?>
                            <?php $g_first += $first; ?>
                            <?php $g_second += $second; ?>
                            <?php $g_third += $third; ?>
                            <?php $g_fourth += $fourth; ?>
                            <tr>
                                <td style="text-align: right;font-weight: bold;" colspan="2">Grand Total
                                </td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($g_total,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($g_inv_due,2)}}</td>
                                <td style="text-align: right;font-weight: bold;"
                                    colspan="2">{{number_format($g_first,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($g_second,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($g_third,2)}}</td>
                                <td style="text-align: right;font-weight: bold;">{{number_format($g_fourth,2)}}</td>
                            </tr>
                            <?php $total = 0;$inv_due = 0;$first = 0;$second = 0;$third = 0;$fourth = 0;?>
                        @endif
                    @endif
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
    