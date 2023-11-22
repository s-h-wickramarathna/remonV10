<?php
/**
 * Created by TharinduMAc.
 * CRaft FrOm Mac <tharindup@craftbyorange.com>
 */

namespace Application\PaymentManage\Http\Controllers;


use App\Classes\PdfTemplate;
use App\Exceptions\TransactionException;
use App\Http\Controllers\Controller;

use Application\AccountManage\Models\Account;
use Application\CustomerManage\Models\Area;
use Application\CustomerManage\Models\Customer;
use Application\EmployeeManage\Models\Employee;
use Application\Functions\stab\Functions;
use Application\InvoiceManage\Models\CreditNote;
use Application\PaymentManage\Models\OverpaidTransaction;
use Application\PaymentManage\Models\PaymentAgingReport;
use Core\UserManage\Models\RoleUsers;
use Core\UserManage\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

use Application\InvoiceManage\Models\Invoice;
use Application\PaymentManage\Models\Recipt;
use Application\PaymentManage\Models\CashPayment;
use Application\PaymentManage\Models\ChequePayment;
use Application\PaymentManage\Models\ReciptDetail;
use Application\PaymentManage\Models\OverPayment;
use Application\PaymentManage\Models\PaymentType;
use Application\PaymentManage\Models\Banks;
use App\Classes\OverpaidHandler;
use Core\Permissions\Models\Permission;

use App\Classes\StockHandler;
use App\Classes\PaymentPdfTemplate;
use App\Classes\Common;

use Application\SalesOrderManage\Models\SalesOrder;


class PaymentController extends Controller
{


