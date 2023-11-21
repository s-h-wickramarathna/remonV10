<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\Configuration\Http\Controllers;


use App\Http\Controllers\Controller;
use Core\UserManage\Models\User;
use Illuminate\Http\Request;

use Application\Configuration\Http\Requests\ConfigurationRequest;
use Application\Configuration\Models\Configuration;
use Application\Product\Models\Product;
use Application\ProductCategory\Models\ProductCategory;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\DB;
use Application\EmployeeManage\Models\Employee;
use Application\EmployeeManage\Models\Rep;
use Application\WebService\Models\Tracking;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;


class ConfigurationController extends Controller
{

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function addView()
    {
        $product_list = Product::select('id', DB::raw('CONCAT( "[",short_code,"] ", product_name) AS name'))->orderBy('id')->pluck('name', 'id');
        $product_list->prepend('Select Product', 0);
        $product = Product::all();
        return view('batchPriceViews::batchPrice.add')->with(['product' => $product, 'product_list' => $product_list]);
    }

    /**
     * add new employee type data to database
     * @param $request
     * @return Redirect to type add
     */
    public function add(Request $request)
    {
//        $raw_count = $request->raw_count;
//        for ($i = 1 ; $i <= $raw_count ; $i++) {
//            if(($request['pr_price_'.$i] != NULL) && ($request['pr_price_'.$i] != "") && ($request['pr_name_'.$i] != 0)) {
//                DB::table('4ever_batch')
//                    ->where('product_id', $request['pr_name_'.$i])->where('status', 1)
//                    ->update(['status' => 0]);
//
//                BatchPrice::create([
//                    'product_id' => $request['pr_name_'.$i],
//                    'mrp'        => $request['pr_price_'.$i],
//                    'status'     => 1
//                ]);
//            }
//        }
//      return redirect( 'grn/add' )->with([ 'success' => true,
//            'success.message'=> 'Batch added successfully!',
//            'success.title' => 'Well Done!' ]);
    }

    /**
     * Show the menu list screen to the user.
     *
     * @return Response
     */
    public function listView()
    {
//        $ProductCategory = ProductCategory::all()->pluck( 'category_name' , 'id' );
//        $ProductCategory->prepend('All Product Category', 0);
        return view('configurationViews::configuration.list')->with(['ProductCategory' => []]);
    }

