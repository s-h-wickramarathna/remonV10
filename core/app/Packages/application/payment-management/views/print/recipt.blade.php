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


<table width="100%">
    <tbody>
    <tr><!--row1-->
        <td align="center">
            <h2>RECEIPT</h2>
        </td>
    </tr>

    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>

    <tr>
        <td>
            <hr>
        </td>
    </tr>

    <tr>
        <td></td>
    </tr>

    <tr><!--row1-->
        <td>
            <table>
                <tr>
                    <td width="50%">
                        <!--CUSTOMER SECTION-->
                        <table>
                            <tr>
                                <td width="20%">
                                    Customer
                                </td>
                                <td width="5%">:</td>
                                <td width="65%">
                                    {{$receipt->outlet->f_name .' '. $receipt->outlet->l_name}}
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                    Address
                                </td>
                                <td width="5%">:</td>
                                <td width="65%">
                                    <?php $array = explode(',', $receipt->outlet->address); ?>
                                    @foreach($array as $address)
                                        {{$address}}<br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                    Telephone
                                </td>
                                <td width="5%">:</td>
                                <td width="65%">
                                    {{$receipt->outlet->telephone .' / '. $receipt->outlet->mobile}}
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
                                <td width="50%">Payment Method</td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{$receipt->types->name}}
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    Receipt No
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{$receipt->recipt_no}}
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    Receipt Date
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{date("d-m-Y", strtotime($receipt->recipt_date))}}
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    Collected By
                                </td>
                                <td width="5%">:</td>
                                <td width="55%">
                                    {{$receipt->employee->first_name}}
                                    {{$receipt->employee->last_name}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="line-height:2"></td>
    </tr>

    <tr>
        <td style="text-align: center">
            <table class="details" style="border-bottom-style:solid;">
                <tr>
                    <th width="15%" class="border-top" style="border-bottom-style:solid;">Invoice No</th>
                    <th width="15%" class="border-top" style="border-bottom-style:solid;">Invoice Date</th>
                    <th width="25%" class="border-top" style="border-bottom-style:solid;" align="right">Invoice Total</th>
                    <th width="25%" class="border-top" style="border-bottom-style:solid;" align="right">Paid For Invoice
                    </th>
                    <th width="20%" class="border-top" style="border-bottom-style:solid;" align="right">Invoice Due</th>

                </tr>
                <?php $total = 0; $invoice_due = 0;?>
                @foreach($receipt->details as $key=>$detail)
                    <?php $invoice_total = 0; ?>
                    @foreach($detail->invoice->details as $inv_detail)
                        <?php $invoice_total += (($inv_detail->unit_price * $inv_detail->qty) - $inv_detail->discount); ?>
                    @endforeach
                    <?php $total += $detail->payment_amount; ?>
                    <?php $invoice_due += $detail->invoice_due; ?>
                    <?php if($detail->invoice->discounts){$invoice_total -= $detail->invoice->discounts->discount;} ?>
                    <tr style="line-height: 2">
                        <td>#{{$detail->invoice->manual_id}}</td>
                        <td>{{date("d-m-Y", strtotime($detail->invoice->created_date))}}</td>
                        <td align="right">Rs.{{number_format($invoice_total,2)}}</td>
                        <td align="right">Rs.{{number_format($detail->payment_amount,2)}}</td>
                        <td align="right">Rs.{{number_format($detail->invoice_due,2)}}</td>
                    </tr>
                @endforeach
                <tr>

                    <td class="border-top"></td>
                    <td class="border-top"></td>
                    <td class="border-top"></td>
                    <td class="border-top" align="right">Total</td>
                    <td class="border-top" align="right">Rs.{{number_format($total,2)}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="right">Paid Amount</td>
                    <td align="right">Rs.{{number_format($receipt->amount,2)}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="right">Invoice(s) Due</td>
                    <td align="right">Rs.{{number_format($invoice_due,2)}}</td>
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
            </table>
        </td>
    </tr>

    <tr>
        <td style="line-height:2"></td>
    </tr>

    @if($receipt->cheque)
        <tr>
            <td>Cheque No - {{$receipt->cheque->cheque_no}}</td>
        </tr>

        <tr>
            <td>Cheque Date - {{$receipt->cheque->cheque_date}}</td>
        </tr>

        <tr>
            <td>Cheque Bank - {{$receipt->cheque->bank->name}}</td>
        </tr>
    @endif
    <tr>
        <td style="line-height:5"></td>
    </tr>

    <tr>
        <td>NOTE -</td>
    </tr>


    </tbody>
</table>