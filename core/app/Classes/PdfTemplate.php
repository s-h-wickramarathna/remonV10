<?php
namespace App\Classes;

use Elibyy\TCPDF\TCPDF;

class PdfTemplate extends TCPDF{

  protected $data;
  protected $last_page_flag = false;
  
  /*public function __construct($data)
  {
	$this->data=$data;
    parent::__construct();
  }*/


    public function Header() {

       /* $headerData = $this->getHeaderData();
        //$distributor_data = $headerData['logo'][0];

        $this->SetY(8);
        // Set font
        $this->SetFont('helvetica','', 10);

        $header='<table width="100%" style="padding-bottom: 10px" >
						<tbody>
							<tr ><!--row1-->
								<td colspan="4" style="vertical-align: top">

								</td>

								<td colspan="3" style="text-align:right">
									<b><span style="font-weight: 900;font-size: 12px">Ramon Album</b></span><br>
									<span style="font-weight: 800;font-size: 9px"><b>Maharagama</b></span><br>
									<span style="font-weight: 800;font-size: 9px"><b>Tel : 011-11111111</b></span><br>
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
                                                <table >
                                                    <tr>
                                                        <td colspan="3" style="font-weight: bold;font-size: 12px;" >
                                                            Customer Receivables Aging - Details
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="25%">
                                                            Customer
                                                        </td>
                                                        <td width="5%">:</td>
                                                        <td width="60%">
                                                            <b>ADL SS</b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td width="25%">
                                                            Telephone
                                                        </td>
                                                        <td width="5%">:</td>
                                                        <td width="60%">
                                                            0711111111
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="25%">
                                                            Marketeer
                                                        </td>
                                                        <td width="5%">:</td>
                                                        <td width="60%">
                                                           In House
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
                                                    <tr><td></td></tr>
                                                    <tr>
                                                        <td width="40%">
                                                            Aging Date
                                                        </td>
                                                        <td width="5%">:</td>
                                                        <td width="55%">
                                                            2017-05-01
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%">
                                                            From
                                                        </td>
                                                        <td width="5%">:</td>
                                                        <td width="55%">
                                                            <b>2017-05-31</b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td width="40%">
                                                            TO
                                                        </td>
                                                        <td width="5%">:</td>
                                                        <td width="55%">
                                                            <b>2017-06-01</b>
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
                        // Title
                        $this->writeHtml($header);*/

    }

//    public function Footer() {
//
//		// Position at 15 mm from bottom
//	    $this->SetY(-30);
//	    // Set font
//	    $this->SetFont('helvetica', 'I', 9);
//			$pagenumber='Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
//			$footer='
//						<table width="100%" align="center">
//							<tr>
//								<td>.......................................</td>
//								<td>.......................................</td>
//								<td>.......................................</td>
//							</tr>
//							<tr>
//								<td>Sales Rep Signature</td>
//								<td>Distributor Signature</td>
//								<td>Customer Signature</td>
//							</tr>
//							<tr><td style="line-height:2"></td></tr>
//						</table>
//
//
//						<hr>
//						<table width="100%" align="center">
//							<tbody>
//								<tr><td style="line-height:0.5"></td></tr>
//								<tr>
//									<td>
//									 <br/>'.$pagenumber.'
//									</td>
//								</tr>
//								<tr>
//									<td align="right">
//
//									</td>
//								</tr>
//							</tbody>
//						</table>';
//
//		$this->writeHtml($footer);
//	}

    public function Close() {
        $this->last_page_flag = true;
        parent::Close();
    }
}
