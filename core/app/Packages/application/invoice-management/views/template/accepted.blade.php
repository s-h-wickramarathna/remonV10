<style>
    .inv_link {
        color: #0081c2;
        text-decoration: underline;
        font-style: italic;
    }
</style>
<tr id="{{$i}}">
    <td>{{$i}}</td>
    <td><span><i class="fa fa-link"></i> {{$order->job_no}}</span><br><a href="{{url('invoice/print?ids='.$order->id)}}"
                                                                         class="inv_link" target="_blank"
                                                                         data-toggle="tooltip" data-placement="top"
                                                                         title="Job Processing"><span><i
                        class="fa fa-link"></i> {{$order->manual_id}}</span></a>

        @if($order->payment_type == 'cash')
            <br>
            <span class="badge" style="background:#0081c2"><i class="fa fa-money"></i> Cash</span>
        @elseif($order->payment_type == 'credit')
            <br>
            <span class="badge" style="background:green"><i class="fa fa-money"></i> Credit</span>
        @endif
    </td>
    <?php $total = 0; ?>
    @foreach($order->details as $detail)
        <?php $total += (($detail->unit_price * $detail->qty) - $detail->discount)?>
    @endforeach
    <td style="text-align: right">
        Rs.{{number_format($total - (is_object($order->discounts) ? $order->discounts->discount : 0),2)}}</td>
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
            <a onclick="deleteInvoice({{$order->id}})" data-toggle="tooltip" data-placement="top"
               title="Delete Invoice" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#7b261c">
                <i class="fa fa-trash"></i>
            </span>
            </a>
        @endif
        @if($user->hasAnyAccess(['invoice.payment_aging']))
            <a href="{{url('invoice/payment-aging?id='.$order->id)}}" data-toggle="tooltip" data-placement="top"
               title="Payment Aging" style="padding-left: 2px;cursor: hand" target="_blank">
            <span class="badge" style="background:#ffa726">
                <i class="fa fa-sliders"></i>
            </span>
            </a>
        @endif
        <a href="{{url('invoice/print?ids='.$order->id)}}" data-toggle="tooltip" data-placement="top"
           title="Job Processing" style="padding-left: 2px;cursor: hand" target="_blank">
            <span class="badge" style="background:#9369cc">
                <i class="fa fa-print"></i>
            </span>
        </a>
        @if($user->id == 1 || $user->hasAnyAccess(['invoice.credit']))
            <a onclick="creditNote({{$order->id}})" data-toggle="tooltip" data-placement="top"
               title="Credit Note" style="padding-left: 2px;cursor: hand">
            <span class="badge" style="background:#0081c2">
                <i class="fa fa-undo fa-lg"></i>
            </span>
            </a>
        @endif
        {{-- <a href="delivered/{{$order->id}}" data-toggle="tooltip" data-placement="top"
            title="Package Delivered" style="padding-left: 2px;cursor: hand">
             <span class="badge" style="background:#E12222">
                 <i class="fa fa-truck"></i>
             </span>
         </a>--}}
    </td>


</tr>
