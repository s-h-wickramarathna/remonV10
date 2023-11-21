<style>
    .inv_link {
        color: #0081c2;
        text-decoration:underline;
        font-style: italic;
    }
</style>
<tr id="{{$i}}">
    <td>{{$i}}</td>
    <td>{{$order->job_no}}<br><a href="{{url('invoice/print?ids='.$order->id)}}" class="inv_link" target="_blank" data-toggle="tooltip" data-placement="top" title="Job Processing"><span><i class="fa fa-link"></i> {{$order->manual_id}}</span></a></td>
    <td style="text-align: right">Rs.{{$order->total - (is_object($order->discounts) ? $order->discounts->discount : 0)}}</td>
    <td>{{$order->created_date}}</td>
    <td>{{$order->customer->f_name}} {{$order->customer->l_name}}</td>
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
        @if($user->id == 1 || $user->roles[0]->slug == 'admin')
        <a href="{{url('invoice/delete?ids='.$order->id)}}" data-toggle="tooltip" data-placement="top"
           title="Delete Invoice" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#7b261c">
                <i class="fa fa-trash"></i>
            </span>
        </a>
        @endif
        <a href="confirm/{{$order->id}}" data-toggle="tooltip" data-placement="top"
           title="Invoice Confirm" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#9369cc">
                <i class="fa fa-cog"></i>
            </span>
        </a>
    </td>
</tr>
