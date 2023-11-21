<style>
    .inv_link {
        color: #0081c2;
        text-decoration:underline;
        font-style: italic;
    }
</style>
<tr id="{{$i}}">
    <td>{{$i}}</td>
    <td><a href="{{url('job/print/'.$order->id)}}" class="inv_link" target="_blank" data-toggle="tooltip" data-placement="top"><span><i class="fa fa-link"></i> {{$order->job_no}}</span></a></td>
    <td>@if(is_object($order->invoice))<a href="{{url('invoice/print?ids='.$order->invoice->id)}}" class="inv_link" target="_blank" data-toggle="tooltip" data-placement="top"><span><i class="fa fa-link"></i> {{is_object($order->invoice) ? $order->invoice->manual_id : '-'}}</span></a>@else - @endif</td>
    <td >{{$order->customer->f_name.' '.$order->customer->l_name}}</td>
    <td>{{$order->created_at}}</td>
    <td>
        @if($order->status == 1)
            <span class="badge" style="background:#fca600"><i class="fa fa-clock-o fa-spin"></i> Pending</span>
        @elseif($order->status == 2)
            <span class="badge" style="background:#9369cc"><i class="fa fa-cog fa-spin"></i> Processing</span>
        @elseif($order->status == 3)
            <span class="badge" style="background:#0081c2"><i class="fa fa-check-circle"></i> Done</span>
        @else
            <span class="badge" style="background:#E12222"><i class="fa fa-truck"></i> Delivered</span>
        @endif
    </td>
    <td>
        @if($order->marketer_confirm == 1)
            <span class="badge" style="background:#0081c2"><i class="fa fa-clock-o fa-spin"></i> Pending</span>
        @else
            <span class="badge" style="background:#9369cc"><i class="fa fa-check-circle"></i> Done</span>
        @endif
    </td>
    <td>{{$order->employee->first_name .' '.$order->employee->last_name}}</td>
    <td style="text-align: center">
        <a href="processing/{{$order->id}}" data-toggle="tooltip" data-placement="top"
           title="Job Processing" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#9369cc">
                <i class="fa fa-cog fa-lg"></i>
            </span>
        </a>
        <a href="done/{{$order->id}}" data-toggle="tooltip" data-placement="top"
           title="Job Done" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#0081c2">
                <i class="fa fa-check-circle fa-lg"></i>
            </span>
        </a>
        <a href="delivered/{{$order->id}}" data-toggle="tooltip" data-placement="top"
           title="Package Delivered" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#E12222">
                <i class="fa fa-truck"></i>
            </span>
        </a>
    </td>


</tr>
