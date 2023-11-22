<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Application\CustomerManage\Models\Customer;
use Application\EmployeeManage\Http\Controllers\EmployeeController;
use Application\EmployeeManage\Models\Employee;
use Application\Functions\stab\Functions;
use Application\InvoiceManage\Models\Invoice;
use Application\InvoiceManage\Models\InvoiceDetail;
use Application\JobManage\Models\Job;
use Application\JobManage\Models\JobNew;
use Application\PaymentManage\Models\PaymentAgingReport;
use Application\SalesOrderManage\Models\SalesOrder;
use Application\TargetManage\Models\MarketeerTarget;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Core\UserManage\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class WelcomeController extends Controller
{
    public function index()
    {
        $user = Sentinel::getUser();
        if ($user->id == 1 || $user->roles[0]->id == 1) {
            $target = MarketeerTarget::select(DB::raw('FORMAT(IFNULL(sum(value),0),2)as target'))->where('from', 'like', date('Y-m-01') . '%')->get();
            $target = $target[0]->target;
            $inv_total = DB::select('SELECT
                    format(ifnull(sum(tmp.total),0),2) as total,
                    count(tmp.id) as count
                FROM
                    (SELECT
                        (sum(invd.qty * invd.unit_price) - ifnull(invd.discount,0)) - ifnull((select discount from invoice_discount where invoice_id = inv.id limit 1),0) total,
                        inv.id
                    FROM
                        invoice as inv
                        inner join invoice_detail as invd on inv.id= invd.invoice_id
                        group by inv.id) as tmp');
            $count = $inv_total[0]->count;
            $inv_total = $inv_total[0]->total;
            $job_count = Job::count();


        } else if ($user->roles[0]->id == 2) {
            $target = MarketeerTarget::select('FORMAT(IFNULL(sum(value),0),2)as target')->where('employee_id', $user->employee_id)->where('from', 'like', date('Y-m-01') . '%')->get();
            $target = $target[0]->target;
            $inv_total = DB::select('SELECT
                    format(ifnull(sum(tmp.total),0),2) as total,
                    count(tmp.id) as count
                FROM
                    (SELECT
                        (sum(invd.qty * invd.unit_price) - ifnull(invd.discount,0)) - ifnull((select discount from invoice_discount where invoice_id = inv.id limit 1),0) total,
                        inv.id
                    FROM
                        invoice as inv
                        inner join invoice_detail as invd on inv.id= invd.invoice_id
                        where rep_id = ' . $user->employee_id . '
                        group by inv.id) as tmp');
            $count = $inv_total[0]->count;
            $inv_total = 0.00;//$inv_total[0]->total;

            $job_count = Job::select(DB::raw('count(id) as job_count'))->whereRaw('customer_id IN (select id from remon_customer where marketeer_id = ' . $user->employee_id . ')')->get();
            $job_count = $job_count[0]->job_count;
        } else {
            $target = '0.00';
            $inv_total = '0.00';
            $job_count = '0';
            $count = '0';

        }

        return view('dashboard')->with(['target' => $target, 'invoice_total' => $inv_total, 'invoice_count' => $count, 'job_count' => $job_count]);

    }

    public function get_employee()
    {

        $user = Sentinel::getUser();

        $employee = $user['employee_id'];

        $detail_emp = Employee::where('id', '=', $employee)->get();

        $rep = [];
        foreach ($detail_emp as $descendant) {
            $tmp = $descendant->getDescendantsAndSelf();
            foreach ($tmp as $value) {
                $k = false;
                foreach ($rep as $ext) {
                    if ($ext == $value->id) {
                        $k = true;
                    }
                }

                if (!$k) {
                    $rep[] = $value->id;
                }
            }
        }

        return $rep;
    }

    public function monthlyData()
    {
        $user = Sentinel::getUser();
        if ($user->id == 1 || $user->roles[0]->id == 1) {
            $data = DB::select('SELECT tmp2.total,tmp3.amount,tmp2.month FROM (
                                    SELECT
                                        ifnull(sum(tmp.total), 0) AS total,
                                        MONTH (created_date) AS MONTH
                                    FROM
                                        (
                                            SELECT
                                                sum(
                                                    (invd.qty * invd.unit_price) - ifnull(invd.discount, 0)
                                                ) - ifnull(
                                                    (
                                                        SELECT
                                                            discount
                                                        FROM
                                                            invoice_discount
                                                        WHERE
                                                            invoice_id = inv.id
                                                        LIMIT 1
                                                    ),
                                                    0
                                                ) total,
                                                inv.id,
                                                inv.created_date
                                            FROM
                                                invoice AS inv
                                            INNER JOIN invoice_detail AS invd ON inv.id = invd.invoice_id
                                            AND inv.deleted_at IS NULL
                                            GROUP BY
                                                inv.id
                                        ) AS tmp
                                    WHERE
                                        YEAR (created_date) = YEAR (CURDATE())
                                    GROUP BY
                                        YEAR (created_date),
                                        MONTH (created_date)
                                ) AS tmp2
                                INNER JOIN (
                                    SELECT
                                        ifnull(sum(tmp.total), 0) AS amount,
                                        MONTH (created_date) AS MONTH
                                    FROM
                                        (
                                            SELECT
                                                sum(amount) total,
                                                rpt.id,
                                                rpt.created_at AS created_date
                                            FROM
                                                recipt AS rpt
                                            WHERE
                                                rpt.deleted_at IS NULL
                                            GROUP BY
                                                rpt.id
                                        ) AS tmp
                                    WHERE
                                        YEAR (created_date) = YEAR (CURDATE())
                                    GROUP BY
                                        YEAR (created_date),
                                        MONTH (created_date)
                                ) AS tmp3 ON tmp2. MONTH = tmp3. MONTH
                                        ');

            $value = array();
            $payment = array();
            for ($i = 1; $i <= 12; $i++) {
                $total = 0;
                $amount = 0;
                foreach ($data as $row) {
                    if ($row->month == $i) {
                        $total = $row->total;
                        $amount = $row->amount;
                    }
                }
                array_push($value, $total);
                array_push($payment, $amount);
            }
        } else if ($user->roles[0]->id == 2) {
            $data = DB::select('SELECT
                                    tmp2.total,
                                    tmp3.amount,
                                    tmp2. MONTH
                                FROM
                                    (
                                        SELECT
                                            ifnull(sum(tmp.total), 0) AS total,
                                            MONTH (created_date) AS MONTH
                                        FROM
                                            (
                                                SELECT
                                                    sum(
                                                        (invd.qty * invd.unit_price) - ifnull(invd.discount, 0)
                                                    ) - ifnull(
                                                        (
                                                            SELECT
                                                                discount
                                                            FROM
                                                                invoice_discount
                                                            WHERE
                                                                invoice_id = inv.id
                                                            LIMIT 1
                                                        ),
                                                        0
                                                    ) total,
                                                    inv.id,
                                                    inv.created_date
                                                FROM
                                                    invoice AS inv
                                                INNER JOIN invoice_detail AS invd ON inv.id = invd.invoice_id
                                                WHERE
                                                    inv.rep_id = ' . $user->employee_id . '
                                                AND inv.deleted_at IS NULL
                                                GROUP BY
                                                    inv.id
                                            ) AS tmp
                                        WHERE
                                            YEAR (created_date) = YEAR (CURDATE())
                                        GROUP BY
                                            YEAR (created_date),
                                            MONTH (created_date)
                                    ) AS tmp2
                                INNER JOIN (
                                    SELECT
                                        ifnull(sum(tmp.total), 0) AS amount,
                                        MONTH (created_date) AS MONTH
                                    FROM
                                        (
                                            SELECT
                                                sum(rd.payment_amount) total,
                                                inv.id,
                                                inv.created_date
                                            FROM
                                                invoice AS inv
                                            INNER JOIN recipt_detail AS rd ON inv.id = rd.invoice_id
                                            WHERE
                                                inv.rep_id = ' . $user->employee_id . '
                                            AND inv.deleted_at IS NULL
                                            GROUP BY
                                                inv.id
                                        ) AS tmp
                                    WHERE
                                        YEAR (created_date) = YEAR (CURDATE())
                                    GROUP BY
                                        YEAR (created_date),
                                        MONTH (created_date)
                                ) AS tmp3 ON tmp2. MONTH = tmp3. MONTH');

            $value = array();
            $payment = array();
            for ($i = 1; $i <= 12; $i++) {
                $total = 0;
                $amount = 0;
                foreach ($data as $row) {
                    if ($row->month == $i) {
                        $total = $row->total;
                        $amount = $row->amount;
                    }
                }
                array_push($value, $total);
                array_push($payment, $amount);
            }
        } else {
            $value = array();
        }
        return response()->json(['chart_value' => $value, 'payment' => $payment]);
    }

    public function yearlyData()
    {
        $month = date('Y');

        $target = $this->jsonListRepTarget($month, $this->get_employee());
        $achievement = $this->jsonListRepAchievement($month, $this->get_employee());

        $tt = $this->jsonListRepDelivered($month, $this->get_employee());

        $delivered = $tt[0]['am'];
        $delivered_count = $tt[0]['co'];

        $invoice = $this->jsonListInvoiceCount($month, $this->get_employee());
        $free = $this->jsonListFreeAmount($month, $this->get_employee());
        $return = $this->jsonListReturn($month, $this->get_employee());

        return Response::json(['target' => $target, 'achievement' => $achievement['tot'], 'discount' => $achievement['dis'], 'delivered' => $delivered, 'delivered_count' => $delivered_count, 'invoice' => $invoice, 'free' => $free, 'return' => $return]);
    }

    /**
     * Show the Monthly Data to the user.
     *
     * @return Response
     */
    public function weeklyData()
    {
        $target = 0;
        $achievement = 0;
        $discount = 0;
        $delivered = 0;
        $delivered_count = 0;
        $invoice = 0;
        $free = 0;
        $return = 0;

        $dd = [];

        for ($i = 2; $i < 9; $i++) {
            $date = date('Y-m-d', strtotime('-' . (8 - $i) . ' days'));

            $target += $this->jsonListRepTarget($date, $this->get_employee());
            $achievement_detail = $this->jsonListRepAchievement($date, $this->get_employee());

            $achievement += $achievement_detail['tot'];
            $discount += $achievement_detail['dis'];

            $tt = $this->jsonListRepDelivered($date, $this->get_employee());

            $delivered += $tt[0]['am'];
            $delivered_count += $tt[0]['co'];

            $invoice += $this->jsonListInvoiceCount($date, $this->get_employee());
            $free += $this->jsonListFreeAmount($date, $this->get_employee());
            $return += $this->jsonListReturn($date, $this->get_employee());
        }

        return Response::json(['target' => $target, 'achievement' => $achievement, 'discount' => $discount, 'delivered' => $delivered, 'delivered_count' => $delivered_count, 'invoice' => $invoice, 'free' => $free, 'return' => $return]);
    }

    /**
     * Show the Weekly Data to the user.
     *
     * @return Response
     */
    public function weeklyDataSummary()
    {

        $achievement = 0;
        $discount = 0;
        $delivered = 0;
        $invoice = 0;
        $free = 0;
        $return = 0;

        for ($i = 2; $i < 9; $i++) {
            $date = date('Y-m-d', strtotime('-' . (8 - $i) . ' days'));

            $achievement_detail = $this->jsonListRepAchievement($date, $this->get_employee());

            $achievement += $achievement_detail['tot'];
            $discount += $achievement_detail['dis'];

            $tt = $this->jsonListRepDelivered($date, $this->get_employee());

            $delivered += $tt[0]['am'];

            $invoice += $this->jsonListInvoiceCount($date, $this->get_employee());

            $free += $this->jsonListFreeAmount($date, $this->get_employee());

            $return += $this->jsonListReturn($date, $this->get_employee());
        }

        return Response::json(['achievement' => $achievement, 'discount' => $discount, 'delivered' => $delivered, 'invoice' => $invoice, 'free' => $free, 'return' => $return]);
    }

    /**
     * Json List Pre Order.
     *
     * @return Response
     */
    public function jsonListPreorder($month, $rep)
    {
        $preorder = SalesOrder::where('created_date', 'like', $month . '%')
            ->whereIn('rep_id', $rep)
            ->with(['discounts'])
            ->get();

        $total = 0;

        if ($preorder) {
            foreach ($preorder as $value) {
                $tot_discount = 0;
                if (!$value->discounts->isEmpty()) {
                    foreach ($value->discounts as $discount) {
                        $tot_discount += floatval($discount->discount);
                    }
                }
                $total += floatval($value->total) - floatval($tot_discount);
            }

            return $total;
        } else {
            return "0.00";
        }
    }

    /**
     * Show the All Pre Order Count to the user.
     *
     * @return Response
     */
    public function jsonListPreorderCount($month, $rep)
    {
        $preorder = SalesOrder::select(DB::raw('COUNT(id) as count'))
            ->where('created_date', 'like', $month . '%')
            ->whereIn('rep_id', $rep)
            ->get();
        if ($preorder[0]->count) {
            return $preorder[0]->count;
        } else {
            return "0";
        }
    }

    /**
     * Show the Rep Target to the user.
     *
     * @return Response
     */
    public function jsonListRepTarget($month, $rep)
    {
        $target = RepTarget::select(DB::raw('SUM(value) as target'))
            ->where('from', 'like', $month . '%')
            ->whereIn('employee_id', $rep)
            ->get();
        return $target[0]->target;
    }

    /**
     * Show the Rep Target to the user.
     *
     * @return Response
     */
    public function jsonListRepAchievement($month, $rep)
    {
        $achievement = Invoice::where('created_date', 'like', $month . '%')
            ->whereIn('rep_id', $rep)
            ->with(['discounts'])
            ->get();

        $total_ach = 0;
        $total_dis = 0;

        if ($achievement) {
            foreach ($achievement as $value) {
                if (!$value->discounts->isEmpty()) {
                    foreach ($value->discounts as $discount) {
                        $total_dis += floatval($discount->discount);
                    }
                }
                $total_ach += floatval($value->total);
            }

            return ['tot' => floatval($total_ach) - floatval($total_dis), 'dis' => floatval($total_dis)];
        } else {
            return "0.00";
        }
    }

    /**
     * Show the Delivered Invoice Amount to the user.
     *
     * @return Response
     */
    public function jsonListRepDelivered($month, $rep)
    {

        $delivered = DB::select(DB::raw("SELECT
				(
					SUM(total) - SUM(
						`4ever_invoice_discount`.discount
					)
				) AS delivered,
				COUNT(total) AS delivered_count
			FROM
				`4ever_invoice`
			LEFT JOIN `4ever_invoice_discount` ON `4ever_invoice`.id = `4ever_invoice_discount`.invoice_id
			WHERE
				`4ever_invoice`.created_date LIKE '" . $month . "%'
			AND delivery_status = 1
			AND `4ever_invoice`.rep_id IN (" . implode(',', $rep) . ")"));


        /*return $delivered=Invoice::select(
                    DB::raw('SUM(total)-(select sum(discount) as discount from 4ever_invoice_discount WHERE invoice_id IN (`4ever_invoice`.id)) as delivered ')
                    ,DB::raw('count(id) as delivered_count'))
                    ->where('created_at', 'like', $month.'%')
                    ->where('delivery_status', '=', 1)
                    ->whereIn('rep_id',$rep)
                    ->get();*/


        if ($delivered[0]->delivered) {
            return array(['am' => $delivered[0]->delivered, 'co' => $delivered[0]->delivered_count]);
        } else {
            return array(['am' => "0.00", 'co' => "0"]);
        }
    }

    /**
     * Show the All Invoice Count to the user.
     *
     * @return Response
     */
    public function jsonListInvoiceCount($month, $rep)
    {
        $invoice = Invoice::select(DB::raw('COUNT(id) as count'))
            ->where('created_date', 'like', $month . '%')
            ->whereIn('rep_id', $rep)
            ->get();
        if ($invoice[0]->count) {
            return $invoice[0]->count;
        } else {
            return "0.00";
        }
    }

    /**
     * Show the Free Issue Amount to the user.
     *
     * @return Response
     */
    public function jsonListFreeAmount($month, $rep)
    {
        $free = InvoiceDetail::whereIn('invoice_id', function ($query) use ($month, $rep) {
            $query->select('id')->from('4ever_invoice')
                ->where('created_date', 'like', $month . '%')
                ->whereIn('rep_id', $rep);
        })
            ->leftJoin('4ever_mrp', '4ever_invoice_detail.price_book_detail_id', '=', '4ever_mrp.id')
            ->where('free_qty', '>', 0)
            ->select(DB::raw('SUM(free_qty*mrp) as free'))
            ->get();

        if ($free[0]->free) {
            //return $free[0]->free;
            return number_format((float)$free[0]->free, 2, '.', '');
        } else {
            return "0.00";
        }
    }

    /**
     * Show the return Amount to the user.
     *
     * @return Response
     */
    public function jsonListReturn($month, $rep)
    {
        $return = ReturnDetail::whereIn('returnId', function ($query) use ($month, $rep) {
            $query->select('id')->from('4ever_return')
                ->where('created_at', 'like', $month . '%')
                ->whereIn('rep_id', $rep)
                ->whereIn('approved_status', ['accepted']);
        })
            ->where('return_approved_status', '=', 2)
            ->select(DB::raw('SUM(returnQuantity*unitPrice) as amount'))
            ->get();

        // $total=0;

        // foreach ($return as $value) {
        // 	$total+=$value->returnQuantity*$value->unitPrice;

        // }

        return number_format((float)($return[0]->amount), 2, '.', '');
    }

    /**
     * Show the Monthly Target vs Achievement.
     *
     * @return Response
     */
    public function monthlyTargetvsAchievement()
    {

        $year = date('Y');
        $month = "";

        $target = [];
        $achievement = [];

        $rep = $this->get_employee();

        for ($i = 1; $i < 13; $i++) {
            if ($i <= 9) {
                $month = "0" . $i;
            } else {
                $month = $i;
            }

            $date = $year . "-" . $month;

            $t = RepTarget::select(DB::raw('SUM(value) as target'))
                ->where('from', 'like', $date . '%')
                ->whereIn('employee_id', $rep)
                ->get();

            if ($t[0]->target) {
                $target[] = (float)$t[0]->target;
            } else {
                $target[] = 0;
            }

            $a = DB::select(DB::raw("SELECT
					(
						SUM(total) - SUM(
							`4ever_invoice_discount`.discount
						)
					) AS achievement
				FROM
					`4ever_invoice`
				LEFT JOIN `4ever_invoice_discount` ON `4ever_invoice`.id = `4ever_invoice_discount`.invoice_id
				WHERE
					`4ever_invoice`.created_date LIKE '" . $date . "%'
				AND `4ever_invoice`.rep_id IN (" . implode(',', $rep) . ")"));

            /*$a = Invoice::select(DB::raw('SUM(total) as achievement'))
                            ->where('created_at', 'like', $date.'%')
                            ->whereIn('rep_id',$rep)
                            ->get();*/

            if ($a[0]->achievement) {
                $achievement[] = (float)$a[0]->achievement;
            } else {
                $achievement[] = 0;
            }
        }

        return Response::json(['target' => $target, 'achievement' => $achievement]);
    }

    /**
     * Show the Monthly Target vs Achievement.
     *
     * @return Response
     */
    public function yearlyTargetvsAchievement()
    {

        $year = date('Y');

        $tmp = (int)$year - 5;

        $target = [];
        $achievement = [];

        $years = [];

        $rep = $this->get_employee();

        for ($i = $tmp; $i < $tmp + 9; $i++) {
            $years[] = $i;
            $t = RepTarget::select(DB::raw('SUM(value) as target'))
                ->where('from', 'like', $i . '%')
                ->whereIn('employee_id', $rep)
                ->get();

            if ($t[0]->target) {
                $target[] = (float)$t[0]->target;
            } else {
                $target[] = 0;
            }

            $a = DB::select(DB::raw("SELECT
					(
						SUM(total) - SUM(
							`4ever_invoice_discount`.discount
						)
					) AS achievement
				FROM
					`4ever_invoice`
				LEFT JOIN `4ever_invoice_discount` ON `4ever_invoice`.id = `4ever_invoice_discount`.invoice_id
				WHERE
					`4ever_invoice`.created_date LIKE '" . $i . "%'
				AND `4ever_invoice`.rep_id IN (" . implode(',', $rep) . ")"));

            /*$a = Invoice::select(DB::raw('SUM(total) as achievement'))
                            ->where('created_at', 'like', $i.'%')
                            ->whereIn('rep_id',$rep)
                            ->get();*/

            if ($a[0]->achievement) {
                $achievement[] = (float)$a[0]->achievement;
            } else {
                $achievement[] = 0;
            }
        }

        return Response::json(['target' => $target, 'achievement' => $achievement, 'year' => $years]);
    }

    /**
     * Show the Weekly Pre Order vs Achievement.
     *
     * @return Response
     */
    public function weeklyPreOrdervsAchievement()
    {
        $preorder = [];
        $achievement = [];
        $days = [];

        $rep = $this->get_employee();

        for ($i = 2; $i < 9; $i++) {
            $date = date('Y-m-d', strtotime('-' . (8 - $i) . ' days'));
            $days[] = $date;
            $t = DB::select(DB::raw("SELECT
					(
						SUM(total) - SUM(
							IFNULL(`4ever_sales_order_discount`.discount,0)
						)
					) AS preorder
				FROM
					`4ever_sales_order`
				LEFT JOIN `4ever_sales_order_discount` ON `4ever_sales_order`.id = `4ever_sales_order_discount`.sales_order_id
				WHERE
					`4ever_sales_order`.created_date LIKE '" . $date . "%'
				AND `4ever_sales_order`.rep_id IN (" . implode(',', $rep) . ")"));

            if ($t[0]->preorder) {
                $preorder[] = (float)$t[0]->preorder;
            } else {
                $preorder[] = 0;
            }

            $a = DB::select(DB::raw("SELECT
					(
						SUM(total) - SUM(
							IFNULL(`4ever_invoice_discount`.discount,0)
						)
					) AS achievement
				FROM
					`4ever_invoice`
				LEFT JOIN `4ever_invoice_discount` ON `4ever_invoice`.id = `4ever_invoice_discount`.invoice_id
				WHERE
					`4ever_invoice`.created_date LIKE '" . $date . "%'
				AND `4ever_invoice`.rep_id IN (" . implode(',', $rep) . ")"));

            if ($a[0]->achievement) {
                $achievement[] = (float)$a[0]->achievement;
            } else {
                $achievement[] = 0;
            }
        }

        return Response::json(['preorder' => $preorder, 'achievement' => $achievement, 'days' => $days]);
    }

    /**
     * get in out qty.
     *
     * @return Response
     */
    public function getStock($user)
    {
        $month = date('Y-m-d');

        if ($user->id == 1) {
            $emp = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', 4)->lists('id');
        } else {
            if ($user->roles[0]->id == 2) {
                $emp = Employee::where('id', $user->employee_id)->lists('id');
            } elseif (($user->roles[0]->id > 2)) {
                $emp = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', 4)->lists('id');
            } else {
                $emp = [];
            }
        }


        $warehouses = Warehouse::whereIn('distributor_id', $emp)->lists('id');
        $vehicles = Vehicle::whereIn('steward', $emp)->lists('id');


        $stock = VehicleStockTransaction::select('qty')
            ->whereIn('vehicle_stock_id', function ($qry) use ($month, $vehicles) {
                return $qry->select('id')->from('4ever_vehicle_stock')
                    ->whereIn('vehicle_id', $vehicles);
            })->whereNull('4ever_vehicle_stock_transactions.deleted_at');

        $in = StockTransaction::select('qty')
            ->whereIn('stock_id', function ($qry) use ($month, $warehouses) {
                return $qry->select('id')->from('4ever_stock')
                    ->whereIn('warehouse_id', $warehouses);
            })
            ->whereNull('4ever_stock_transactions.deleted_at')
            ->union($stock)
            ->where('qty', '>', 0)
            ->sum('qty');

        $out = StockTransaction::select('qty')
            ->whereIn('stock_id', function ($qry) use ($month, $warehouses) {
                return $qry->select('id')->from('4ever_stock')
                    ->whereIn('warehouse_id', $warehouses);
            })
            ->whereNull('4ever_stock_transactions.deleted_at')
            ->union($stock)
            ->where('qty', '<', 0)
            ->sum('qty');

        return ['in' => $in, 'out' => $out];
    }

    public function test()
    {
        return password_hash('4ever@ceo007', PASSWORD_DEFAULT);
    }


    function marketeerAuth(Request $request)
    {
        if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;

        $user_obj = $result = User::join('employee as emp', 'emp.id', '=', 'employee_id')
            ->select(
                'user.id',
                'emp.id as userId',
                'username as userName',
                'token',
                DB::raw('CONCAT(emp.first_name, " ", emp.last_name) AS name'),
                'password as password',
                'emp.mobile as telephoneNo',
                'emp.address as userAddress',
                'emp.status as userActive'
            )
            ->where('username', $request->get('username'))
            ->where('employee_type_id', 2)
            ->first();

        if ($user_obj) {
            $credentials = [
                'email' => $request->get('username'),
                'password' => $request->get('password'),
            ];

            $user = Sentinel::findUserById($user_obj->id);
            $user = Sentinel::validateCredentials($user, $credentials);

            if ($user) {
                $response["user"] = $user_obj;
            } else {
                $response["result"] = 0;
            }
            return response()->json($response);
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    function customerList(Request $request)
    {
        if (!isset($_REQUEST['token']) || !isset($_REQUEST['offset']) || !isset($_REQUEST['tag'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;
        $token = $request->get('token');
        $offset = $request->get('offset');
        $tag = $request->get('tag');
        $offset = 20 * $offset;
        if ($token != NULL && EmployeeController::checkToken($token) > 0) {

            $rep_id = EmployeeController::getUserById($token);

            $user_obj = Customer::select(
                'id',
                DB::raw('CONCAT(f_name, " ", l_name) AS name'),
                DB::raw('IFNULL(address,"N/A") as address'),
                DB::raw('IFNULL(mobile,"N/A") as mobile'),
                DB::raw('IFNULL(telephone,"N/A") as telephone'),
                DB::raw('IFNULL(email,"N/A") as email'),
                'credit_limit',
                'credit_period'
            )
                ->where('marketeer_id', $rep_id->employee_id);
            if ($tag) {
                $user_obj = $user_obj->havingRaw('name like "%' . $tag . '%"');
            }
            $user_obj = $user_obj->take(20);
            $user_obj = $user_obj->skip($offset);
            $user_obj = $user_obj->get();

            if ($user_obj) {
                $response["customerList"] = $user_obj;
                return response()->json($response);
            } else {
                $response["result"] = 0;
                return response()->json($response);
            }
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    function dashboardData(Request $request)
    {
        if (!isset($_REQUEST['token'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;
        $token = $request->get('token');
        if ($token != NULL && EmployeeController::checkToken($token) > 0) {

            $rep_id = EmployeeController::getUserById($token);

            $target = MarketeerTarget::select(DB::raw('FORMAT(IFNULL(sum(value),0),2)as target'))->where('employee_id', $rep_id->employee_id)->where('from', 'like', date('Y-m-01') . '%')->get();
            $target = $target[0]->target;

            $orders = Invoice::with('employee', 'discounts', 'details')
                ->select('invoice.id', 'created_date', 'manual_id', 'invoice.location_id', 'invoice.rep_id')
                ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id')
                ->whereRaw("DATE(created_date) BETWEEN  '2019-08-01' AND '2019-08-31'")
                ->whereRaw('rep_id =' . $rep_id->employee_id)
                ->get();

            $inv_total = 0;
            $count = 0;
            foreach ($orders as $detail) {
                $total = 0;
                foreach ($detail->details as $order)
                    $total += (((float)$order->unit_price * (int)$order->qty) - (float)$order->discount);
                $inv_total += $total - (is_object($detail->discounts) ? (float)$detail->discounts->discount : 0);
                ++$count;
            }


            $job_count = Job::select(DB::raw('count(id) as job_count'))->whereRaw('customer_id IN (select id from remon_customer where marketeer_id = ' . $rep_id->employee_id . ')')->whereRaw('YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())')->get();
            $job_count = $job_count[0]->job_count;


            $customer_count = Customer::where('marketeer_id', $rep_id->employee_id)->count();

            $invoice_due = PaymentAgingReport::whereRaw('marketeer_id =' . $rep_id->employee_id)
                ->whereRaw("DATE(created_date) BETWEEN  CONCAT(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE())),'-','01') AND CONCAT(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE())),'-','31')")->whereRaw('total > 0')->where('invoice_due', '>', 0)->get()->sum('invoice_due');

            $user_obj = ['target' => $target, 'invoice_total' => $inv_total, 'invoice_count' => $count, 'job_count' => $job_count, 'invoice_due' => $invoice_due, 'customer_count' => $customer_count];

            $data = DB::select(DB::raw('SELECT
                              IFNULL(mt.value,0) as target,
                              IFNULL(tmp2.total,0) as total,
                              IFNULL(SUM(r.amount),0) as payment,
                              tmp2.MONTH
                            FROM
                              (
                              SELECT
                                IFNULL(SUM(tmp.total),
                                0) AS total,
                                DATE_FORMAT(created_date,
                                "%Y-%m-01") AS MONTH
                              FROM
                                (
                                SELECT
                                  SUM(
                                    (invd.qty * invd.unit_price) - IFNULL(invd.discount,
                                    0)
                                  ) - IFNULL(
                                    (
                                    SELECT
                                      discount
                                    FROM
                                      invoice_discount
                                    WHERE
                                      invoice_id = inv.id
                                    LIMIT 1
                                  ),
                                  0
                                  ) total,
                                  inv.id,
                                  inv.created_date
                                FROM
                                  invoice AS inv
                                INNER JOIN
                                  invoice_detail AS invd ON inv.id = invd.invoice_id
                                WHERE
                                  inv.rep_id = ' . $rep_id->employee_id . ' AND inv.deleted_at IS NULL
                                GROUP BY
                                  inv.id
                              ) AS tmp
                            WHERE
                              YEAR(created_date) = YEAR(CURDATE())
                            GROUP BY
                              YEAR(created_date),
                              MONTH(created_date)
                            ORDER BY
                              MONTH DESC
                            LIMIT 6) tmp2
                            LEFT JOIN
                              marketeer_target AS mt ON tmp2.MONTH = mt.from AND mt.employee_id = ' . $rep_id->employee_id . '
                              LEFT JOIN recipt as r ON DATE_FORMAT(tmp2.MONTH,"%Y-%m") = DATE_FORMAT(r.recipt_date,"%Y-%m") AND r.location_id IN (1,5,49)
                              GROUP BY tmp2.MONTH'));

            $value = array();

            foreach ($data as $row) {
                $arr[0] = $row->target;
                $arr[1] = $row->total;
                $arr[2] = $row->payment;
                array_push($value, $arr);
            }

            $month = DB::select(DB::raw('SELECT MONTHNAME(curdate()) as first, MONTHNAME(DATE_SUB(curdate(), INTERVAL 1 MONTH)) as second, MONTHNAME(DATE_SUB(curdate(), INTERVAL 2 MONTH)) as third, MONTHNAME(DATE_SUB(curdate(), INTERVAL 3 MONTH)) as fourth,MONTHNAME(DATE_SUB(curdate(), INTERVAL 4 MONTH)) as fifth'));
            $month_arr = [$month[0]->fifth, $month[0]->fourth, $month[0]->third, $month[0]->second, $month[0]->first];

            $response["data_list"] = $user_obj;
            $response["chart_data"] = ['month' => $month_arr, 'value' => $value];
            return response()->json($response);
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    private function getInvoiceTotal($filter, $rep)
    {
        $inv_total = DB::select(DB::raw('SELECT
                    format(ifnull(sum(tmp.total),0),2) as total,
                    count(tmp.id) as count
                FROM
                    (SELECT
                        (sum(invd.qty * invd.unit_price) - ifnull(invd.discount,0)) - ifnull((select discount from invoice_discount where invoice_id = inv.id limit 1),0) total,
                        inv.id
                    FROM
                        invoice as inv
                        inner join invoice_detail as invd on inv.id= invd.invoice_id
                        where rep_id = ' . $rep . '
                        ' . $filter . '  
                        group by inv.id) as tmp'));
    }

    public function getOutstandingData(Request $request)
    {
        if (!isset($_REQUEST['token']) || !isset($_REQUEST['customer']) || !isset($_REQUEST['month']) || !isset($_REQUEST['year'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;
        $token = $request->get('token');
        $customer = $request->get('customer');
        $month = $request->get('month');
        $year = $request->get('year');
        if ($token != NULL && EmployeeController::checkToken($token) > 0) {

            $rep_id = EmployeeController::getUserById($token);

            $date_sql = 'AND YEAR(invoice.created_at) = ' . $year . ' AND MONTH(invoice.created_at) = ' . $month;

            $rep_sql = ' AND rep_id =' . $rep_id->employee_id;

            $location_sql = ' AND location_id =' . $customer;

            $orders_1 = DB::select(DB::raw(
                "SELECT
                            CONCAT(
                                remon_customer.f_name,
                                ' ',
                                remon_customer.l_name
                            ) AS customer_name,
                            `invoice`.`created_date`,
                            `invoice`.`manual_id`,
                            `invoice`.`job_no`,
                            (
                                IFNULL(
                                    (
                                        SELECT
                                            sum(
                                                (ind.qty * ind.unit_price) - ifnull(ind.discount, 0)
                                            )
                                        FROM
                                            invoice_detail AS ind
                                        WHERE
                                            ind.invoice_id = invoice.id
                                        GROUP BY
                                            invoice.id
                                    ),
                                    0
                                ) - ifnull(
                                    invoice_discount.discount,
                                    0
                                )
                            ) AS total,
                        
                        IF (
                            recipt_detail.id IS NULL,
                            (
                                IFNULL(
                                    (
                                        SELECT
                                            sum(
                                                (ind.qty * ind.unit_price) - ifnull(ind.discount, 0)
                                            )
                                        FROM
                                            invoice_detail AS ind
                                        WHERE
                                            ind.invoice_id = invoice.id
                                        GROUP BY
                                            invoice.id
                                    ),
                                    0
                                ) - ifnull(
                                    invoice_discount.discount,
                                    0
                                )
                            ),
                            (
                                SELECT
                                    recipt_detail.invoice_due
                                FROM
                                    recipt_detail
                                WHERE
                                    recipt_detail.invoice_id = invoice.id
                                ORDER BY
                                    recipt_detail.id DESC
                                LIMIT 1
                            )
                        ) AS invoice_due,
                         datediff(
                            curdate(),
                            invoice.created_date
                        ) AS no_of_days,
                         `recipt_detail`.`id`
                        FROM
                            `invoice`
                        LEFT JOIN `recipt_detail` ON `recipt_detail`.`invoice_id` = `invoice`.`id`
                        LEFT JOIN `invoice_discount` ON `invoice_discount`.`invoice_id` = `invoice`.`id`
                        INNER JOIN `remon_customer` ON `remon_customer`.`id` = `invoice`.`location_id`
                        WHERE
                            `invoice`.`deleted_at` IS NULL
                        " . $date_sql . "
                        " . $rep_sql . "
                        " . $location_sql . "
                        GROUP BY
                            `invoice`.`id`
                        HAVING
                            (recipt_detail.id IS NULL
                        OR invoice_due > 0)
                        AND total > 0 ORDER BY customer_name,manual_id"
            ));

            $orders = PaymentAgingReport::select(
                'created_date',
                'manual_id',
                'job_no',
                'customer_name',
                'total',
                'invoice_due',
                DB::raw('IFNULL(DATEDIFF(CURDATE(),created_date),0) as no_of_days'),
                'id'
            )
                ->whereRaw("DATE(created_date) BETWEEN  CONCAT(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE())),'-','01') AND CONCAT(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE())),'-','31')")
                ->whereRaw('total > 0')->where('invoice_due', '>', 0);

            if ($year) {
                if ($month) {
                    $orders = $orders->whereRaw("DATE(created_date) BETWEEN  CONCAT(CONCAT('" . $year . "','-','" . $month . "'),'-','01') AND CONCAT(CONCAT('" . $year . "','-','" . $month . "'),'-','31')");
                }
            } else {
                $orders = $orders->whereRaw("DATE(created_date) BETWEEN  CONCAT(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE())),'-','01') AND CONCAT(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE())),'-','31')");
            }

            if ($request->get('marketeer') && $request->get('marketeer') > 0) {
                $orders = $orders->where('marketeer_id', $request->get('marketeer'));
            }

            if ($request->get('customer') && $request->get('customer') > 0) {
                $orders = $orders->where('customer', $request->get('customer'));
            }
            $orders = $orders->groupBy(
                'id'
            );
            $orders = $orders->orderBy('no_of_days', 'desc');
            $orders = $orders->get();

            $response["data_list"] = $orders;
            return response()->json($response);
        }
    }

    public function addLocation(Request $request)
    {
        $token = $request->get('token');
        $data = file_get_contents('php://input');
        $data = str_replace("\xe2\x80\x8b", "", $data);
        $uploadType = $request->get('uploadType');

        if (!isset($_REQUEST['token'])) {
            return 'invalid request request parameters is in valid ';
        }

        $rep_id = EmployeeController::getUserById($token);

        $data = json_decode($data);
        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;

        if ($token != NULL && EmployeeController::checkToken($token) > 0) {
            $response["result"] = 1;
            $response["sucsessfully"] = array();
            foreach (json_decode($data) as $gps) {
                $tracking = Tracking::create([
                    'lat' => $gps->gpsLatitude,
                    'lon' => $gps->gpsLongitude,
                    'rep_id' => $rep_id->employee_id,
                    'invoice_id' => 0,
                    'type' => 'tracking',
                    'battery' => $gps->batteryLevel,
                    'location_type' => $gps->locationType,
                    'date' => Functions::formatDateToPhp($gps->createdDate)
                ]);
                array_push($response["sucsessfully"], array('clientKey' => $gps->gpsTracking_id, 'serverKey' => $tracking->id));
            }
            return response()->json($response);
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    public function jobList(Request $request)
    {
        if (!isset($_REQUEST['token']) || !isset($_REQUEST['offset']) || !isset($_REQUEST['tag'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;
        $token = $request->get('token');
        $offset = $request->get('offset');
        $tag = $request->get('tag');
        $offset = 20 * $offset;
        if ($token != NULL && EmployeeController::checkToken($token) > 0) {

            $rep_id = EmployeeController::getUserById($token);

            $user_arr = Customer::select('id')
                ->where('marketeer_id', $rep_id->employee_id)->lists('id');

            $user_obj = JobNew::select('job_new.*', DB::raw('DATE_FORMAT(job_new.created_at,"%Y-%b-%d") as formatted_date'), DB::raw('IFNULL((SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1)),"Creator") as section '))->with(['customer' => function ($query) {
                $query->select(
                    'id',
                    DB::raw('CONCAT(f_name, " ", l_name) AS name'),
                    DB::raw('IFNULL(address,"N/A") as address'),
                    DB::raw('IFNULL(mobile,"N/A") as mobile'),
                    DB::raw('IFNULL(telephone,"N/A") as telephone'),
                    DB::raw('IFNULL(email,"N/A") as email'),
                    'credit_limit',
                    'credit_period'
                );
            }])->whereIn('customer_id', $user_arr);
            if ($tag) {
                $user_obj = $user_obj->havingRaw('job_no like "%' . $tag . '%"');
            }
            $user_obj = $user_obj->take(20);
            $user_obj = $user_obj->skip($offset);
            $user_obj = $user_obj->get();

            if ($user_obj) {
                $response["jobList"] = $user_obj;
                return response()->json($response);
            } else {
                $response["result"] = 0;
                return response()->json($response);
            }
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    public function getJobData(Request $request)
    {
        if (!isset($_REQUEST['token']) || !isset($_REQUEST['job_no'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;
        $token = $request->get('token');
        $job_no = $request->get('job_no');
        if ($token != NULL && EmployeeController::checkToken($token) > 0) {

            $job = Job::with('lamination.lamination_obj', 'paper.paper_obj', 'customer', 'employee', 'album_type.album_obj', 'album_size', 'album_cover.cover_obj', 'album_box.box_obj')->where('job_no', $job_no)->first();
            $response["job"] = $job;
            return response()->json($response);
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    public function getCustomerOutstanding(Request $request)
    {
        if (!isset($_REQUEST['token']) || !isset($_REQUEST['offset']) || !isset($_REQUEST['tag'])) {
            return 'invalid request request parameters is in valid ';
        }

        $response = array();
        $time = time();
        $response["timestamp"] = $time * 1000;
        $response["result"] = 1;
        $token = $request->get('token');
        $offset = $request->get('offset');
        $tag = $request->get('tag');
        $job_no = $request->get('job_no');
        $offset = 20 * $offset;
        if ($token != NULL && EmployeeController::checkToken($token) > 0) {

            $rep_id = EmployeeController::getUserById($token);

            $orders = PaymentAgingReport::select(
                'customer_name',
                DB::raw('SUM(total) as total'),
                DB::raw('SUM(invoice_due) as invoice_due')
            )
                ->whereRaw('total > 0')
                ->where('invoice_due', '>', 0)
                ->where('marketeer_id', $rep_id);

            if ($tag) {
                $orders = $orders->whereRaw('customer_name LIKE "%' . $tag . '%"');
            }

            $orders = $orders->groupBy('customer');
            $orders = $orders->orderBy('customer_name', 'asc')->orderBy('no_of_days', 'desc');
            $orders = $orders->take(20)->skip($offset)->get();

            $response["outstanding"] = $orders;
            return response()->json($response);
        } else {
            $response["result"] = 0;
            return response()->json($response);
        }
    }

    public function checkPayment(Request $request)
    {
        $last_days = date("Y-m-d", strtotime("-4 days"));
        $invoices = DB::select('SELECT
                                    id 
                                FROM
                                    invoice WHERE id NOT IN(SELECT invoice_id FROM recipt_detail)
                                    AND DATE(created_date) < "' . $last_days . '" AND payment_type = "cash" AND discount_reset = 0 AND deleted_at IS NULL');

        foreach ($invoices as $inv) {
            DB::select('UPDATE invoice_detail SET old_discount = invoice_detail.discount ,discount = 0 WHERE invoice_id = ' . $inv->id);
            DB::select('UPDATE invoice_discount SET old_discount = invoice_discount.discount ,discount = 0 WHERE invoice_id = ' . $inv->id);
            $invoice = Invoice::find($inv->id);
            PaymentAgingReport::where('manual_id', $invoice->manual_id)->update(['total' => $invoice->total, 'invoice_due' => $invoice->total]);
            $invoice->discount_reset = 1;
            $invoice->save();
        }
        return 1;
    }

    public function resetDiscount(Request $request)
    {
        $invoices = Invoice::with('details', 'discounts')->skip($request->get('skip'))->take(5000)->get();

        foreach ($invoices as $inv) {
            // DB::select('UPDATE invoice_detail SET invoice_detail.discount = old_discount  WHERE invoice_id = ' . $inv->id);
            // DB::select('UPDATE invoice_discount SET invoice_discount.discount = old_discount WHERE invoice_id = ' . $inv->id);
            $invoice = Invoice::find($inv->id);
            $total = 0;
            foreach ($inv->details as $detail) {
                $total += (($detail->unit_price * $detail->qty) - $detail->discount);
            }
            //Log::info($total);
            //Log::info(is_object($inv->discounts) ? $inv->discounts->discount : 0);
            $total = $total - (is_object($inv->discounts) ? $inv->discounts->discount : 0);
            //Log::info($total);
            //Log::info(' ---- ');
            $payment_agin = PaymentAgingReport::where('manual_id', $invoice->manual_id)->first();


            if ($payment_agin) {
                $payment_agin->total = $total;
                $payment_agin->save();
            }
            //$invoice->discount_reset = 0;
            //$invoice->total = $total;
            //$invoice->save();
        }
        return 1;
    }

    public function resetDue(Request $request)
    {
        $invoices = Invoice::with('details', 'discounts', 'recipt.bill.types')->skip($request->get('skip'))->take(5000)->get();

        foreach ($invoices as $inv) {
            // DB::select('UPDATE invoice_detail SET invoice_detail.discount = old_discount  WHERE invoice_id = ' . $inv->id);
            // DB::select('UPDATE invoice_discount SET invoice_discount.discount = old_discount WHERE invoice_id = ' . $inv->id);
            //  $invoice = Invoice::find($inv->id);
            $total = 0;
            foreach ($inv->details as $detail) {
                $total += (($detail->unit_price * $detail->qty) - $detail->discount);
            }
            //Log::info($total);
            //Log::info(is_object($inv->discounts) ? $inv->discounts->discount : 0);
            $total = $total - (is_object($inv->discounts) ? $inv->discounts->discount : 0);
            //Log::info($total);
            //Log::info(' ---- ');
            $due = 0;
            foreach ($inv->recipt as $recipt) {
                $due += $recipt->payment_amount;
            }

            $payment_agin = PaymentAgingReport::where('manual_id', $inv->manual_id)->first();


            if ($payment_agin) {
                $payment_agin->invoice_due = ($total - $due);
                $payment_agin->save();
            }
            //$invoice->discount_reset = 0;
            //$invoice->total = $total;
            //$invoice->save();
        }
        return 1;
    }
}
