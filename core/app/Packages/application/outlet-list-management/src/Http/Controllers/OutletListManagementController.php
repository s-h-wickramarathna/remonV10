<?php
/**
 * Created by Tharindu
 * Craft from mac.
 * Date: 05/19/2016
 * Time: 4:40 PM
 */

namespace Application\OutletListManagement\Http\Controllers;


use App\Classes\Common;
use App\Classes\PdfTemplate;
use App\Http\Controllers\Controller;
use Application\EmployeeManage\Models\Employee;
use Application\LocationManage\Models\EmployeeLocationDetails;
use Application\LocationManage\Models\Location;
use Core\UserManage\Models\User;
use Illuminate\Http\Request;

use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


use Application\OutletListManagement\Models\Outlet;
use Application\InvoiceManage\Models\Invoice;

class OutletListManagementController extends Controller
{

    /**
     * Show the outlet list view.
     *
     * @return Response
     */
    public function listView()
    {
        $user = Sentinel::getUser();
        if ($user->roles[0]->id == 2) {
            $reps[] = $user;
        } else {
            $reps = Employee::where('employee_type_id', 2)->get();
        }

        return view('outletListManagementViews::outlet.list')->with(['reps' => $reps]);
    }

    /**
     * Show the outlet details.
     *
     * @return Response
     */
    public function detailView(Request $request)
    {
        $outlet = Outlet::find($request->id);
//         $location_id = DB::table('4ever_outlet')->where('id', $request->id);
        $location_id = DB::table('4ever_outlet')->where('id', $request->id)->value('4ever_location_id as id');
        $isNewPayament = Permission::whereIn('name', ['payment.new', 'admin'])->where('status', '=', 1)->pluck('name');
        $outlet_invoice_details = [];

        $sql_inv_det = 'select count(tmp.id) as count,sum(tmp.outstanding) as outstanding  from (
                          select 
                            4ever_invoice.id,
                            (IFNULL(total,0) - (select IFNULL(sum(discount),0) from 4ever_invoice_discount where invoice_id=4ever_invoice.id)) as outstanding
                          from 4ever_invoice  
                          where location_id=' . $outlet["4ever_location_id"] . '
                          )tmp';

        $outlet_invoice_details = DB::select($sql_inv_det);

