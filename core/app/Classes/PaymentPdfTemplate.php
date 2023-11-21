<?php
namespace App\Classes;


use TCPDF;

class PaymentPdfTemplate extends TCPDF{

  protected $data;
  protected $last_page_flag = false;
  
  public function __construct($data)
  {
	$this->data=$data;
    parent::__construct();
  }


    public function Header() {        
        $header='';
        $this->SetY(1);
        $this->SetFont('helvetica', '', 9);
        $this->writeHtml($header);

    }

    public function Close() {
        $this->last_page_flag = true;
        parent::Close();
    }
}
