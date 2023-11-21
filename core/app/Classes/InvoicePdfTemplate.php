<?php
namespace App\Classes;

/**
 *
 * PDF TEMPLATE
 *
 * @author chandimal G.G.C.S
 * @version 1.0.0
 * @copyright Copyright (c) 2015
 *
 */
use TCPDF;

class InvoicePdfTemplate extends TCPDF
{


    protected $data;
	protected $last_page_flag = false;

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function Header()
    {

        $headerData = $this->getHeaderData();
        $distributor_data = $headerData['logo'][0];
        $invoice = $headerData['logo'][1];




        $this->SetY(8);
        // Set font
        $this->SetFont('helvetica','', 10);

        $header = '<table width="100%" style="padding-bottom: 10px" >
						<tbody>
							<tr ><!--row1-->
								<td colspan="4" style="vertical-align: top">

								</td>

								<td colspan="3" style="text-align:right">
									<b><span style="font-weight: 900;font-size: 12px">Ramon Album</b></span><br>
									<span style="font-weight: 800;font-size: 9px"><b>Maharagama</b></span><br>
									<span style="font-weight: 800;font-size: 9px"><b>Tel : 0112089448 / 0113098756</b></span><br>
									<!--<span style="font-weight: 800;font-size: 9px"><b>chandimal@gmail.com</b></span><br>-->
									<span style="font-weight: 800;font-size: 9px"><b>www.ramonalbum.com</b></span><br>
								</td>

							</tr>

							<tr><!--row1-->
								<td colspan="3"></td>
								<td align="center">
									<div style="font-size: 13px; border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-width: 1px;!important border-radius:50px">
										<b>INVOICE</b>
									</div>
								</td>
								<td colspan="3"></td>
							</tr>
								<tr><!--row1-->
									<td colspan="7">
										<table>
											<tr>
												<td width="50%">
													<!--CUSTOMER SECTION-->
													<table >
														<tr>
															<td width="25%">
																Customer
															</td>
															<td width="5%">:</td>
															<td width="60%">
																<b>' . $invoice->customer->f_name . ' '.$invoice->customer->l_name.'</b>
															</td>
														</tr>
														<tr>
															<td width="25%">
															</td>
															<td width="5%">:</td>
															<td width="60%">
																' . $invoice->customer->address . '
															</td>
														</tr>
														<tr>
															<td width="25%">
																Telephone
															</td>
															<td width="5%">:</td>
															<td width="60%">
																' . $invoice->customer->mobile . ' / ' . $invoice->customer->telephone.'
															</td>
														</tr>
														<tr>
															<td width="25%">
																Couple Name
															</td>
															<td width="5%">:</td>
															<td width="60%">
																' . $invoice->couple_name .'
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
																Invoice No
															</td>
															<td width="5%">:</td>
															<td width="55%">
																<b>' . $invoice->manual_id . '</b>
															</td>
														</tr>
														<tr>
															<td width="40%">
																Job No
															</td>
															<td width="5%">:</td>
															<td width="55%">
																<b>' . $invoice->job_no . '</b>
															</td>
														</tr>
														<tr>
															<td width="40%">
																Invoice Date
															</td>
															<td width="5%">:</td>
															<td width="55%">
																' . date("d-m-Y", strtotime($invoice->created_date)) . '
															</td>
														</tr>														
														<tr>
															<td width="40%">
																Create By
															</td>
															<td width="5%">:</td>
															<td width="55%">
																' . $invoice->create_by->first_name . '
																' . $invoice->create_by->last_name . '
															</td>
														</tr>
														<tr>
															<td width="40%">
																Marketeer
															</td>
															<td width="5%">:</td>
															<td width="55%">
																' . $invoice->employee->first_name . '
																' . $invoice->employee->last_name . '<br>
																' . $invoice->employee->mobile . '
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</tbody>
						</table>';
						/*<table  class="details" width="100%" style="border-collapse: collapse;padding: 5px;border-width: 1px;border-top-style:solid;border-bottom-style:solid">
							<tr>
								<th width="4%" class="border-top" style="border-bottom-style:solid;border-top-style:solid;">#</th>
								<th width="44%" class="border-top" style="border-bottom-style:solid;border-top-style:solid;">Sales Item</th>
								<th width="12%" class="border-top" style="border-bottom-style:solid;border-top-style:solid;" align="right">MRP</th>
								<th width="14%" class="border-top" style="border-bottom-style:solid;border-top-style:solid;" align="right">Unit Price</th>
								<th width="10%" class="border-top" style="border-bottom-style:solid;border-top-style:solid;" align="right">Qty</th>
								<th width="16%" class="border-top" style="border-bottom-style:solid;border-top-style:solid;" align="right">Amount</th>
							</tr>
						</table>';*/

        $this->writeHtml($header);
    }

    public function Footer()
    {

		//if ($this->last_page_flag) {
			// Position at 15 mm from bottom
			$this->SetY(-30);
			// Set font
			$this->SetFont('helvetica', '', 11);
			$pagenumber = 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages();
			$footer = '	<hr>
						<table width="100%" align="center">
							<tbody>
								<tr><td style="line-height:0.5"></td></tr>
								<tr>
									<td>								
									 <br/>' . $pagenumber . '
									</td>
								</tr>
								<tr>
									<td align="right">								

									</td>
								</tr>
							</tbody>
						</table>';

			$this->writeHtml($footer);
		//}
    }

	public function Close() {
		$this->last_page_flag = true;
		parent::Close();
	}
}
