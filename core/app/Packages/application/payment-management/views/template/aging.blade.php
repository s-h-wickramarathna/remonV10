@if($order->invoice_due > 0)


    @if($order->customer_name != $old_name)
        <tr>
            <td colspan="10">{{$order->customer_name}}</td>
        </tr>
    @endif

    <tr>
        <td></td>
        <td> {{$order->created_date}}</td>
        <td> {{$order->manual_id}} <br> {{$order->job_no}}<br> {{$order->couple_name}} </td>
        <td style="text-align: right">Rs.{{number_format($order->total,2)}}</td>
        <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
        <td style="text-align: right">{{$order->no_of_days}}</td>
        @if($order->no_of_days > 0 && $order->no_of_days <= 30 )
            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
            <td></td>
            <td></td>
            <td></td>
        @elseif($order->no_of_days > 30 && $order->no_of_days <= 60 )
            <td></td>
            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
            <td></td>
            <td></td>
        @elseif($order->no_of_days > 60 && $order->no_of_days <= 90 )
            <td></td>
            <td></td>
            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
            <td></td>
        @else
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right">{{number_format($order->invoice_due,2)}}</td>
        @endif
    </tr>
@endif