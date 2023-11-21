<style type="text/css">

    td {
        font-weight: 400;
        font-size: 10px;
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
        border-bottom-style: solid;
        border-width: 1px;
    }

</style>

<table width="100%">
    <tbody>
    <tr>
        <td colspan="2" width="100%">
            <table class="details" width="100%" style="border-bottom-style:solid">
                <?php $total_qty = 0;$freeIssueExist = 0;$detail_index = 1;$gross_tot = 0;$line_tot=0;$line_disc=0;?>
                @foreach($invoice->details as $key=>$detail)
                    <?php $total_qty += $detail->qty ?>
                    <?php $line_tot += (floatval($detail->unit_price) * floatval($detail->qty)) ?>
                    <?php $line_disc += floatval($detail->discount) ?>
                    <?php $gross_tot += ((floatval($detail->unit_price) * floatval($detail->qty)) - floatval($detail->discount)) ?>
                    @if($detail->free_qty > 0)
                        <?php $freeIssueExist = 1 ?>
                    @endif
                    @if($detail->qty > 0)
                        @if($detail_index == 1 || ($detail_index % 38) == 0)
                            <tr>
                                <th width="4%" class="border-top" style="border-bottom-style:solid;">#</th>
                                <th width="41%" class="border-top" style="border-bottom-style:solid;">Item</th>
                                <th width="20%" class="border-top" style="border-bottom-style:solid;" align="right">Unit
                                    Price
                                </th>
                                <th width="8%" class="border-top" style="border-bottom-style:solid;" align="right">
                                    Qty
                                </th>
                                <th width="12%" class="border-top" style="border-bottom-style:solid;" align="right">
                                    Disc
                                </th>
                                <th width="15%" class="border-top" style="border-bottom-style:solid;" align="right">
                                    Amount
                                </th>
                            </tr>
                        @endif
                        <tr>
                            <td width="4%" style="font-size: medium;line-height: 3px">{{$detail_index++}}</td>
                            <td width="41%"
                                style="font-size: medium;line-height: 10px">{{strlen($detail->product->product_name) > 38 ? substr($detail->product->product_name,0,37).'..' : $detail->product->product_name}} {{$detail->product->sizes ? '' : 'Chargers (Editor : '.$detail->editor_->first_name.' '.$detail->editor_->last_name.')'}}</td>
                            <td width="20%" style="font-size: medium;line-height: 3px"
                                align="right">{{number_format(floatval($detail->unit_price),2)}}</td>
                            <td width="8%" style="font-size: medium;line-height: 3px"
                                align="right">{{$detail->qty}}</td>
                            <td width="12%" style="font-size: medium;line-height: 3px"
                                align="right">{{$detail->discount}}</td>
                            <td width="15%" style="font-size: medium;line-height: 3px" align="right">
                                {{number_format((floatval($detail->unit_price)*floatval($detail->qty))- floatval($detail->discount),2)}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="100%">
            <table width="82%">
                <tr>
                    <td style="font-size: medium">No of SKU : {{($detail_index-1)}}</td>
                    {{--<td align="right" style="font-size: medium">Total Qty : {{$total_qty}}</td>--}}

                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="line-height:2"></td>
    </tr>
    <tr>
        <td rowspan="7" width="50%">
            <table border="1">
                <tr>
                    <td width="100%" style="vertical-align: top;height: 80px;">Comment : {{$invoice->remark}}</td>
                </tr>
                {{--<tr>
                    <td width="100%" style="line-height:7;" ></td>
                </tr>--}}
            </table>
        </td>
        <td width="50%">
            <table style="padding: 1px">
                <tr>
                    <td width="50%" align="right">Sub Total :</td>
                    <td width="50%" align="right">{{number_format($line_tot,2)}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right">
            <table style="padding: 1px">
                <tr>
                    <td width="50%" align="right">Line Discount Total :
                    </td>
                    <td width="50%"
                        align="right">{{number_format($line_disc,2)}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right">
            <table style="padding: 1px">
                <tr>
                    <td width="50%" align="right">Gross Amount :
                    </td>
                    <td width="50%"
                        align="right">{{number_format($gross_tot,2)}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right">
            <table style="padding: 1px">
                <tr>
                    <td width="50%" align="right">Invoice Discount
                        ({{number_format(((is_object($invoice->discounts) ? $invoice->discounts->discount : 0)/$gross_tot)*100,2)}}
                        %) :
                    </td>
                    <td width="50%"
                        align="right">{{number_format((is_object($invoice->discounts) ? $invoice->discounts->discount : 0),2)}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <?php  $due = 0; ?>
    @foreach($invoice->recipt as $recipt)
        <?php  $due += $recipt->payment_amount ?>
    @endforeach
    <tr>
        <td align="right">
            <table style="padding: 1px">
                <tr>
                    <td width="50%" align="right">Paid Amount :</td>
                    <td width="50%" align="right">{{number_format($due,2)}}</td>
                </tr>
            </table>
        </td>
    </tr>
    @if($credit_amt > 0)
        <tr>
            <td align="right">
                <table style="padding: 3px">
                    <tr>
                        <td width="50%" align="right">Return Amount :</td>
                        <td width="50%" align="right">{{number_format($credit_amt,2)}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif
    <tr>
        <td align="right">
            <table style="padding: 3px">
                <tr>
                    <td width="50%" align="right"><h3>Due Amount :</h3></td>
                    <td width="50%" align="right"
                        style="border-bottom-style: double;border-top-style: solid;border-width: 1px"><h3>
                            Rs.{{number_format((floatval($gross_tot)-floatval((is_object($invoice->discounts) ? $invoice->discounts->discount : 0))) - (floatval($due))- (floatval($credit_amt)),2) }}</h3>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    @if($freeIssueExist > 0)
        <tr>
            <td style="font-size: medium"><b>FREE ISSUE</b></td>
        </tr>
        <tr>
            <td width="100%">
                <table class="details" width="100%" style="border-bottom-style:solid;">
                    <tr>
                        <th width="4%" class="border-top" style="border-bottom-style:solid;">#</th>
                        <th width="58%" class="border-top" style="border-bottom-style:solid;">Item</th>
                        <th width="12%" class="border-top" style="border-bottom-style:solid;" align="right">MRP</th>
                        <th width="10%" class="border-top" style="border-bottom-style:solid;" align="right">Qty</th>
                        <th width="16%" class="border-top" style="border-bottom-style:solid;" align="right">Amount</th>
                    </tr>
                    <?php $total_free_qty = 0;$index = 0; $total_amount = 0; ?>
                    @foreach($invoice->details as $key=>$detail)
                        <?php $total_free_qty += $detail->free_qty;?>
                        @if($detail->free_qty > 0)
                            <?php $index++; $total_amount += (floatval($detail->product->mrp->mrp) * floatval($detail->free_qty)); ?>
                            <tr>
                                <td style="font-size: medium;line-height: 3px">{{$index}}</td>
                                <td style="font-size: medium;line-height: 3px">{{strlen($detail->product->product_name) > 38 ? substr($detail->product->product_name,0,37).'..' : $detail->product->product_name}}</td>
                                <td style="font-size: medium;line-height: 3px"
                                    align="right">{{number_format(floatval($detail->product->mrp->mrp),2)}}</td>
                                <td style="font-size: medium;line-height: 3px" align="right">{{$detail->free_qty}}</td>
                                <td style="font-size: medium;line-height: 3px" align="right">
                                    {{number_format(floatval($detail->product->mrp->mrp)*floatval($detail->free_qty),2)}}</td>

                            </tr>
                        @endif
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table width="98%">
                    <tr>
                        <td style="font-size: medium">No of SKU : {{$index}}</td>
                        <td style="font-size: medium" align="right">Total Free Issue : {{$total_free_qty}}</td>
                        <td style="font-size: medium" align="right">Total Free Amount :
                            <b>{{number_format($total_amount,2)}}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="line-height:2"></td>
        </tr>
    @endif

    <tr>
        <td colspan="2" style="line-height:2"></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: small" align="left">
            Customer Outstanding :
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: medium" align="left">
            <strong>Rs.{{$outlet_invoice_details->remain}}</strong>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="line-height:5"></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: small" align="left">
            NOTE - Cheque to be drawn in favour of <b>Ramon Album </b>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="line-height:5"></td>
    </tr>

    </tbody>
</table>
<table width="100%" align="center">
    <tr>
        <td>.......................................</td>
        <td>............................................</td>
        <td>.......................................</td>
    </tr>
    <tr>
        <td style="font-size: small">Received by</td>
        <td style="font-size: small">Prepared by</td>
        <td style="font-size: small">Checked by</td>
    </tr>
    <tr>
        <td style="line-height:2"></td>
    </tr>
</table>