    public function newPaymentView(Request $request)
    {
        if ($request->type > 0) {
            $type = 1;
        } else {
            $type = 0;
        }

        $outlet = Customer::find($request->id);
        $payment_types = PaymentType::get();
        $banks = Banks::all();
        $accounts = Account::with('banks')->get();
        $overpaids = OverpaidHandler::getOverpaids($outlet["id"]);
        $sql = 'select
                        format(ifnull(sum(tmp.remain),0),2) as remain,count(tmp.id) as count from
                        (select
                           invoice.id,
                           total,
                           (IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = invoice.id group by invoice.id ),0) - (select IFNULL(sum(discount),0) from invoice_discount where invoice_id=invoice.id))-IFNULL((select sum(payment_amount) from recipt_detail where invoice_id=invoice.id),0) - IFNULL( ( SELECT sum( credit_amount ) FROM credit_note WHERE invoice_id = invoice.id ), 0 ) as remain
                         from invoice
                         where location_id=' . $outlet['id'] . ' AND invoice.deleted_at is NULL
                         ) as tmp where tmp.remain > 0';

        $outlet_invoice_details = DB::select($sql);

        /* return [
             'outlet' => $outlet,
             'outlet_invoice_details' => $outlet_invoice_details[0],
             'payment_types' => $payment_types,
             'banks' => $banks,
             'overpaids' => $overpaids,
             'type' => $type,
         ];*/

        if ($outlet) {
            return view('paymentManage::payment.new')->with([
                'outlet' => $outlet,
                'outlet_invoice_details' => $outlet_invoice_details[0],
                'payment_types' => $payment_types,
                'banks' => $banks,
                'overpaids' => $overpaids,
                'type' => $type,
                'accounts' => $accounts
            ]);
        } else {
            return response()->view("errors.404");
        }
    }

    public function newPaymentOutletList(Request $request)
    {
        $distributors = Employee::where('employee_type_id', 2)->get();
        return view('paymentManage::outlet.list')->with(['distributors' => $distributors]);
    }

    public function receiptListView(Request $request)
    {

        $outlets = Customer::get();
        return view('paymentManage::recipt.list')->with(['outlets' => $outlets]);
    }

    public function receiptDetailsView(Request $request)
    {
        $receipt_id = $request->id;
        $receipt = Recipt::with(['employee', 'types', 'details.invoice.details', 'details.invoice.discounts', 'outlet', 'cheque', 'cash'])
            ->find($receipt_id);

        $distributor = [];


        if ($receipt) {
            return view('paymentManage::recipt.view')->with(['receipt' => $receipt, 'distributor' => $distributor]);
        } else {
            return response()->view("errors.404");
        }
    }

    /**
     * get the outlets.
     *
     * @return Response with pagination
     */
    public function getOutlets(Request $request)
    {

        $search = $request->get('search')['value'];
        $rowData = Customer::
        whereRaw('(CONCAT(LOWER(TRIM(f_name)), " " ,LOWER(TRIM(l_Name)))  like "%' . $search . '%" )')->
        skip($request->get('start'))->
        take($request->get('length'))->get();


        $data = array();

        foreach ($rowData as $key => $value) {
            $aa = array();
            $outlet_name = '-';
            $short_code = '-';
            $outlet_tel = '-';
            $outlet_email = '-';
            $outlet_fax = '-';
            $outlet_address = '-';


            $sql = 'select
                        format(ifnull(sum(tmp.remain),0),2) as remain,count(tmp.id) as count from
                        (select
                           invoice.id,
                           total,
                           (IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = invoice.id group by invoice.id ),0) - (select IFNULL(sum(discount),0) from invoice_discount where invoice_id=invoice.id))-IFNULL((select sum(payment_amount) from recipt_detail where invoice_id=invoice.id),0) - IFNULL( ( SELECT sum( credit_amount ) FROM credit_note WHERE invoice_id = invoice.id ), 0 ) as remain
                         from invoice
                         where location_id=' . $value['id'] . ' AND invoice.deleted_at is NULL
                         ) as tmp where tmp.remain > 0';

            $outlet_invoice_details = DB::select($sql);

            array_push($aa, $value->id);

            if ($value->f_name || $value->f_name == "") {
                $outlet_name = $value->f_name . ' ' . $value->l_name;
            } else {
                $outlet_name = '-';
            }

            if ($value->telephone || $value->telephone == " ") {
                $outlet_tel = '<span class="icon"><i class="fa fa-phone"></i></span> ' . $value->telephone;
                $outlet_tel .= '<br><span class="icon"><i class="fa fa-phone"></i></span> ' . $value->mobile;
            } else {
                $outlet_tel = '-';
            }

            if ($value->email || $value->email == " ") {
                $outlet_email = '<span class="icon"><i class="fa fa-envelope"></i></span> ' . $value->email;
            } else {
                $outlet_email = '-';
            }


            if ($value->address || $value->address == " ") {
                $outlet_address = '<span class="icon"><i class="fa fa-map-marker"></i></span> ' . $value->address;
            } else {
                $outlet_address = '-';
            }

            array_push($aa, $outlet_name);

            //if ($value->invoice_count > 0) {
            array_push($aa, '<span class="badge" style="background-color:green">' .
                $outlet_invoice_details[0]->count . '</span>');
            /* } else {
                 array_push($aa, '-');
             }*/

            array_push($aa, $outlet_tel . '<br>' . $outlet_fax);
            array_push($aa, $outlet_email . '<br>' . $outlet_address);
            array_push($aa, 'Limit - ' . $value->credit_limit . '<br> Period - ' . $value->credit_period);

            //$permissions = Permission::whereIn('name', ['outlet.view', 'admin'])->where('status', '=', 1)->pluck('name');
            //array_push($aa, '<a href="#" class="gray" onclick="window.location.href=\'' . url('outlet/detail/' . $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="Outlet Details"><i class="fa fa-eye"></i></a>');
            /*if ($user->hasAnyAccess($permissions)) {
            } else {
                array_push($aa, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Outlet View Disabled - Persmisson Deined"><i class="fa fa-eye"></i></a>');
            }*/

            array_push($aa, '<a href="#" style="text-align:center" class="gray" onclick="window.location.href=\'' . url('payment/new/' . $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="New Payament"><i class="fa fa-money"></i></a>');
            /*$permissions = Permission::whereIn('name', ['payment.new', 'admin'])->where('status', '=', 1)->pluck('name');
            if ($user->hasAnyAccess($permissions)) {
            } else {
                array_push($aa, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Outlet View Disabled - Persmisson Deined"><i class="fa fa-money"></i></a>');
            }*/


            array_push($data, $aa);

        }

        return Response::json(['draw' => (int)$request->get('draw'),
            'recordsTotal' => (int)Customer::get(['id'])->count(),
            'recordsFiltered' => (int)Customer::whereRaw('(CONCAT(LOWER(TRIM(f_name)), " " ,LOWER(TRIM(l_Name)))  like "%' . $search . '%" )')->count(), "data" => $data]);


    }

    /**
     * get the Recipts.
     *
     * @return Response with pagination
     */
    public function getRecipts(Request $request)
    {


        $search = $request->get('search')['value'];
        $outlet_id = $request->outlet_id;
        $record_count = 0;
        $rowData = [];
        if ($outlet_id == 0) {
            $outlets = Customer::get()->pluck('id');
            $rowData = Recipt::with(['types', 'employee.type', 'outlet'])
                ->whereRaw('(CONCAT(LOWER(TRIM(recipt_no)), " " ,LOWER(TRIM(recipt_date)))  like "%' . $search . '%" )')
                ->whereIn('location_id', $outlets)->orderBy('id', 'DESC')
                ->skip($request->get('start'))
                ->take($request->get('length'))->get();

            $record_count = Recipt::
            whereRaw('(CONCAT(LOWER(TRIM(recipt_no)), " " ,LOWER(TRIM(recipt_date)))  like "%' . $search . '%" )')
                ->whereIn('location_id', $outlets)->orderBy('id', 'DESC')->count();
        } else {
            $rowData = Recipt::with(['types', 'employee', 'outlet'])
                ->whereRaw('(CONCAT(LOWER(TRIM(recipt_no)), " " ,LOWER(TRIM(recipt_date)))  like "%' . $search . '%" )')
                ->where('location_id', $outlet_id)
                ->orderBy('id', 'DESC')
                ->skip($request->get('start'))
                ->take($request->get('length'))->get();

            $record_count = Recipt::
            whereRaw('(CONCAT(LOWER(TRIM(recipt_no)), " " ,LOWER(TRIM(recipt_date)))  like "%' . $search . '%" )')
                ->where('location_id', $outlet_id)->count();

        }

        $data = array();

        foreach ($rowData as $key => $value) {
            $aa = array();

            $distributor = Employee::where('id', $value->user_id)->first();

            $recipt_no = '-';
            $recipt_date = '-';
            $amount = '-';
            $user_id = '-';
            $print_count = '-';

            array_push($aa, $value->id);

            //return $value->outlet->f_name;

            $customer_name = '-';
            if ($value->outlet->f_name) {
                $customer_name = $value->outlet->f_name;
            }
            if ($value->outlet->l_name) {
                $customer_name .= $value->outlet->l_name;
            }

            array_push($aa, $customer_name);

            if ($value->recipt_no || $value->recipt_no == "") {
                $recipt_no = $value->recipt_no;
            } else {
                $recipt_no = '-';
            }

            array_push($aa, $recipt_no);

            if ($value->recipt_date || $value->recipt_date == " ") {
                $recipt_date = $value->recipt_date;
            } else {
                $recipt_date = ' ';
            }

            array_push($aa, $recipt_date);

            if ($value->amount || $value->amount == " ") {
                $amount = 'Rs.' . $value->amount;
            } else {
                $amount = '-';
            }

            array_push($aa, $amount);


            if ($value->types || $value->types->name) {
                array_push($aa, $value->types->name);
            } else {
                array_push($aa, '-');
            }

//            if ($value->print_count || $value->print_count == " ") {
//                $print_count = '<span class="badge">' . $value->print_count . '</span>';
//            } else {
//                $print_count = '-';
//            }

//            array_push($aa, $print_count);

            if ($distributor) {
                $distributor_name = $distributor->first_name . ' ' . $distributor->last_name;
            } else {
                $distributor_name = '-';
            }
            array_push($aa, $distributor_name);

            if ($value->outlet != "") {
                $outlet = $value->f_name . ' ' . $value->l_name;
            } else {
                $outlet = '-';
            }

            //array_push($aa, $outlet);

            array_push($aa, '<a href="#" class="gray" onclick="window.location.href=\'' . url('payment/receipt/print/' . $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="Print Recipt"><i class="fa fa-print"></i></a>');
            /*$permissions = Permission::whereIn('name', ['payment_recipt.print', 'admin'])->where('status', '=', 1)->pluck('name');
            if ($user->hasAnyAccess($permissions)) {
            } else {
                array_push($aa, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Print Recipt Disabled - Persmisson Deined"><i class="fa fa-eye"></i></a>');
            }*/

            array_push($aa, '<a href="#" class="gray" onclick="window.location.href=\'' . url('payment/receipt/detail/' . $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="View Recipt"><i class="fa fa-eye"></i></a>');
            /*$permissions = Permission::whereIn('name', ['payment_recipt.view', 'admin'])->where('status', '=', 1)->pluck('name');
            if ($user->hasAnyAccess($permissions)) {
            } else {
                array_push($aa, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="View Recipt Disabled - Persmisson Deined"><i class="fa fa-eye"></i></a>');
            }*/


            array_push($data, $aa);

        }

        return Response::json(['draw' => (int)$request->get('draw'),
            'recordsTotal' => (int)Recipt::get(['id'])->count(),
            'recordsFiltered' => (int)$record_count, "data" => $data]);
    }


    public function addPayment(Request $request)
    {
        //Log::info($request['payment']['cheque']['date']);
        DB::beginTransaction();
        $status = true;

        Log::info($request['payment']['type']);
        $remark = '';
        if (isset($request['payment']['cash']['remark'])) {
            $remark = $request['payment']['cash']['remark'];
        }
        $recipt_date = date('Y-m-d H:i:s');
        if (isset($request['payment']['cash']['date'])) {
            $recipt_date = $request['payment']['cash']['date'];
        }

        $temp_amount;
        if(isset($request['payment']['cash']['amount'])){
          $temp_amount = $request['payment']['cash']['amount'] ;
        }elseif(isset($request['payment']['overpaid']['amount'])){
           $temp_amount = $request['payment']['overpaid']['amount'] ;
        }elseif(isset($request['payment']['cheque']['amount'])){
            $temp_amount = $request['payment']['cheque']['amount'] ;
         }

        $count = DB::select('SELECT * FROM recipt 
        WHERE amount = ' .$temp_amount . ' AND location_id = ' . $request['outlet']['id'] . 
        ' AND user_id ='. Sentinel::getUser()->employee_id . ' AND `type` = '.  $request['payment']['type'] .
        ' AND  TIMESTAMPDIFF(MINUTE,  recipt.created_at , now() ) <= 1 ' );
      
        if(sizeof($count) > 0) {
            Log::info("returned");
            return Response::json(["status" => 'success', 'result' => $request['type'], 'ids' => $count[0]->id]);
        } 

        if (($request['payment']['type'] == 1) || ($request['payment']['type'] == 6) || ($request['payment']['type'] == 7)) {
            //cash payment

            $recipt = Recipt::create([
                "amount" => $request['payment']['cash']['amount'],
                "remark" => $remark,
                "recipt_no" => '',
                "location_id" => $request['outlet']['id'],
                "recipt_date" => $recipt_date,
                "user_id" => Sentinel::getUser()->employee_id,
                "type" => $request['payment']['type']
            ]);

            if (!$recipt) {
                $status = false;
            } else {
                if (($request['payment']['type'] == 6) || ($request['payment']['type'] == 7)) {
                    $recipt->account_id = $request['payment']['cash']['account'];
                    $recipt->save();
                }
            }

            $cash = CashPayment::create([
                "amount" => $recipt->amount,
                "recipt_id" => $recipt->id,
            ]);

            if (!$cash) {
                $status = false;
            } else {
                $recipt->recipt_no = 'WR-CA-' . $recipt->id . '-' . $request['outlet']['id'];
                $recipt->save();
            }

        } else if ($request['payment']['type'] == 2) {
            //cheque payment
            $recipt = Recipt::create([
                "amount" => $request['payment']['cheque']['amount'],
                "remark" => $remark,
                "recipt_no" => '',
                "location_id" => $request['outlet']['id'],
                "recipt_date" => $recipt_date,
                "user_id" => Sentinel::getUser()->employee_id,
                "type" => $request['payment']['type']
            ]);

            if (!$recipt) {
                $status = false;
            }
            $time = strtotime($request['payment']['cheque']['date']);
            $new_format = date('Y-m-d', $time);

            $cheque = ChequePayment::create([
                "cheque_no" => $request['payment']['cheque']['no'],
                "cheque_amount" => $recipt->amount,
                "cheque_date" => $new_format,
                "cheque_bank_id" => $request['payment']['cheque']['bank'],
                "recipt_id" => $recipt->id,
            ]);

            if (!$cheque) {
                $status = false;
            } else {
                $recipt->recipt_no = 'WR-CH-' . $recipt->id . '-' . $request['outlet']['id'];
                $recipt->save();
            }
        } else if (($request['payment']['type'] > 2) && ($request['payment']['type'] != 6) && ($request['payment']['type'] != 7)) {
            //overpayment payment
            $recipt = Recipt::create([
                "amount" => $request['payment']['overpaid']['amount'],
                "remark" => $remark,
                "recipt_no" => '',
                "location_id" => $request['outlet']['id'],
                "recipt_date" => $recipt_date,
                "user_id" => Sentinel::getUser()->employee_id,
                "type" => $request['payment']['type']
            ]);

            if (!$recipt) {
                $status = false;
            }
            //$amount,$from,$location_id,$type='0'
            $overpayment = OverpaidHandler::useOverpaid($request['payment']['overpaid']['amount'], $recipt, $request['outlet']['id'], $request['payment']['type']);

            if (!$overpayment) {
                $status = false;
            } else {
                $recipt->recipt_no = 'WR-OV-' . $recipt->id . '-' . $request['outlet']['id'];
                $recipt->save();
            }
        }

        $invoice_id = 0;
        foreach ($request['setoff_details']['details'] as $key => $value) {

            if(($value['invoice']['total'] - $value['setoff']) < 0){
                $status = false;
                break;
            }
            //insert details
            // $invoice = SalesOrder::find($value['invoice']['id']);

            $older = ReciptDetail::where("invoice_id", $value['invoice']['id'])->sum("payment_amount");

            if ($value['setoff'] > 0) {
                $details = ReciptDetail::create([
                    "payment_amount" => $value['setoff'],
                    "invoice_due" => $value['invoice']['total'] - $value['setoff'],
                    "recipt_id" => $recipt->id,
                    "invoice_id" => $value['invoice']['id']
                ]);
                $invoice_id = $value['invoice']['id'];

                $inv = Invoice::find($invoice_id);
                if ($inv) {
                    $aging = PaymentAgingReport::where('manual_id', $inv->manual_id)->first();
                    if ($aging) {
                        $aging->invoice_due = $aging->invoice_due - $value['setoff'];
                        $aging->save();
                    }
                }
                if (!$details) {
                    $status = false;
                }
            }

        }

        if ($request['setoff_details']['overpaid'] > 0) {
            //$location_id,$amount,$type,$from
            $overpayment = OverpaidHandler::add($request['outlet']['id'], $request['setoff_details']['overpaid'], $request['payment']['type'], $recipt);
            if (!$overpayment) {
                $status = false;
            }
        }

        if ($request['type'] == 1) {
            $id = $invoice_id;
        } else {
            $id = $recipt->id;
        }

        if ($status) {
            DB::commit();
            return Response::json(["status" => 'success', 'result' => $request['type'], 'ids' => $id]);
        } else {
            DB::rollBack();
        }


    }

    public function getInvoicesFor(Request $request)
    {

        $sql = 'SELECT
              tmp1.id as id,
              tmp1.manual_id,
              tmp1.created_date,
              IFNULL( tmp1.total, 0 ) - IFNULL( tmp1.remain, 0 ) - 
	IFNULL( ( SELECT sum( credit_amount ) FROM credit_note WHERE invoice_id = tmp1.id ), 0 )  AS total
            from (SELECT
              inv.id as id,
              inv.manual_id,
              inv.created_date,
              (IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = inv.id group by inv.id ),0) - (select IFNULL(sum(discount),0) from invoice_discount where invoice_id=inv.id)) as total,
              (SELECT IFNULL(sum(recipt_detail.payment_amount),0) as  remain FROM recipt_detail where recipt_detail.invoice_id=inv.id) as remain
            FROM
              invoice inv
            WHERE inv.deleted_at is null AND inv.location_id=' . $request["outlet"]["id"] . ')tmp1
            WHERE (IFNULL(tmp1.total,0) -  IFNULL(tmp1.remain,0) - 
	IFNULL( ( SELECT sum( credit_amount ) FROM credit_note WHERE invoice_id = tmp1.id ), 0 )) > 0';


        $aa = DB::select($sql);

        if ($aa) {
            return Response::json(["invoices" => $aa]);
        } else {
            return Response::json(['status' => 'error']);
        }
    }


    public function receiptPrint(Request $request)
    {
        $receipt_id = $request->id;
        $receipt = Recipt::with(['employee', 'types', 'details.invoice.details', 'details.invoice.discounts', 'outlet', 'cheque', 'cash'])->find($receipt_id);

        $distributor_data = [];
        $distributor = [];

        if ($receipt) {
            $page1 = view('paymentManage::print.recipt')->with(['receipt' => $receipt, 'distributor' => $distributor])->render();
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PaymentPdfTemplate(['distributor_data' => $distributor_data]);
        $pdf->SetMargins(5, 10, 5);
        $pdf->SetAutoPageBreak(TRUE, 35);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt_[" . $receipt->receipt_no . "].pdf", 'I');
    }


    static function addReceiptPayment($empId, $paymentReceipt, $successArray)
    {

        //res arrays
        $receiptRes = $successArray['receipt'];
        $receiptDetailRes = $successArray['receiptDetailses'];
        $paymentCashRes = $successArray['paymentCashs'];
        $paymentChequeRes = $successArray['paymentCheques'];
        $overpaymentRes = $successArray['overpaymentses'];
        $overpaymentTransactionRes = $successArray['overpaymentTransactionses'];


        try {
            DB::transaction(function () use ($paymentReceipt, &$receiptRes, &$receiptDetailRes, &$paymentCashRes, &$paymentChequeRes, &$overpaymentRes, &$overpaymentTransactionRes, $empId) {

                // request data
                $receipt = $paymentReceipt->receipt;
                $receiptDetailses = $paymentReceipt->receiptDetailses;
                $paymentCashs = $paymentReceipt->paymentCashs;
                $paymentCheques = $paymentReceipt->paymentCheques;
                $overPayments = $paymentReceipt->overpaymentses;
                $overpaymentTransactions = $paymentReceipt->overpaymentTransactionses;

                //add receipt
                $receiptObj = Recipt::create([
                    'amount' => $receipt->receiptAmount,
                    'recipt_no' => $receipt->receiptNo,
                    'recipt_date' => Functions::formatDateToPhp($receipt->createdDate),
                    'location_id' => $receipt->outletId,
                    'type' => $receipt->paymentTypeId,
                    'user_id' => $empId,
                ]);

                if (!$receiptObj) {
                    throw new TransactionException('Something wrong', 100);
                }
                array_push($receiptRes, array('clientKey' => $receipt->receipt_id, 'serverKey' => $receiptObj->id));

                foreach ($receiptDetailses as $detail) {
                    $detailObj = ReciptDetail::create([
                        'payment_amount' => $detail->receiptDetailsAmount,
                        'invoice_id' => $detail->salesOrderRealId,
                        'recipt_id' => $receiptObj->id
                    ]);
                    if (!$detailObj) {
                        throw new TransactionException('Something wrong', 100);
                    }
                    array_push($receiptDetailRes, array('clientKey' => $detail->receiptDetails_id, 'serverKey' => $detailObj->id));
                }

                if ($receipt->paymentTypeId == 1) {
                    foreach ($paymentCashs as $cash) {
                        $cashPaymentObj = CashPayment::create([
                            'amount' => $cash->cashPaymentAmount,
                            'recipt_id' => $receiptObj->id
                        ]);
                        if (!$cashPaymentObj) {
                            throw new TransactionException('Something wrong', 100);
                        }
                        array_push($paymentCashRes, array('clientKey' => $cash->cashPayment_id, 'serverKey' => $cashPaymentObj->id));
                    }
                } else {
                    foreach ($paymentCheques as $cheque) {
                        $chequePaymentObj = ChequePayment::create([
                            'cheque_date' => Functions::formatDateToPhp($cheque->chequeBankDate),
                            'cheque_no' => $cheque->chequeNo,
                            'cheque_bank_id' => $cheque->bankId,
                            'cheque_amount' => $cheque->chequeAmount,
                            'recipt_id' => $receiptObj->id
                        ]);
                        if (!$chequePaymentObj) {
                            throw new TransactionException('Something wrong', 100);
                        }
                        array_push($paymentCashRes, array('clientKey' => $cheque->chequePayment_id, 'serverKey' => $chequePaymentObj->id));
                    }
                }

                foreach ($overPayments as $overPayment) {
                    $overpaymentObj = Overpayment::where('location_id', '=', $overPayment->outletId)->where('reference_id', '=', $overPayment->referenceId)->first();

                    if (count($overpaymentObj) > 0) {
                        $overpaymentObj->amount += $overPayment->overPaidAmount;
                        $overpaymentObj->save();

                        if (!$overpaymentObj) {
                            throw new TransactionException('Something wrong', 100);
                        }
                        array_push($overpaymentRes, array('clientKey' => $overPayment->overPaid_id, 'serverKey' => $overpaymentObj->id));
                    } else {
                        $overpaymentObj = OverPayment::create([
                            "amount" => $overPayment->overPaidAmount,
                            "location_id" => $overPayment->outletId,
                            "reference_id" => $overPayment->referenceId,
                            "over_paid_date" => Functions::formatDateToPhp($overPayment->createdDate),
                        ]);
                        if (!$overpaymentObj) {
                            throw new TransactionException('Something wrong', 100);
                        }
                        array_push($overpaymentRes, array('clientKey' => $overPayment->overPaid_id, 'serverKey' => $overpaymentObj->id));
                    }
                    foreach ($overpaymentTransactions as $overpaymentTransaction) {
                        if ($overPayment->referenceId == 3) {//credit note
                            $transaction = OverpaidTransaction::create([
                                "amount" => $overpaymentTransaction->overPaidAmount,
                                "overpayment_id" => $overpaymentObj->id,
                                "return_id" => $overpaymentTransaction->returnID,
                                "recipt_id" => null,
                            ]);
                            if (!$transaction) {
                                throw new TransactionException('Something wrong', 100);
                            }
                            array_push($overpaymentTransactionRes, array('clientKey' => $overpaymentTransaction->overPaid_id, 'serverKey' => $transaction->id));
                        } else {//cash or cheque
                            $transaction = OverpaidTransaction::create([
                                "amount" => $overpaymentTransaction->overPaidAmount,
                                "overpayment_id" => $overpaymentObj->id,
                                "return_id" => null,
                                "recipt_id" => $overpaymentTransaction->receiptId,
                            ]);
                            if (!$transaction) {
                                throw new TransactionException('Something wrong', 100);
                            }
                            array_push($overpaymentTransactionRes, array('clientKey' => $overpaymentTransaction->overPaid_id, 'serverKey' => $transaction->id));
                        }
                    }
                }

            });
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return response()->json('#');
            } else if ($e->getCode() == 101) {
                return response()->json('#');
            }
        }

        // re-assign to mother array
        $successArray['receipt'] = $receiptRes;
        $successArray['receiptDetailses'] = $receiptDetailRes;
        $successArray['paymentCashs'] = $paymentCashRes;
        $successArray['paymentCheques'] = $paymentChequeRes;
        $successArray['overpaymentses'] = $overpaymentRes;
        $successArray['overpaymentTransactionses'] = $overpaymentTransactionRes;

        return $successArray;


    }

    public function getBank($id)
    {
        return Banks::find($id);
    }

    public function agingReport(Request $request)
    {
        $log_user = Sentinel::getUser();
        $areas = Area::get();

        $orders = PaymentAgingReport::select(
            'created_date',
            'manual_id',
            'job_no',
            'customer_name',
            'total',
            'invoice_due',
            DB::raw('IFNULL(DATEDIFF(CURDATE(),created_date),0) as no_of_days'),
            'id')
            ->whereRaw('total > 0')->where('invoice_due', '>', 0);

        if ($log_user->id > 1) {
            if (trim($log_user->roles[0]->name) == 'marketer') {
                $marketeerList = Employee::where('id', $log_user->employee_id)->get();
                $customerList = Customer::where('marketeer_id', $log_user->employee_id)->get();
                $orders = $orders->where('marketeer_id', $log_user->employee_id);

            } else {
                $marketeerList = Employee::where('employee_type_id', 2)->get();
                $customerList = Customer::all();
            }
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $customerList = Customer::all();
        }

        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('marketeer') && $request->get('marketeer') > 0) {
            $orders = $orders->where('marketeer_id', $request->get('marketeer'));
        }

        if ($request->get('customer') && $request->get('customer') > 0) {
            $orders = $orders->where('customer', $request->get('customer'));
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('area', $request->get('area'));
        }

        $orders = $orders->groupBy('id');
        $orders = $orders->orderBy('customer_name', 'asc')->orderBy('no_of_days', 'desc');
        $orders = $orders->paginate(10);

        return view('paymentManage::aging.list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'customerList' => $customerList, 'customer' => $request->get('customer'), 'areas' => $areas, 'area' => $request->get('area')]);
    }

    public function download(Request $request)
    {
        /*$orders = Invoice::leftJoin('recipt_detail', 'recipt_detail.invoice_id', '=', 'invoice.id')
            ->leftJoin('invoice_discount', 'invoice_discount.invoice_id', '=', 'invoice.id')
            ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id')
            ->select(DB::raw('CONCAT(remon_customer.f_name,\' \',remon_customer.l_name) as customer_name'),
                'invoice.created_date',
                'invoice.manual_id',
                'invoice.job_no',
                DB::raw('(IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = invoice.id group by invoice.id),0) - ifnull(invoice_discount.discount,0)) as total'),
                DB::raw('if(recipt_detail.id is null,(IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = invoice.id group by invoice.id),0) - ifnull(invoice_discount.discount,0)),(SELECT recipt_detail.invoice_due FROM recipt_detail WHERE recipt_detail.invoice_id = invoice.id ORDER BY recipt_detail.id DESC LIMIT 1)) as invoice_due'),
                DB::raw('datediff(curdate(), invoice.created_date) as no_of_days'),
                'recipt_detail.id')->whereRaw('(recipt_detail.id is null OR invoice_due > 0 )');*/

        $to = date('Y-m-d');
        $date_sql = '';
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $date_sql = " AND DATE(created_date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else {
            $from = 'All';
        }

        $rep_sql = '';
        if ($request->get('marketeer') > 0) {
            $rep_sql = ' AND marketeer_id =' . $request->get('marketeer');
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }
        $cus_all = '';
        $location_sql = '';
        if ($request->get('customer') > 0) {
            $location_sql = ' AND customer =' . $request->get('customer');
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        $are_sql = '';
        if ($request->get('area') && $request->get('area') > 0) {
            $are_sql = ' AND area =' . $request->get('area');;
        }

        $orders_1 = DB::select(
            "SELECT
                          customer_name,
                          couple_name,
                          created_date,
                          manual_id,
                          job_no,
                          total,
                          invoice_due,
                          IFNULL(DATEDIFF(CURDATE(),created_date),0) as no_of_days,
                          id
                        FROM
                            `payment_aging_report`
                        WHERE
                            deleted_at IS NULL AND invoice_due > 0 AND total > 0
                        " . $date_sql . "
                        " . $rep_sql . "
                        " . $location_sql . "
                        " . $are_sql . "
                        GROUP BY
                            id
                        ORDER BY customer_name asc,no_of_days desc"
        );

        if (sizeof($orders_1) > 700) {
            return redirect('payment/aging')->with(['error' => true,
                'error.message' => 'please try again with the filters...!',
                'error.title' => 'Data too long for pdf..!']);
        }

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all];


        if ($orders_1) {
            $page1 = view('paymentManage::print.aging')->with(['orders' => $orders_1, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function excel(Request $request)
    {
        $to = date('Y-m-d');
        $date_sql = '';
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $date_sql = " AND DATE(created_date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else {
            $from = 'All';
        }

        $rep_sql = '';
        if ($request->get('marketeer') > 0) {
            $rep_sql = ' AND marketeer_id =' . $request->get('marketeer');
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }
        $cus_all = '';
        $location_sql = '';
        if ($request->get('customer') > 0) {
            $location_sql = ' AND customer =' . $request->get('customer');
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        $are_sql = '';
        if ($request->get('area') && $request->get('area') > 0) {
            $are_sql = ' AND area =' . $request->get('area');;
        }

        $orders = DB::select(
            "SELECT
                          customer_name,
                          couple_name,
                          created_date,
                          manual_id,
                          job_no,
                          total,
                          invoice_due,
                          IFNULL(DATEDIFF(CURDATE(),created_date),0) as no_of_days,
                          id
                        FROM
                            `payment_aging_report`
                        WHERE
                            deleted_at IS NULL AND invoice_due > 0 AND total > 0
                        " . $date_sql . "
                        " . $rep_sql . "
                        " . $location_sql . "
                        " . $are_sql . "
                        GROUP BY
                            id
                        ORDER BY customer_name asc,no_of_days desc"
        );

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/invoice_aging.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);
                $customer_name = '';
                $inv_total = 0;
                $due_total = 0;
                $first_total = 0;
                $second_total = 0;
                $third_total = 0;
                $fourth_total = 0;
                $all_inv_total = 0;
                $all_due_total = 0;
                $all_first_total = 0;
                $all_second_total = 0;
                $all_third_total = 0;
                $all_fourth_total = 0;
                foreach ($orders as $detail) {
                    if ($index > 1 && $customer_name != $detail->customer_name) {
                        $excel->getActiveSheet()->setCellValue('E' . $tbl_column, 'Total');
                        $excel->getActiveSheet()->getStyle('E' . $tbl_column)->getFont()->setBold(true);
                        $excel->getActiveSheet()->setCellValue('F' . $tbl_column, number_format($inv_total, 2));
                        $excel->getActiveSheet()->getStyle('F' . $tbl_column)->getFont()->setBold(true);
                        $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($due_total, 2));
                        $excel->getActiveSheet()->getStyle('G' . $tbl_column)->getFont()->setBold(true);
                        $excel->getActiveSheet()->setCellValue('I' . $tbl_column, number_format($first_total, 2));
                        $excel->getActiveSheet()->getStyle('I' . $tbl_column)->getFont()->setBold(true);
                        $excel->getActiveSheet()->setCellValue('J' . $tbl_column, number_format($second_total, 2));
                        $excel->getActiveSheet()->getStyle('J' . $tbl_column)->getFont()->setBold(true);
                        $excel->getActiveSheet()->setCellValue('K' . $tbl_column, number_format($third_total, 2));
                        $excel->getActiveSheet()->getStyle('K' . $tbl_column)->getFont()->setBold(true);
                        $excel->getActiveSheet()->setCellValue('L' . $tbl_column, number_format($fourth_total, 2));
                        $excel->getActiveSheet()->getStyle('L' . $tbl_column)->getFont()->setBold(true);
                        $inv_total = 0;
                        $due_total = 0;
                        $first_total = 0;
                        $second_total = 0;
                        $third_total = 0;
                        $fourth_total = 0;
                        $tbl_column++;
                        $index++;
                    }
                    $customer_name = $detail->customer_name;
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->manual_id);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->job_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $detail->couple_name);
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, number_format($detail->total, 2));
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($detail->invoice_due, 2));
                    $excel->getActiveSheet()->setCellValue('H' . $tbl_column, $detail->created_date);
                    $excel->getActiveSheet()->setCellValue('I' . $tbl_column, $detail->no_of_days);

                    if ($detail->no_of_days >= 0 && $detail->no_of_days <= 15) {
                        $excel->getActiveSheet()->setCellValue('J' . $tbl_column, number_format($detail->invoice_due, 2));
                        $first_total += $detail->invoice_due;
                        $all_first_total += $detail->invoice_due;
                    } elseif ($detail->no_of_days > 15 && $detail->no_of_days <= 30) {
                        $excel->getActiveSheet()->setCellValue('K' . $tbl_column, number_format($detail->invoice_due, 2));
                        $second_total += $detail->invoice_due;
                        $all_second_total += $detail->invoice_due;
                    } elseif ($detail->no_of_days > 30 && $detail->no_of_days <= 60) {
                        $excel->getActiveSheet()->setCellValue('L' . $tbl_column, number_format($detail->invoice_due, 2));
                        $third_total += $detail->invoice_due;
                        $all_third_total += $detail->invoice_due;
                    } else {
                        $excel->getActiveSheet()->setCellValue('M' . $tbl_column, number_format($detail->invoice_due, 2));
                        $fourth_total += $detail->invoice_due;
                        $all_fourth_total += $detail->invoice_due;
                    }

                    $inv_total += $detail->total;
                    $due_total += $detail->invoice_due;
                    $all_inv_total += $detail->total;
                    $all_due_total += $detail->invoice_due;
                    $tbl_column++;
                    $index++;
                }

                $excel->getActiveSheet()->setCellValue('E' . $tbl_column, 'Total');
                $excel->getActiveSheet()->getStyle('E' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('F' . $tbl_column, number_format($inv_total, 2));
                $excel->getActiveSheet()->getStyle('F' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($due_total, 2));
                $excel->getActiveSheet()->getStyle('G' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('J' . $tbl_column, number_format($first_total, 2));
                $excel->getActiveSheet()->getStyle('J' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('K' . $tbl_column, number_format($second_total, 2));
                $excel->getActiveSheet()->getStyle('K' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('L' . $tbl_column, number_format($third_total, 2));
                $excel->getActiveSheet()->getStyle('L' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('M' . $tbl_column, number_format($fourth_total, 2));
                $excel->getActiveSheet()->getStyle('M' . $tbl_column)->getFont()->setBold(true);
                $tbl_column++;
                $index++;

                $excel->getActiveSheet()->setCellValue('E' . $tbl_column, 'Grand Total');
                $excel->getActiveSheet()->getStyle('E' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('F' . $tbl_column, number_format($all_inv_total, 2));
                $excel->getActiveSheet()->getStyle('F' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($all_due_total, 2));
                $excel->getActiveSheet()->getStyle('G' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('J' . $tbl_column, number_format($all_first_total, 2));
                $excel->getActiveSheet()->getStyle('J' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('K' . $tbl_column, number_format($all_second_total, 2));
                $excel->getActiveSheet()->getStyle('K' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('L' . $tbl_column, number_format($all_third_total, 2));
                $excel->getActiveSheet()->getStyle('L' . $tbl_column)->getFont()->setBold(true);
                $excel->getActiveSheet()->setCellValue('M' . $tbl_column, number_format($all_fourth_total, 2));
                $excel->getActiveSheet()->getStyle('M' . $tbl_column)->getFont()->setBold(true);

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/aging')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function chequeList(Request $request)
    {
        $customerList = Customer::all();
        $bankList = Banks::all();
        $areas = Area::get();

        $orders = Recipt::with('customer', 'cheque')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->join('payment_cheques', 'payment_cheques.recipt_id', '=', 'recipt.id')
            ->join('banks', 'banks.id', '=', 'payment_cheques.cheque_bank_id')
            ->where('recipt.type', 2);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('(recipt.recipt_no = "' . $request->get('no') . '" OR payment_cheques.cheque_no="' . $request->get('no') . '")');
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('bank')) {
            $orders = $orders->where('payment_cheques.cheque_bank_id', $request->get('bank'));
        }

        if ($request->get('customer')) {
            $orders = $orders->where('recipt.location_id', $request->get('customer'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }


        $orders = $orders->orderBy('rc.f_name', 'ASC');
        $orders = $orders->paginate(20);

        //return ['orders' => $orders,'customerList'=>$customerList, 'customer' => $request->get('customer'),'bankList' =>$bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status'=> $request->get('status')];

        return view('paymentManage::cheque.list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'customerList' => $customerList, 'customer' => $request->get('customer'), 'bankList' => $bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status' => $request->get('status'), 'areas' => $areas, 'area' => $request->get('area')]);
    }

    public function changeStatus(Request $request)
    {
        $cheque = ChequePayment::where('cheque_no', $request->cheque_no)->first();
        if ($cheque) {
            $cheque->status = $request->status;
            $cheque->remark = $request->remark;
            $cheque->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function chequeExcel(Request $request)
    {
        $orders = Recipt::with('customer', 'cheque')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->join('payment_cheques', 'payment_cheques.recipt_id', '=', 'recipt.id')
            ->join('banks', 'banks.id', '=', 'payment_cheques.cheque_bank_id')
            ->where('recipt.type', 2);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('(recipt.recipt_no = "' . $request->get('no') . '" OR payment_cheques.cheque_no="' . $request->get('no') . '")');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('bank')) {
            $orders = $orders->where('payment_cheques.cheque_bank_id', $request->get('bank'));
            $bank = $request->get('bank');
        } else {
            $bank = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
            if ($request->get('status') == 1) {
                $status = 'Pending';
            } else if ($request->get('status') == 2) {
                $status = 'Received';
            } else {
                $status = 'Bounced';
            }
        } else {
            $status = 'All';
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'status' => $status, 'bank' => $bank, 'no' => $no];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/cheque_details.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 6;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {

                    $status = 'Bounced';
                    if ($detail->status == 1) {
                        $status = 'Pending';
                    } elseif ($detail->status == 2) {
                        $status = 'Received';
                    }

                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->cheque_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $detail->name);
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($detail->amount, 2));
                    $excel->getActiveSheet()->setCellValue('H' . $tbl_column, $status);
                    $excel->getActiveSheet()->setCellValue('H' . $tbl_column, $detail->remark);
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/report/excel')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function chequeDownload(Request $request)
    {
        $orders = Recipt::with('customer', 'cheque')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->join('payment_cheques', 'payment_cheques.recipt_id', '=', 'recipt.id')
            ->join('banks', 'banks.id', '=', 'payment_cheques.cheque_bank_id')
            ->where('recipt.type', 2);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('(recipt.recipt_no = "' . $request->get('no') . '" OR payment_cheques.cheque_no="' . $request->get('no') . '")');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('bank')) {
            $orders = $orders->where('payment_cheques.cheque_bank_id', $request->get('bank'));
            $bank = $request->get('bank');
        } else {
            $bank = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
            if ($request->get('status') == 1) {
                $status = 'Pending';
            } else if ($request->get('status') == 2) {
                $status = 'Received';
            } else {
                $status = 'Bounced';
            }
        } else {
            $status = 'All';
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'status' => $status, 'bank' => $bank, 'no' => $no];


        if ($orders) {
            $page1 = view('paymentManage::print.cheque')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function receiptDownload(Request $request)
    {
        $orders = Recipt::with('customer', 'cheque')
            ->join('payment_cheques', 'payment_cheques.recipt_id', '=', 'recipt.id')
            ->join('banks', 'banks.id', '=', 'payment_cheques.cheque_bank_id')
            ->where('type', 2);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('(recipt.recipt_no = "' . $request->get('no') . '" OR payment_cheques.cheque_no="' . $request->get('no') . '")');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('bank')) {
            $orders = $orders->where('payment_cheques.cheque_bank_id', $request->get('bank'));
            $bank = $request->get('bank');
        } else {
            $bank = 'All';
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
            if ($request->get('status') == 1) {
                $status = 'Pending';
            } else if ($request->get('status') == 2) {
                $status = 'Received';
            } else {
                $status = 'Bounced';
            }
        } else {
            $status = 'All';
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');
        $orders = $orders->orderBy('f_name', 'asc')->orderBy('no_of_days', 'desc');*/
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'status' => $status, 'bank' => $bank, 'no' => $no];


        if ($orders) {
            $page1 = view('paymentManage::print.cheque')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function receiptList(Request $request)
    {

        $users = User::with('employee_')->get();
        $areas = Area::get();
        $cashierList = array();
        foreach ($users as $user) {
            $user_ = Sentinel::findById($user->id);
            if ($user_->hasAccess(['invoice.add'])) {
                array_push($cashierList, $user);
            }
        }

        $log_user = Sentinel::getUser();
        $customerList = Customer::all();
        if ($log_user->id > 1) {
            if (trim($log_user->roles[0]->name) == 'marketer') {
                $marketeerList = Employee::where('id', $log_user->employee_id)->get();
            } else {
                $marketeerList = Employee::where('employee_type_id', 2)->get();
            }
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
        }
        $bankList = Banks::all();

        $orders = Recipt::join('remon_customer as cus', 'cus.id', '=', 'recipt.location_id')->select('recipt.id', 'recipt_date', 'location_id', 'amount', 'recipt_no')->with('customer')->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id');

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('recipt_no = "' . $request->get('no') . '"');
        }

        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/

        if ($request->get('cashier') && $request->get('cashier') > 0) {
            $orders = $orders->whereRaw('recipt.user_id =' . $request->get('cashier'));
        }

        if ($request->get('marketeer') && $request->get('marketeer') > 0) {
            $orders = $orders->whereRaw('cus.marketeer_id =' . $request->get('marketeer'));
        }

        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
        }
        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('cus.area', $request->get('area'));
        }


        $orders = $orders->orderBy('rc.f_name', 'ASC');
        $orders = $orders->paginate(20);

        return view('paymentManage::recipt.report-list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'customerList' => $customerList, 'customer' => $request->get('customer'), 'bankList' => $bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status' => $request->get('status'), 'cashierList' => $cashierList, 'cashier' => $request->get('cashier'), 'areas' => $areas, 'area' => $request->get('area')]);
    }


    public function receiptListDownload(Request $request)
    {
        $orders = Recipt::with('customer')->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id');

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('recipt_no = "' . $request->get('no') . '"');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        $marketeer = '';
        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('rc.marketeer_id =' . $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'marketeer' => $marketeer];


        if ($orders) {
            $page1 = view('paymentManage::print.receipt-list')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function receiptListExcel(Request $request)
    {
        $orders = Recipt::with('customer')->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id');

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('recipt_no = "' . $request->get('no') . '"');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        $marketeer = '';
        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('rc.marketeer_id =' . $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'marketeer' => $marketeer];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/receipt_details.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, number_format($detail->amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/receipt/list/excel')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function paymentList(Request $request)
    {

        $users = User::with('employee_')->get();
        $areas = Area::get();
        $cashierList = array();
        foreach ($users as $user) {
            $user_ = Sentinel::findById($user->id);
            if ($user_->hasAccess(['invoice.add'])) {
                array_push($cashierList, $user);
            }
        }

        $log_user = Sentinel::getUser();
        $customerList = Customer::all();
        if ($log_user->id > 1) {
            if (trim($log_user->roles[0]->name) == 'marketer') {
                $marketeerList = Employee::where('id', $log_user->employee_id)->get();
            } else {
                $marketeerList = Employee::where('employee_type_id', 2)->get();
            }
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
        }
        $bankList = Banks::all();

        $orders = Recipt::join('remon_customer as cus', 'cus.id', '=', 'recipt.location_id')
            ->select('recipt.id', 'recipt_date', 'location_id', 'amount', 'recipt_no')
            ->with('customer')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->whereNotIn('recipt.type',[3,4,5,8,9]);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('recipt_no = "' . $request->get('no') . '"');
        }

        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/

        if ($request->get('cashier') && $request->get('cashier') > 0) {
            $orders = $orders->whereRaw('recipt.user_id =' . $request->get('cashier'));
        }

        if ($request->get('marketeer') && $request->get('marketeer') > 0) {
            $orders = $orders->whereRaw('cus.marketeer_id =' . $request->get('marketeer'));
        }

        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
        }
        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('cus.area', $request->get('area'));
        }


        $orders = $orders->orderBy('rc.f_name', 'ASC');
        $orders = $orders->paginate(20);

        return view('paymentManage::recipt.report-payment', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'customerList' => $customerList, 'customer' => $request->get('customer'), 'bankList' => $bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status' => $request->get('status'), 'cashierList' => $cashierList, 'cashier' => $request->get('cashier'), 'areas' => $areas, 'area' => $request->get('area')]);
    }

    public function paymentListDownload(Request $request)
    {
        $orders = Recipt::with('customer')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->whereNotIn('recipt.type',[3,4,5,8,9]);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('recipt_no = "' . $request->get('no') . '"');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        $marketeer = '';
        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('rc.marketeer_id =' . $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'marketeer' => $marketeer];


        if ($orders) {
            $page1 = view('paymentManage::print.receipt-list')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function paymentListExcel(Request $request)
    {
        $orders = Recipt::with('customer')->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->whereNotIn('recipt.type',[3,4,5,8,9]);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('recipt_no = "' . $request->get('no') . '"');
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        $marketeer = '';
        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('rc.marketeer_id =' . $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'marketeer' => $marketeer];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/receipt_details.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, number_format($detail->amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/receipt/list/excel')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }


    public function monthly_collection(Request $request)
    {

        //return $request;

        $users = User::with('employee_')->get();
        $cashierList = array();
        foreach ($users as $user) {
            $user_ = Sentinel::findById($user->id);
            if ($user_->hasAccess(['invoice.add'])) {
                array_push($cashierList, $user);
            }
        }

        //return $cashierList;

        $log_user = Sentinel::getUser();
        if ($log_user->id > 1) {
            if (trim($log_user->roles[0]->name) == 'marketer') {
                $marketeerList = Employee::where('id', $log_user->employee_id)->get();
            } else {
                $marketeerList = Employee::where('employee_type_id', 2)->get();
            }
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
        }
        $bankList = Banks::all();

        $orders = Recipt::join('remon_customer as cus', 'cus.id', '=', 'recipt.location_id')
            ->join('payment_cheques as pac', 'pac.recipt_id', '=', 'recipt.id')
            ->select('recipt.id', 'recipt_date', 'location_id', 'amount', 'recipt_no')->with('customer');


        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            $orders = $orders->whereRaw("IF(recipt.type = 2,date_format(pac.cheque_date, '%Y-%m') = '" . $from . "',date_format(recipt_date, '%Y-%m') = '" . $from . "')");
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/

        if ($request->get('filter-checkbox') != 'on') {
            if ($request->get('marketeer') && $request->get('marketeer') > 0) {
                $orders = $orders->whereRaw('cus.marketeer_id =' . $request->get('marketeer'));
            }
        } else {
            if ($request->get('cashier') && $request->get('cashier') > 0) {
                $orders = $orders->whereRaw('recipt.user_id =' . $request->get('cashier'));
            }
        }

        $orders = $orders->orderBy('cus.f_name', 'ASC');
        $orders = $orders->paginate(20);

        return view('paymentManage::recipt.monthly-collection', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'bankList' => $bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'status' => $request->get('status'), 'cashierList' => $cashierList, 'cashier' => $request->get('cashier'), 'filter_checkbox' => $request->get('filter-checkbox')]);
    }

    public function monthlyDownload(Request $request)
    {
        $orders = Recipt::join('remon_customer as cus', 'cus.id', '=', 'recipt.location_id')
            ->join('payment_cheques as pac', 'pac.recipt_id', '=', 'recipt.id')
            ->select('recipt.id', 'recipt_date', 'location_id', 'amount', 'recipt_no')->with('customer');


        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            $orders = $orders->whereRaw("IF(recipt.type = 2,date_format(pac.cheque_date, '%Y-%m') = '" . $from . "',date_format(recipt_date, '%Y-%m') = '" . $from . "')");
        } else {
            $from = '-';
        }

        $marketeer = '-';
        $cashier = '-';
        if ($request->get('filter_checkbox') != 'on') {
            if ($request->get('marketeer') && $request->get('marketeer') > 0) {
                $orders = $orders->whereRaw('cus.marketeer_id =' . $request->get('marketeer'));
                $marketeer = Employee::find($request->get('marketeer'));
                $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
            } else {
                $marketeer = 'All';
            }
        } else {

            if ($request->get('cashier') && $request->get('cashier') > 0) {
                $orders = $orders->whereRaw('user_id =' . $request->get('cashier'));
                $cashier = Employee::find($request->get('cashier'));
                $cashier = $cashier->first_name . ' ' . $cashier->last_name;
            } else {
                $cashier = 'All';
            }
        }


        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('cus.f_name', 'asc');
        $orders = $orders->get();

        $header = ['from' => $from, 'aging_date' => date('Y-m-d'), 'marketeer' => $marketeer, 'cashier' => $cashier];


        if ($orders) {
            $page1 = view('paymentManage::print.monthly')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function monthlyExcel(Request $request)
    {
        $orders = Recipt::join('remon_customer as cus', 'cus.id', '=', 'recipt.location_id')
            ->join('payment_cheques as pac', 'pac.recipt_id', '=', 'recipt.id')
            ->select('recipt.id', 'recipt_date', 'location_id', 'amount', 'recipt_no')->with('customer');


        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            $orders = $orders->whereRaw("IF(recipt.type = 2,date_format(pac.cheque_date, '%Y-%m') = '" . $from . "',date_format(recipt_date, '%Y-%m') = '" . $from . "')");
        } else {
            $from = '-';
        }

        $marketeer = '-';
        $cashier = '-';
        if ($request->get('filter_checkbox') != 'on') {
            if ($request->get('marketeer') && $request->get('marketeer') > 0) {
                $orders = $orders->whereRaw('cus.marketeer_id =' . $request->get('marketeer'));
                $marketeer = Employee::find($request->get('marketeer'));
                $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
            } else {
                $marketeer = 'All';
            }
        } else {

            if ($request->get('cashier') && $request->get('cashier') > 0) {
                $orders = $orders->whereRaw('user_id =' . $request->get('cashier'));
                $cashier = Employee::find($request->get('cashier'));
                $cashier = $cashier->first_name . ' ' . $cashier->last_name;
            } else {
                $cashier = 'All';
            }
        }


        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('cus.f_name', 'asc');
        $orders = $orders->get();

        $header = ['from' => $from, 'aging_date' => date('Y-m-d'), 'marketeer' => $marketeer, 'cashier' => $cashier];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/monthly_collection.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 6;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['cashier']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, number_format($detail->amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/report/monthly')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function cashPaymentList(Request $request)
    {
        // return $request;
        $customerList = Customer::all();
        $areas = Area::get();

        $orders = Recipt::with('customer')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 1);
        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("recipt.recipt_no LIKE '%" . $request->get('no') . "%'");
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }


        if ($request->get('customer')) {
            $orders = $orders->where('recipt.location_id', $request->get('customer'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }


        $orders = $orders->orderBy('rc.f_name', 'ASC');
        $orders = $orders->paginate(20);

        //return ['orders' => $orders,'customerList'=>$customerList, 'customer' => $request->get('customer'),'bankList' =>$bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status'=> $request->get('status')];

        return view('paymentManage::cash.paymentList', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'customerList' => $customerList, 'customer' => $request->get('customer'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status' => $request->get('status'), 'areas' => $areas, 'area' => $request->get('area')]);
    }

    public function cashPaymentDownload(Request $request)
    {
        $orders = Recipt::with('customer')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 1);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('(recipt.recipt_no = "' . $request->get('no'));
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        if ($request->get('bank')) {
            $orders = $orders->where('payment_cheques.cheque_bank_id', $request->get('bank'));
            $bank = $request->get('bank');
        } else {
            $bank = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
            if ($request->get('status') == 1) {
                $status = 'Pending';
            } else if ($request->get('status') == 2) {
                $status = 'Received';
            } else {
                $status = 'Bounced';
            }
        } else {
            $status = 'All';
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'status' => $status, 'bank' => $bank, 'no' => $no];


        if ($orders) {
            $page1 = view('paymentManage::print.cashPayment')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function cashPaymentExcel(Request $request)
    {
        $orders = Recipt::with('customer')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 1);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw('(recipt.recipt_no = "' . $request->get('no'));
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        if ($request->get('bank')) {
            $orders = $orders->where('payment_cheques.cheque_bank_id', $request->get('bank'));
            $bank = $request->get('bank');
        } else {
            $bank = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
            if ($request->get('status') == 1) {
                $status = 'Pending';
            } else if ($request->get('status') == 2) {
                $status = 'Received';
            } else {
                $status = 'Bounced';
            }
        } else {
            $status = 'All';
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'status' => $status, 'bank' => $bank, 'no' => $no];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/cash_payment.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 6;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, number_format($detail->amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/report/cashPayment/list')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    /* Online payments */
    public function onlinePaymentList(Request $request)
    {
        // return $request;
        $customerList = Customer::all();
        $AccountList = Account::with('banks')->get();
        $areas = Area::get();

        $orders = Recipt::with('customer')
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 6);
        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("recipt.recipt_no LIKE '%" . $request->get('no') . "%'");
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }


        if ($request->get('customer')) {
            $orders = $orders->where('recipt.location_id', $request->get('customer'));
        }

        if ($request->get('account')) {
            $orders = $orders->where('recipt.account_id', $request->get('account'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('payment_cheques.status', $request->get('status'));
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }


        $orders = $orders->orderBy('rc.f_name', 'ASC');
        $orders = $orders->paginate(20);

        //return ['orders' => $orders,'customerList'=>$customerList, 'customer' => $request->get('customer'),'bankList' =>$bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status'=> $request->get('status')];

        return view('paymentManage::online.onlinePaymentList', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'customerList' => $customerList, 'customer' => $request->get('customer'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status' => $request->get('status'), 'areas' => $areas, 'area' => $request->get('area'), 'AccountList' => $AccountList, 'account' => $request->get('account')]);
    }

    public function onlinePaymentDownload(Request $request)
    {
        $orders = Recipt::with(['customer', 'account.banks'])
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 6);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(recipt.recipt_no LIKE '%" . $request->get('no') . "%') ");
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('account')) {
            $orders = $orders->where('recipt.account_id', $request->get('account'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        //    $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];


        if ($orders) {
            $page1 = view('paymentManage::print.onlinePayment')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function onlinePaymentExcel(Request $request)
    {
        $orders = Recipt::with(['customer', 'account.banks'])
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 6);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(recipt.recipt_no LIKE '%" . $request->get('no') . "%') ");
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        if ($request->get('account')) {
            $orders = $orders->where('recipt.account_id', $request->get('account'));
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }


        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        //    $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/online_payment.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 6;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $detail->account ? $detail->account->account_no : '');
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $detail->account ? $detail->account->banks->name . ' - ' . $detail->account->branch : '');
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($detail->amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/report/onlinePayment/list')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    /* Cash Deposit */

    public function cashDepositList(Request $request)
    {
        // return $request;
        $customerList = Customer::all();
        $AccountList = Account::with('banks')->get();
        $areas = Area::get();

        $orders = Recipt::with(['customer', 'account.banks'])
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 7);
        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(recipt.recipt_no LIKE '%" . $request->get('no') . "%') ");
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        }
        if ($request->get('account')) {
            $orders = $orders->where('recipt.account_id', $request->get('account'));
        }


        if ($request->get('customer')) {
            $orders = $orders->where('recipt.location_id', $request->get('customer'));
        }


        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }


        $orders = $orders->orderBy('rc.f_name', 'ASC');
        $orders = $orders->paginate(20);

        //return ['orders' => $orders,'customerList'=>$customerList, 'customer' => $request->get('customer'),'bankList' =>$bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status'=> $request->get('status')];

        return view('paymentManage::cash.depositList', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'customerList' => $customerList, 'customer' => $request->get('customer'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'areas' => $areas, 'area' => $request->get('area'), 'AccountList' => $AccountList, 'account' => $request->get('account')]);
    }

    public function cashDepositDownload(Request $request)
    {
        $orders = Recipt::with(['customer', 'account.banks'])
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 7);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(recipt.recipt_no LIKE '%" . $request->get('no') . "%') ");
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }


        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('account')) {
            $orders = $orders->where('recipt.account_id', $request->get('account'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];


        if ($orders) {
            $page1 = view('paymentManage::print.cashDeposit')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function cashDepositExcel(Request $request)
    {
        $orders = Recipt::with(['customer', 'account.banks'])
            ->join('remon_customer as rc', 'rc.id', '=', 'recipt.location_id')
            ->where('recipt.type', 7);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(recipt.recipt_no LIKE '%" . $request->get('no') . "%') ");
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(recipt.recipt_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }


        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->where('rc.area', $request->get('area'));
        }

        if ($request->get('account')) {
            $orders = $orders->where('recipt.account_id', $request->get('account'));
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('location_id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('rc.f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/cash_deposit.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 6;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->recipt_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->recipt_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $detail->account ? $detail->account->account_no : '');
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $detail->account ? $detail->account->banks->name . ' - ' . $detail->account->branch : '');
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, number_format($detail->amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/report/cashDeposit/list')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function creditNoteList(Request $request)
    {
        // return $request;
        $customerList = Customer::all();
        $AccountList = Account::with('banks')->get();
        $areas = Area::get();

        $orders = CreditNote::with(['invoice.customer','create_user']);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(credit_note.invoice_no LIKE '%" . $request->get('no') . "%') ");
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(credit_note.created_at) BETWEEN '" . $from . "' AND '" . $to . "'");
        }

        if ($request->get('customer')) {
            $orders = $orders->whereHas('invoice.customer',function ($q) use($request){
                $q->where('id',$request->get('customer'));
            });
        }


        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->whereHas('invoice.customer',function ($q) use($request){
                $q->where('area',$request->get('area'));
            });
        }


        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->paginate(20);

        //return ['orders' => $orders,'customerList'=>$customerList, 'customer' => $request->get('customer'),'bankList' =>$bankList, 'bank' => $request->get('bank'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'status'=> $request->get('status')];

        return view('paymentManage::credit_note.list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'customerList' => $customerList, 'customer' => $request->get('customer'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'no' => $request->get('no'), 'areas' => $areas, 'area' => $request->get('area'), 'AccountList' => $AccountList, 'account' => $request->get('account')]);
    }


    public function creditNoteDownload(Request $request)
    {
        $orders = CreditNote::with(['invoice.customer','create_user']);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(credit_note.invoice_no LIKE '%" . $request->get('no') . "%') ");
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(credit_note.created_at) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->whereHas('invoice.customer',function ($q) use($request){
                $q->where('area',$request->get('area'));
            });
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->whereHas('invoice.customer',function ($q) use($request){
                $q->where('id',$request->get('customer'));
            });
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];


        if ($orders) {
            $page1 = view('paymentManage::print.credit_note')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

//        $data = ['no' => $receipt->recipt_no];

        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function creditNoteExcel(Request $request)
    {
        $orders = CreditNote::with(['invoice.customer','create_user']);

        if (strlen($request->get('no')) > 0) {
            $orders = $orders->whereRaw("(credit_note.invoice_no LIKE '%" . $request->get('no') . "%') ");
            $no = $request->get('no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(credit_note.created_at) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        if ($request->get('area') && $request->get('area') > 0) {
            $orders = $orders->whereHas('invoice.customer',function ($q) use($request){
                $q->where('area',$request->get('area'));
            });
        }

        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->whereHas('invoice.customer',function ($q) use($request){
                $q->where('id',$request->get('customer'));
            });
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];


        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/credit_note.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 6;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->invoice->customer->f_name . ' ' . $detail->invoice->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->created_at);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->invoice_no);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, number_format($detail->credit_amount, 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('payment/report/credit-note/list')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }
}