        $sql_remain = 'select count(tmp.id) as count,sum(tmp.remain) as remain from (
                          select 
                            4ever_invoice.id,
                            total,
                            (IFNULL(total,0) - (select IFNULL(sum(discount),0) from 4ever_invoice_discount where invoice_id=4ever_invoice.id))-IFNULL((select sum(payment_amount) from 4ever_recipt_detail where invoice_id=4ever_invoice.id),0) as remain
                          from 4ever_invoice  
                          where location_id=' . $outlet["4ever_location_id"] . '
                          )tmp where tmp.remain > 0';

        $outlet_inv_remain_details = DB::select($sql_remain);


        if ($outlet) {
            return view('outletListManagementViews::outlet.details')->with([
                'outlet' => $outlet,
                'outlet_invoice_details' => $outlet_invoice_details[0],
                'outlet_inv_remain_details' => $outlet_inv_remain_details[0],
                'isNewPayament' => $isNewPayament,
                'location_id' => $location_id
            ]);
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

        $customer_array = array();

//        if ($request->ajax()) {
        $user = Sentinel::getUser();
        $userId = $request->get('dis_id');

        $data = DB::select("select
                              c.id,c.f_name,c.l_name,c.address,c.nic,
                              c.mobile,c.telephone,c.email,c.credit_limit,c.credit_period,c.is_credit_limit_block,
                              c.`status`,c.`type`,marketeer_id,CONCAT(e.first_name,' ',e.last_name) as marketeer_name
                              from remon_customer AS c left join employee as e ON c.marketeer_id = e.id WHERE c.marketeer_id =" . $userId);

        $jsonList = array();
        $i = 1;
        //return $data;
        foreach ($data as $key => $customers) {

            $dd = array();
            array_push($dd, $customers->id);
            array_push($dd, $customers->f_name . ' ' . $customers->l_name);
            array_push($dd, $customers->address . '<br/>' . $customers->email);
            array_push($dd, $customers->mobile);
            /*array_push($dd, $customers->telephone);*/
            array_push($dd, $customers->marketeer_name);


            array_push($dd, $customers->credit_limit);

            array_push($dd, $customers->credit_period);

            array_push($jsonList, $dd);
            $i++;
        }
        return Response::json(array('data' => $jsonList));
        /* } else {
             return Response::json(array('data' => []));
         }*/
    }

    public function download(Request $request)
    {


        $userId = $request->get('marketeer');

        $data = DB::select("select
                              c.id,c.f_name,c.l_name,c.address,c.nic,
                              c.mobile,c.telephone,c.email,c.credit_limit,c.credit_period,c.is_credit_limit_block,
                              c.`status`,c.`type`,marketeer_id,CONCAT(e.first_name,' ',e.last_name) as marketeer_name
                              from remon_customer AS c left join employee as e ON c.marketeer_id = e.id WHERE c.marketeer_id =" . $userId);

        $emp = Employee::find($userId);

        $header = ['marketeer' => $emp->first_name . ' ' . $emp->last_name, 'date' => date('Y-m-d')];


        if($request->get('submit') == 1) {
            if ($data) {
                $page1 = view('outletListManagementViews::outlet.print')->with(['data' => $data, 'page_header' => $header]);
            } else {
                return response()->view("errors.404");
            }

            $pdf = new PdfTemplate();
            $pdf->SetMargins(28.35 / $pdf->k, 10);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetAutoPageBreak(TRUE, 20);
            $pdf->AddPage();
            $pdf->writeHtml($page1);
            $pdf->output("recipt.pdf", 'I');
        }elseif($request->get('submit') == 2){
            if (sizeof($data) > 0) {
                $now = new \DateTime('NOW');
                $date = $now->format('Y-m-d_H-i-s');//
                $fileName = $date . '_sheet';
                Excel::load(storage_path('xls/template/customer_list.xls'), function ($excel) use ($data, $header) {
                    $tbl_column = 5;
                    $index = 1;
                    $excel->getActiveSheet()->setCellValue('B' . 2, $header['marketeer']);
                    $excel->getActiveSheet()->setCellValue('D' . 2, $header['date']);

                    foreach ($data as $detail) {
                        $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                        $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->f_name . ' ' . $detail->l_name);
                        $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->address);
                        $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->email);
                        $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $detail->mobile);
                        $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $detail->marketeer_name);
                        $excel->getActiveSheet()->setCellValue('G' . $tbl_column, $detail->credit_limit);
                        $excel->getActiveSheet()->setCellValue('H' . $tbl_column, $detail->credit_period);
                        $tbl_column++;
                        $index++;
                    }

                })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
            } else {
                return redirect('payment/report/monthly')->with(['error' => true,
                    'error.message' => 'Same thing went wrong...!',
                    'error.title' => 'Failed..!']);
            }
        }

        return redirect()->back();
    }


    /**
     * get the outlets.
     *
     * @return Response with pagination
     */
    public function getOutletInvoices(Request $request)
    {

        $sql = 'select 
                    4ever_invoice.id,
                    4ever_invoice.manual_id,
                    4ever_invoice.created_date,
                    (IFNULL(total,0) - (select IFNULL(sum(discount),0) from 4ever_invoice_discount where invoice_id=4ever_invoice.id)) as total,
                    (IFNULL(total,0) - (select IFNULL(sum(discount),0) from 4ever_invoice_discount where invoice_id=4ever_invoice.id))-IFNULL((select sum(payment_amount) from 4ever_recipt_detail where invoice_id=4ever_invoice.id),0) as remain
                from 4ever_invoice  
                where location_id=' . $request->location_id;


        $aa = DB::select($sql);
        $data = array();

        foreach ($aa as $key => $value) {
            $row = array();
            array_push($row, $value->id);
            array_push($row, $value->manual_id);
            array_push($row, $value->created_date);
            array_push($row, 'Rs.' . $value->total);
            if ($value->remain == 0) {
                array_push($row, '<span class="badge">PAID</span>');
            } else {
                array_push($row, 'Rs.' . $value->remain);
            }
            array_push($data, $row);

        }

        return Response::json(["data" => $data]);
    }

    public function changeOutlatLocation(Request $request)
    {
        $result = Outlet::where('id', $request->input('id'))->update([
            'gps_latitude' => $request->input('latitude'),
            'gps_longitude' => $request->input('longitude')
        ]);

        if ($result) {
            return Response::json(['success' => true]);
        } else {
            return Response::json(['success' => false]);
        }
    }
}