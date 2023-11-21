<style type="text/css">
	
td{
	font-weight: 400;
	font-size: 10px;
}

.details , .details> th ,  .details> td {
    border-collapse: collapse;   
    padding: 5px; 
}

th {
    text-align: left;
}	


.border{
    border-width:1px;
    border-style:solid;
}

.border-left{
    border-left-style:solid;
    border-width:1px;
}

.border-right{
    border-right-style:solid;
    border-width:1px;
}

.border-top{
    border-top-style:solid;
    border-width:1px;
}

.border-bottom: {
    border-bottom-style:solid;
    border-width:1px;
}

</style>

<table width="100%">
	<tbody>
		<tr><!--row1-->
			<td align="center">			
				<h2>RECIEPT</h2>
			</td>
		</tr>

		<tr><td></td></tr>

		<tr><td><hr></td></tr>

		<tr><td></td></tr>

		<tr><!--row1-->
			<td>
				<table>
					<tr>
						<td width="50%">
							<!--CUSTOMER SECTION-->
							<table >
								<tr>
									<td width="20%">
										Customer 
									</td>
									<td width="5%">:</td>
									<td width="65%">
										{{$receipt->outlet->outlet_name}} @if(strlen($receipt->outlet->short_code)>0)({{$receipt->outlet->short_code}})@endif
									</td>
								</tr>
								<tr>
									<td width="20%">										 
									</td>
									<td width="5%">:</td>
									<td width="65%">
										<?php $array = explode(',', $receipt->outlet->outlet_address); ?>
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
										{{$receipt->outlet->outlet_tel}}
									</td>
								</tr>
							</table>
						</td>

						<td width="15%">
							<!--SPACE-->
							
						</td>

						<td width="30%">
							<!--DETAILS-->
							<table >
								<tr>
									<td width="40%">
										Recipt No 
									</td>
									<td width="5%">:</td>	
									<td width="55%">
										{{$receipt->recipt_no}}
									</td>
								</tr>
								<tr>
									<td width="40%">
										Recipt Date										 
									</td>
									<td width="5%">:</td>	
									<td width="55%">
										{{date("d-m-Y", strtotime($receipt->recipt_date))}}									  
									</td>
								</tr>
								<tr>
									<td width="40%">
										Collected By 
									</td>
									<td width="5%">:</td>
									<td width="55%">
										{{$receipt->user->employee[0]->first_name}}	
										{{$receipt->user->employee[0]->last_name}}	
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>	
			</td>	
		</tr>

		<tr><td style="line-height:2"></td></tr>

		<tr>
			<td>
				<table  class="details" style="border-bottom-style:solid;">
					<tr >			
						<th width="10%"  class="border-top" style="border-bottom-style:solid;">LineNo</th>
						<th width="22%"  class="border-top" style="border-bottom-style:solid;">Invoice No</th>
						<th width="20%" class="border-top" style="border-bottom-style:solid;">Invoice Date</th>
						<th width="16%" class="border-top" style="border-bottom-style:solid;" align="right">Invoice Total</th>
						<th width="16%" class="border-top" style="border-bottom-style:solid;" align="right">Invoice Due</th>
						<th width="16%" class="border-top" style="border-bottom-style:solid;" align="right">Paid Amount</th>
					</tr>
					<?php $total = 0 ?>
					@foreach($receipt->details as $key=>$detail)
					<?php $total += $detail->payment_amount ?>
						<tr>
							<td>{{$key}}</td>
							<td>#{{$detail->invoice->manual_id}}</td>
							<td>{{date("d-m-Y", strtotime($detail->invoice->created_date))}}</td>
							<td  align="right">Rs.{{number_format($detail->invoice->total,2)}}</td>
							<td  align="right">Rs.{{number_format(floatval($detail->invoice->total)-floatval($detail->payment_amount),2)}}</td>
							<td  align="right">Rs.{{number_format($detail->payment_amount,2)}}</td>
						</tr>
					@endforeach
				</table>				
			</td>
		</tr>

		<tr><td style="line-height:2"></td></tr>

		<tr>
			<td>Payment Method - {{$receipt->types->name}}</td>
		</tr>

		<tr>
			<td align="right">
				<h3>TOTAL : Rs.{{number_format($total,2)}}</h3>
			</td>
		</tr>

		<tr><td style="line-height:5"></td></tr>

		<tr>
			<td>NOTE - </td>
		</tr>

		
	</tbody>
</table>