    /**
     * Show the employee list data to the user.
     *
     * @return Response
     */
    public function jsonList(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select(
                "SELECT
                    wp.id,
                    CONCAT(we.first_name, ' ', we.last_name) AS rep_name,
                    wp.mobile_user_name,
                    wp.login_status,
                    (SELECT CONCAT(emp.first_name, ' ', emp.last_name) AS distributor FROM 4ever_employee as emp WHERE emp.id = we.parent LIMIT 1) as distributor
                FROM
                    4ever_rep wp
                        INNER JOIN
                    4ever_employee we ON wp.employee_id = we.id
                WHERE
                    wp.active_status = 1
                        AND wp.login_status = 1");
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $rep) {
                $dd = array();
                array_push($dd, $rep->id);
                array_push($dd, $rep->rep_name);
                array_push($dd, $rep->mobile_user_name);
                array_push($dd, $rep->distributor);
                if ($rep->login_status == 1) {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="' . $rep->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                } else {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="' . $rep->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                }
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    /**
     * Activate or Deactivate Employee
     * @param  Request $request employee id with status to change
     * @return Json            json object with status of success or failure
     */
    public function status(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $status = $request->status;

            if ($status == 1) {
                DB::table('4ever_rep')
                    ->where('id', $id)
                    ->update(['login_status' => 0]);
            } else {
                DB::table('4ever_rep')
                    ->where('id', $id)
                    ->update(['login_status' => 1]);
            }
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }


    /**
     * Show the menu edit screen to the user.
     * @param type id
     * @return Response
     */
    public function editView($id)
    {
        $priceArray = BatchPrice::find($id);
        $price = $priceArray->mrp;
        return view('batchPriceViews::batchPrice.edit')->with(['price' => $price]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function edit(Request $request, $id)
    {
        $price = $request['mrp'];
        $batch = BatchPrice::find($id);
        DB::table('4ever_batch')
            ->where('id', $id)
            ->update(['mrp' => $price]);
        return redirect('batchPrice/list')->with(['success' => true,
            'success.message' => 'Batch Price updated successfully!',
            'success.title' => 'Good Job!']);
    }

    /**
     * Show the prices of products.
     *
     * @return Response
     */
    public function getPrice(Request $request)
    {
        $productId = $request->productId;
        if ($productId != 0) {
            $maker = Station::where('district_id', '=', $districtId)->get();
        } else {
            $maker = Station::all();
        }
        return Response::json(array('data' => $maker));
    }

    /**
     *Show the rep list
     */

    public function repListView()
    {
        $date = date("Y-m-d h:i:s");
        $currentDate = strtotime($date);
        $futureDate = $currentDate-(60*5);
        $formatDate = date("Y-m-d H:i:s", $futureDate);

        //select * from where date < $date

        $reps   = DB::select("
            SELECT
                rp.id AS rep_id,
                emp.id AS emp_id,
                CONCAT(
                    emp.first_name,
                    emp.last_name,
                    ' '
                ) AS `name`,
                IFNULL(rp.mobile_user_name, '-') AS user_name,
                rp.login_status,
                rp.lock_status,
                IFNULL((
                    SELECT
                        st.syncTime
                    FROM
                        4ever_sync_time AS st
                    WHERE
                        emp.id = st.user_id
                    AND emp.employee_type_id = 5
                    ORDER BY
                        syncTime DESC
                    LIMIT 1
                ), '-') AS syncTime
            FROM
                4ever_rep AS rp
            INNER JOIN 4ever.4ever_employee AS emp ON rp.employee_id = emp.id
            WHERE
                emp.employee_type_id = 5
            GROUP BY
                rp.mobile_user_name
        ");

        $active_users = Tracking::whereBetween('created_at', array($formatDate, $date))->toSql();

        $table  = "";

        $table .= "<table class='table table-bordered bordered table-striped table-condensed datatable'>
            <thead style='background-color:rgba(52, 73, 94,0.5);color:#fff;'>
                <tr>
                    <th class='text-center' width='4%'>#</th>
                    <th class='text-center' width='6%'>Rep ID</th>
                    <th class='text-center' style='font-weight:normal;'>Rep Name</th>
                    <th class='text-center' style='font-weight:normal;'>User Name</th>
                    <th class='text-center' style='font-weight:normal;'>User active</th>
                    <th class='text-center' style='font-weight:normal;'>Login status</th>
                    <th class='text-center' style='font-weight:normal;'>Lock status</th>
                    <th class='text-center' style='font-weight:normal;'>Last Sync time</th>
                    <th class='text-center' width='10%' style='font-weight:normal;'>Battery level</th>
                    <td class='text-center'>Action</td>
                </tr>
            </thead>";
            if(isset($reps))
            {
                $table .= "<tbody>";

                $markers        = 0;
                $latitude       = array();
                $longitude      = array();
                $name           = array();
                $onlineStatus   = '';
                $nameArray      = array();
                $data           = array();
                $onlineUsers    = array();
                $userIds        = array();
                $contentString  = "";

                foreach($reps as $key => $rep)
                {
                    /*array_push($nameArray, $rep->name);*/

                    $gps = DB::select("
                        SELECT
                            gps.id,
                            gps.rep_id,
                            gps.lat,
                            gps.lon,
                            gps.active_status,
                            gps.battery,
                            gps.created_at,
                            IFNULL(rp.lock_status, 0) AS lock_status,
                            IFNULL(rp.login_status, 0) AS login_status,
                            IFNULL(rp.mobile_user_name, '-') AS username,
                            IFNULL(rp.short_code, '-') AS short_code,
                            (
                                SELECT
                                    snc.syncTime
                                FROM
                                    4ever_sync_time AS snc
                                WHERE
                                    snc.user_id = $rep->emp_id
                                ORDER BY
                                    id DESC
                                LIMIT 1
                            ) AS syncTime,

                        IF (
                            (
                                SELECT
                                    gps.rep_id
									FROM 4ever_gps.gps_info
                                WHERE
                                    gps.rep_id = $rep->emp_id
                                AND gps.created_at BETWEEN '".$formatDate."'
                                AND '".$date."'
                            ),
                            1,
                            0
                        ) AS online,
                         IFNULL(
                            (
                                SELECT
                                    COUNT(rep_id)
                                FROM
                                    `4ever_sales_order`
                                WHERE
                                    rep_id = $rep->emp_id
                            ),
                            0
                        ) AS order_count,
                         IFNULL(
                            (
                                SELECT
                                    sum(total)
                                FROM
                                    `4ever_sales_order`
                                WHERE
                                    rep_id = $rep->emp_id
                            ),
                            0
                        ) AS total_amount
                        FROM
                            4ever_gps.gps_info AS gps 
                        LEFT JOIN 4ever_rep AS rp ON gps.rep_id = rp.id
                        WHERE
                            gps.rep_id = $rep->emp_id
                        AND gps.type = 'tracking'
                        ORDER BY
                            id DESC
                        LIMIT 1
                    ");

                    if(sizeof($gps) > 0)
                    {
                        if(isset($gps[0]->lat) && isset($gps[0]->lon))
                        {
                                array_push($latitude, $gps[0]->lat);
                                array_push($longitude, $gps[0]->lon);
                                array_push($nameArray, $rep->name);
                                array_push($userIds, $rep->emp_id);
                        }
                    }


                    $table .= "
                    <tr>
                        <td style='background-color:rgba(189, 195, 199,0.5);vertical-align: middle;'>".(++$key)."</td>
                        <td style='vertical-align: middle;'>$rep->rep_id</td>
                        <td style='vertical-align: middle;'>$rep->name</td>
                        <td style='vertical-align: middle;'>";
                        $table .= $rep->user_name ? : 'User name not found!.';
                        $table .= "</td>
                        <td align='center' style='vertical-align: middle;'>";
                            if(sizeOf($gps) > 0)
                            {
                                if($gps[0]->online)
                                {
                                    $contentString .= 
                                    "<div id='content'>
                                        <div id='siteNotice'></div>
                                        <div class='img-user'><img src='".asset('assets/images/user.jpg')."' width='60' height='60'/></div>
                                        <h5 id='firstHeading' class='firstHeading username'>";
                                            $contentString .= $rep->name;        
                                            $contentString .=
                                        " &nbsp;<span class='fa fa-circle' style='
                                          color:rgba(22, 160, 133,1.0);
                                          '></span></h5>
                                          <br><br>
                                        <table class='table table-condensed'>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Order Count</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'>".$gps[0]->order_count."</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Total amount</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> Rs : ".$gps[0]->total_amount.".</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Lock Status</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> ".($gps[0]->lock_status == 0? 'NO' : 'YES')."</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Battey</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> ";
                                                    if(sizeOf($gps[0]->battery) > 0)
                                                    {
                                                        $val = explode('%', $gps[0]->battery);
                                                        if($val[0] <= 100 && $val[0] >= 80)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-full"  style="color:rgba(26, 188, 156,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 80 && $val[0] >= 60)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-three-quarters"  style="color:rgba(26, 188, 156,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 60 && $val[0] >= 40)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-half"  style="color:rgba(46, 204, 113,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 40 && $val[0] >= 20)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-quarter" style="color:rgba(243, 156, 18,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 20 && $val[0] >= 0)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-empty"  style="color:rgba(192, 57, 43,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else
                                                        {
                                                            $contentString .= 'Invalid Value';   
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $contentString .= "<span style='color:rgba(44, 62, 80,0.8);'>not found!.</span>";
                                                    }
                                    $contentString .= "</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Login Status</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> ";
                                                if($gps[0]->login_status == 1)
                                                {
                                                    $contentString .= "<div class='online-info-box' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
                                                }
                                                else
                                                {
                                                    $contentString .= "<div class='offline-info-box' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
                                                }
                                    $contentString .= "</td>
                                            </tr>
                                        </table>
                                        <div id='bodyContent'>
                                        </div>
                                    </div>";

                                    array_push($onlineUsers, ['online' => $contentString]);
                                    $contentString = "";

                                    $table .= "<span style='color:rgba(22, 160, 133,1.0);'><strong>Online <span class='fa fa-check-circle-o'></span></strong></span>";
                                }
                                else
                                {
                                    $contentString .= 
                                    "<div id='content'>
                                        <div id='siteNotice'></div>
                                        <div class='img-user'><img src='".asset('assets/images/user.jpg')."' width='60' height='60'/></div>
                                        <h5 id='firstHeading' class='firstHeading username'>";
                                            $contentString .= $rep->name;        
                                            $contentString .=
                                        " &nbsp;<span class='fa fa-circle' style='
                                          color:rgba(189, 195, 199,1.0);
                                          '></span></h5>
                                          <br><br>
                                        <table class='table table-condensed'>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Order Count</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'>".$gps[0]->order_count."</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Total amount</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> Rs : ".$gps[0]->total_amount.".</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Lock Status</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> ".($gps[0]->lock_status == 0? 'NO' : 'YES')."</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Battey</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> ";
                                                    if(sizeOf($gps[0]->battery) > 0)
                                                    {
                                                        $val = explode('%', $gps[0]->battery);
                                                        if($val[0] <= 100 && $val[0] >= 80)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-full"  style="color:rgba(26, 188, 156,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 80 && $val[0] >= 60)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-three-quarters"  style="color:rgba(26, 188, 156,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 60 && $val[0] >= 40)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-half"  style="color:rgba(46, 204, 113,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 40 && $val[0] >= 20)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-quarter" style="color:rgba(243, 156, 18,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else if($val[0] <= 20 && $val[0] >= 0)
                                                        {
                                                            $contentString .= '<span class="fa fa-battery-empty"  style="color:rgba(192, 57, 43,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                                        }
                                                        else
                                                        {
                                                            $contentString .= 'Invalid Value';   
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $contentString .= "<span style='color:rgba(44, 62, 80,0.8);'>not found!.</span>";
                                                    }
                                    $contentString .= "</td>
                                            </tr>
                                            <tr style='font-size: 15px;'>
                                                <td align='right'><strong>Login Status</strong></td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td align='left'> ";
                                                if($gps[0]->login_status == 1)
                                                {
                                                    $contentString .= "<div class='online-info-box' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
                                                }
                                                else
                                                {
                                                    $contentString .= "<div class='offline-info-box' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
                                                }
                                    $contentString .= "</td>
                                            </tr>
                                        </table>
                                        <div id='bodyContent'>
                                        </div>
                                    </div>";

                                    array_push($onlineUsers, ['offline' => $contentString]);
                                    $contentString = "";
                                    $table .= "<span style='color:rgba(44, 62, 80,0.7);'><strong>Offline <span class='fa fa-circle-o'></span></strong></span>";

                                }
                                
                                /*$table .= $onlineStatus;*/
                            }
                            else
                            {
                                $table .= "<span style='opacity:0.5;'>not found!.</span>";
                            }

                        $table .= "
                        </td>
                        <td style='vertical-align: middle;'>";
                                if($rep->login_status == 1)
                                {
                                    $table .= "<div class='online'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
                                }
                                else
                                {
                                    $table .= "<div class='offline'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
                                }
                        $table .= "<td style='vertical-align: middle;text-align:center;'>";
                        $table .= $rep->lock_status == 0? 'NO' : 'YES';
                        $table .= "</td>";
                        $table .= "<td>".$rep->syncTime."</td>";
                        $table .= "
                        <td align='left' style='vertical-align: middle;'>";

                                if(sizeOf($gps) > 0)
                                {
                                    $val = explode('%', $gps[0]->battery);
                                    if($val[0] <= 100 && $val[0] >= 80)
                                    {
                                        $table .= '<span class="fa fa-battery-full"  style="color:rgba(26, 188, 156,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                    }
                                    else if($val[0] <= 80 && $val[0] >= 60)
                                    {
                                        $table .= '<span class="fa fa-battery-three-quarters"  style="color:rgba(26, 188, 156,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                    }
                                    else if($val[0] <= 60 && $val[0] >= 40)
                                    {
                                        $table .= '<span class="fa fa-battery-half"  style="color:rgba(46, 204, 113,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                    }
                                    else if($val[0] <= 40 && $val[0] >= 20)
                                    {
                                        $table .= '<span class="fa fa-battery-quarter" style="color:rgba(243, 156, 18,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                    }
                                    else if($val[0] <= 20 && $val[0] >= 0)
                                    {
                                        $table .= '<span class="fa fa-battery-empty"  style="color:rgba(192, 57, 43,1.0);"></span> &nbsp;'.$gps[0]->battery;
                                    }
                                    else
                                    {
                                        $table .= 'Invalid Value';   
                                    }
                                }
                                else
                                {
                                    $table .= "<span style='color:rgba(44, 62, 80,0.8);'>not found!.</span>";
                                }
                        $table .= "</td>
                        <td align='center' style='vertical-align: middle;'>
                        <button type='button' class='btn btn-green get_id' id='get_id' class='btn btn-info btn-lg' data-user-id='$rep->rep_id'><span class='fa fa-eye'></span></button>
                        </td>
                    </tr>
                    <div class='modal fade' id='myModal_$rep->rep_id' role='dialog'>
                        <div class='modal-dialog'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <button type='button' class='close' data-dismiss='modal'>&times;</button>
                              <h4 class='modal-title'>Get Action</h4>
                            </div>
                            <div class='modal-body'>
                                <div class='row'>
                                    <div class='col-lg-8 col-lg-offset-2' style='padding-top:30px;padding-bottom: 30px;'>
                                        <div class='row' align='center'>
                                            <div class='col-lg-4'>
                                                <a class='btn btn-green' onclick='getAction($rep->rep_id, 'lock')'>
                                                    <span class='fa fa-lock' style='font-size: 45px;text-align: center;vertical-align: middle;'></span>
                                                </a>
                                                <p class='sm-head'>Lock user</p>
                                            </div>
                                            <div class='col-lg-4'>
                                                <a class='btn btn-green' onclick='getAction($rep->rep_id, 'getDB')'>
                                                    <span class='fa fa-floppy-o' style='font-size: 45px;text-align: center;vertical-align: middle;'></span>
                                                </a>
                                                <p class='sm-head'>Get DB</p>
                                            </div>
                                            <div class='col-lg-4'>
                                                <a class='btn btn-green notify_btn' id='notify_btn' data-user-id='$rep->rep_id'>
                                                    <span class='fa fa-globe' style='font-size: 45px;text-align: center;vertical-align: middle;'></span>
                                                </a>
                                                <p class='sm-head'>Notify</p>
                                            </div>
                                        </div>
                                        <div class='row' id='notify_msg_$rep->rep_id' align='center'>
                                            <input type='hidden' name='user_id' value='get_notify_$rep->rep_id'>
                                            <div id='get_notify_$rep->rep_id' style='padding-top: 20px;display: none;'>
                                                <h3>Notification</h3>
                                                <textarea id='notify_txt' class='form-control' style='resize: none;'' rows='4' placeholder='Enter message here...' required='required'></textarea>
                                            </div>
                                        </div>
                                        <div class='row' style='margin-top: 50px;'>
                                            <div class='col-lg-12' align='center'>
                                                <img src='";
                                                $table .= asset('assets/images/loading-2.gif');
                                                $table .= "'alt='Loading Image' width='200px' class='loading-img' style='display: none;'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-green save_btn' id='save_btn_$rep->rep_id' style='display:none;'>Save</button>
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                            </div>
                          </div>
                        </div>
                    </div>";

                } 
                $table .= "</tbody>";
            }
        $table .= "</table>";
        
        return view('configurationViews::configuration.repList')->with(['table' => $table, 'latitude' => $latitude, 'longitude' => $longitude, 'nameArray' => $nameArray, 'onlineUsers' => $onlineUsers, 'userIds' => $userIds]);
    }

    /**
    *  rep's db
    */

    public function getRepDB()
    {
        return view('configurationViews::configuration.rep_db');
    }

}