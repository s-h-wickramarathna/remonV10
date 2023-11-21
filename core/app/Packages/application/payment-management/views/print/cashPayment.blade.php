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
                                    Payment Cash - Details
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
                                    Receipt NO
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
                        <th width="5%" class="border-top">#</th>
                        <th width="25%" class="border-top">Customer</th>
                        <th align="left" width="25%" class="border-top">Receipt</th>
                        <th align="left" width="20%" class="border-top">Date</th>
                        <th align="left" width="18%" class="border-top">Remark</th>
                        <th align="right" width="10%" class="border-top">Amount</th>

                    </tr>
                </thead>
                <tbody>
                <?php $i = 1;$total=0;?>
                @foreach($orders as $order)
                    <tr>
                        <td class="border-top">{{$i}}</td>
                        <td class="border-top">{{$order->customer->f_name.' '.$order->customer->l_name}}</td>
                        <td class="border-top">{{$order->recipt_no}}
                        </td>
                        <td class="border-top">{{$order->recipt_date}}</td>
                        <td class="border-top">{{$order->remark}}</td>
                        <td class="border-top" style="text-align: right">{{number_format($order->amount,2)}}</td>

                    </tr>
                    <?php $i++;$total+=$order->amount;?>
                @endforeach
                </tbody>
                <tfoot>
                <tfoot>
                <tr>
                    <td class="border-top" colspan="5" style="font-size: 9px" align="right"><b>Total:</b></td>
                    <td class="border-top" colspan="1" align="right" style="font-size: 9px"><b>{{number_format($total,2)}}</b></td>

                </tr>
                </tfoot>
                </tfoot>
            </table>
        </td>
    </tr>
    </tbody>
</table>
    