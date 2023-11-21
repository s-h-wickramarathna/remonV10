<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\InvoiceManage\Http\Controllers;


use App\Classes\InvoicePdfTemplate;
use App\Classes\OverpaidHandler;
use App\Exceptions\TransactionException;
use App\Http\Controllers\Controller;
use App\Models\AdminAuth;
use Application\CustomerManage\Models\Customer;
use Application\InvoiceManage\Models\CreditNote;
use Application\InvoiceManage\Models\InvoiceDiscount;
use Application\JobManage\Models\Job;
use Application\PaymentManage\Models\PaymentAgingReport;
use Application\PaymentManage\Models\Recipt;
use Application\PaymentManage\Models\ReciptDetail;
use Application\SalesOrderManage\Http\Controllers\SalesOrderController;
use Application\SalesOrderManage\Models\SalesOrder;
use Application\SalesOrderManage\Models\SalesOrderDiscount;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Application\EmployeeManage\Http\Controllers\EmployeeController;
use Application\EmployeeManage\Models\Employee;
use Application\InvoiceManage\Models\Invoice;
use Application\InvoiceManage\Models\InvoiceDetail;
use Application\Product\Models\Product;
use Application\Product\Models\Product_Brand;
use Application\Product\Models\Product_Range;
use Application\ProductCategory\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\CountValidator\Exception;
use phpDocumentor\Reflection\Location;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Classes\PdfTemplate;
use App\Classes\PdfLoadingNote;
use App\Classes\Common;


class InvoiceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Show the invoice add screen to the user.
     *
     * @return Response
     */
    public function addView($id)
    {
        //$job = Job::find($id);
        $outlet = Customer::find($id);
        $brandList = [];
        $categoryList = [];
        $rangeList = [];
        $outstanding = DB::select("SELECT
                                count(tmp.id) AS count,
                                sum(tmp.remain) AS outstanding
                            FROM
                                (
                                    SELECT
                                        invoice.id,
                                        (
                                            SELECT
                                                SUM(
                                                    (id.qty * id.unit_price) - id.discount
                                                )
                                            FROM
                                                invoice_detail AS id
                                            WHERE
                                                id.invoice_id = invoice.id
                                            GROUP BY
                                                id.invoice_id
                                        ),
                                        (
                                            IFNULL(
                                                (
                                                    SELECT
                                                        SUM(
                                                            (id.qty * id.unit_price) - id.discount
                                                        )
                                                    FROM
                                                        invoice_detail AS id
                                                    WHERE
                                                        id.invoice_id = invoice.id
                                                    GROUP BY
                                                        id.invoice_id
                                                ),
                                                0
                                            ) - (
                                                SELECT
                                                    IFNULL(sum(discount), 0)
                                                FROM
                                                    invoice_discount
                                                WHERE
                                                    invoice_id = invoice.id
                                            )
                                        ) - IFNULL(
                                            (
                                                SELECT
                                                    sum(payment_amount)
                                                FROM
                                                    recipt_detail
                                                WHERE
                                                    invoice_id = invoice.id
                                            ),
                                            0
                                        ) AS remain
                                    FROM
                                        invoice
                                    WHERE
                                        location_id = " . $id . "
                                        AND deleted_at IS NULL
                                        AND status = 1
                                ) tmp
                            WHERE
                                tmp.remain > 0");
        $outstanding = strlen($outstanding[0]->outstanding) > 0 ? $outstanding[0]->outstanding : 0;
        $e_productList = Product::where('id', 85)->orWhere('id', 858)->get();
        $editorList = Employee::where('employee_type_id', 4)->get();

        /*$marketeer_outstanding = DB::select(DB::raw("SELECT
                                    count(tmp.id) AS count,
                                    IFNULL(sum(tmp.remain),0) AS outstanding
                                FROM
                                    (
                                        SELECT
                                            invoice.id,
                                            ((
                                                SELECT
                                                    SUM(
                                                        (id.qty * id.unit_price) - id.discount
                                                    )
                                                FROM
                                                    invoice_detail AS id
                                                WHERE
                                                    id.invoice_id = invoice.id
                                                GROUP BY
                                                    id.invoice_id
                                            )- (
                                                SELECT
                                                    IFNULL(sum(discount), 0)
                                                FROM
                                                    invoice_discount
                                                WHERE
                                                    invoice_id = invoice.id
                                            )),
                                            (
                                                IFNULL(
                                                    (
                                                        SELECT
                                                            SUM(
                                                                (id.qty * id.unit_price) - id.discount
                                                            )
                                                        FROM
                                                            invoice_detail AS id
                                                        WHERE
                                                            id.invoice_id = invoice.id
                                                        GROUP BY
                                                            id.invoice_id
                                                    ),
                                                    0
                                                ) - (
                                                    SELECT
                                                        IFNULL(sum(discount), 0)
                                                    FROM
                                                        invoice_discount
                                                    WHERE
                                                        invoice_id = invoice.id
                                                )
                                            ) - IFNULL(
                                                (
                                                    SELECT
                                                        sum(payment_amount)
                                                    FROM
                                                        recipt_detail
                                                    WHERE
                                                        invoice_id = invoice.id
                                                ),
                                                0
                                            ) AS remain
                                        FROM
                                            invoice
                                        WHERE
                                            rep_id = ".$outlet->marketeer_id."
                                    ) tmp
                                WHERE
                                    tmp.remain > 0"));*/

        //return $outlet->marketeer_id;

        $marketeer_outstanding = DB::select("SELECT SUM(tt.invoice_due) as outstanding,COUNT(tt.invoice_due) FROM (SELECT
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
                             `recipt_detail`.`id`
                            FROM
                                `invoice`
                            LEFT JOIN `recipt_detail` ON `recipt_detail`.`invoice_id` = `invoice`.`id`
                            LEFT JOIN `invoice_discount` ON `invoice_discount`.`invoice_id` = `invoice`.`id`
                            WHERE
                                `invoice`.`deleted_at` IS NULL
                            AND rep_id = " . $outlet->marketeer_id .
            " GROUP BY
                                `invoice`.`id`
                            HAVING
                                (
                                    recipt_detail.id IS NULL
                                    OR invoice_due > 0
                                )
                            AND total > 0)as tt");


        $creditlimit = $outlet->credit_limit;

        $marketeer = Employee::find($outlet->marketeer_id);
        //return $marketeer_outstanding[0]->outstanding;
        //return $marketeer->credit_limit - $marketeer_outstanding[0]->outstanding;
        $marketeer->credit_limit = $marketeer->credit_limit - $marketeer_outstanding[0]->outstanding;

        //return $marketeer;

        return view('invoiceManage::add')->with(['brandList' => $brandList, 'categoryList' => $categoryList, 'rangeList' => $rangeList, 'productList' => [], 'outlet' => $outlet, 'outstanding' => $outstanding, 'e_productList' => $e_productList, 'editorList' => $editorList, 'creditlimit' => $creditlimit, 'marketeer' => $marketeer]);
    }


    public function insertInvoice($order, $details, $discounts, $resArray)
    {

        $vehicle = RepVehicle::where('rep_employee_id', $order->userId)->whereNull('ended_at')->first();
        $location = Location::find($order->outletId);
        $is_exist = Invoice::where('rep_id', $order->userId)->where('location_id', $order->outletId)->where('manual_id', $order->manualId)->where('created_date', $order->salesOrderDateTime)->first();
        if (sizeof($is_exist) > 0) {
            $orderObj = $is_exist;
        } else {
            $orderObj = Invoice::create([
                'rep_id' => $order->userId,
                'location_id' => $order->outletId,
                'loading_id' => 0,
                'manual_id' => $order->manualId,
                'lat' => $order->gps_latitude,
                'lon' => $order->gps_longitude,
                'print_status' => $order->printStatus,
                'pay_date' => $order->paymentDate,
                'discount' => $order->salesOrderDiscount,
                'created_date' => $order->salesOrderDateTime,
                'brand_id' => $order->brandId,
                'territory_id' => $location->ancestors()->get()[3]->id,
                'route_id' => $location->ancestors()->get()[4]->id,
                'total' => $order->totalAmount,
                'device' => 1
            ]);
        }
        //return $orderObj;
        $orderResArray = $resArray['invoices'];
        $orderDetailsResArray = $resArray['salesOrderDetails'];
        $orderDiscountResArray = $resArray['salesOrderDiscountList'];
        array_push($orderResArray, array('clientKey' => $order->salesOrder_Id, 'serverKey' => $orderObj->id));
        $orderDetailsResArray = $this->insertInvoiceDetail($orderObj->id, $details, $vehicle->vehicle_id, $orderDetailsResArray);
        $orderDiscountResArray = $this->insertOrderDiscount($orderObj->id, $discounts, $orderDiscountResArray);
        SalesOrderController::updateLocationOfOutlet($order->outletId, $order->gps_latitude, $order->gps_longitude);
        $resArray['invoices'] = $orderResArray;
        $resArray['salesOrderDetails'] = $orderDetailsResArray;
        $resArray['salesOrderDiscountList'] = $orderDiscountResArray;
        return $resArray;
    }

    public function insertInvoiceDetail($orderId, $details, $vehicleId, $orderDetailsResArray)
    {
        $detail_exists = InvoiceDetail::where('invoice_id', $orderId)->get();
        if (sizeof($detail_exists) > 0) {
            foreach ($detail_exists as $detail_exist) {
                foreach ($details as $detail) {
                    if ($detail->productId == $detail_exist->productId && $detail->batchId == $detail_exist->batchId) {
                        array_push($orderDetailsResArray, array('clientKey' => $detail->salesOrderDetails_Id, 'serverKey' => $detail_exist->id));
                        break;
                    }
                }
            }
        } else {
            foreach ($details as $detail) {
                $detailObj = InvoiceDetail::create([
                    'product_id' => $detail->productId,
                    'batch_id' => $detail->batchId,
                    'unit_price' => $detail->unitPrice,
                    'qty' => $detail->quantity,
                    'free_qty' => $detail->freeQuantity,
                    'group_id' => $detail->groupId,
                    'invoice_id' => $orderId,
                    'order_detail_type' => $detail->orderDetailType,
                    'price_book_detail_id' => $detail->priceBookDetailId
                ]);
                VehicleStockHandler::stockOut(($detail->quantity + $detail->freeQuantity), $detail->productId, $detail->batchId, $vehicleId, 1, $orderId, 1);
                array_push($orderDetailsResArray, array('clientKey' => $detail->salesOrderDetails_Id, 'serverKey' => $detailObj->id));
            }
        }
        return $orderDetailsResArray;
    }

    public function insertOrderDiscount($orderId, $discounts, $orderDiscountResArray)
    {
        $discounts_exist = InvoiceDiscount::where('invoice_id', $orderId)->get();
        if (sizeof($discounts_exist) > 0) {
            foreach ($discounts_exist as $discount_exist) {
                foreach ($discounts as $discount) {
                    if ($discount->packageId == $discount_exist->packageId && $discount->discountRuleId == $discount_exist->discountRuleId && $discount->discountType == $discount_exist->discountType && $discount->discountAmount == $discount_exist->discountAmount) {
                        array_push($orderDiscountResArray, array('clientKey' => $discount->salesOrderDiscountId, 'serverKey' => $discount_exist->id));
                        break;
                    }

                }
            }
        } else {
            foreach ($discounts as $discount) {
                $discountObj = InvoiceDiscount::create([
                    'product_id' => 0,
                    'discount_group_id' => $discount->packageId,
                    'discount' => $discount->discountAmount,
                    'rule_id' => $discount->discountRuleId,
                    'discount_type' => $discount->discountType,
                    'invoice_id' => $orderId
                ]);
                array_push($orderDiscountResArray, array('clientKey' => $discount->salesOrderDiscountId, 'serverKey' => $discountObj->id));
            }
        }
        return $orderDiscountResArray;
    }

    public function getProductByBrand(Request $request)
    {
        $expt = explode(',', $request->get('expectProduct'));

        return Product::with(['mrp', 'sizes'])
            ->where('status', 1)
            ->whereNotIn('id', $expt)
            ->get();
    }

    public function getMarketeer(Request $request)
    {
        $custID = $request->custID;

        //Customer::where('marketeer_id')->get();
        $mar = Employee::select('first_name', 'last_name', 'id')->whereIn('id', function ($query) use ($custID) {
            $query->from('remon_customer')->where('id', $custID)->pluck('marketeer_id');
        })->get();

        $rowData = Job::whereNotIn('job_no', function ($query) {
            $query->from('invoice')->select('job_no');
        })
            ->where('customer_id', $custID)->get();
        $mar = sizeof($mar) > 0 ? $mar : Employee::where('first_name', 'In')->where('last_name', 'House')->get();

        return array('marketeer' => $mar, 'jobs' => $rowData);


    }

    public function getProductByCategory(Request $request)
    {
        $expt = explode(',', $request->get('expectProduct'));
        $category = $request->category;
        $brand = $request->brand;
        $outlet = $request->outlet;
        $user = Sentinel::getUser();
        $warehouse = Warehouse::where('distributor_id', $user->employee_id)->first();
        if ($category > 0) {
            return Product::with(['custom_price_book' => function ($query) use ($outlet) {
                $query->select('4ever_price_book_detail.*', 'cpb.*')
                    ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                    ->join('4ever_price_book_custom as cpb', 'pb.id', '=', 'cpb.price_book_id')
                    ->whereRaw('pb.type = 2 and pb.category = 3 and cpb.user_id=' . $outlet . ' and effective_date <= curDate() and (cpb.ended_at is null or cpb.ended_at >= curDate())');
            }, 'stranded_price_book' => function ($query) {
                $query->select('4ever_price_book_detail.*')
                    ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                    ->where('pb.type', 1)
                    ->where('pb.category', 3);
            }, 'stock' => function ($query) use ($warehouse) {
                $query->join('4ever_mrp as mrp', 'mrp.id', '=', '4ever_stock.mrp_id')->where('warehouse_id', $warehouse->id);
            }, 'range', 'category'])
                /*->join('4ever_mrp as mrp','mrp.product_id','=','4ever_product.id')
                ->select('4ever_product.id','4ever_product.product_name','4ever_product.pack_size','4ever_product.short_code','mrp.mrp','mrp.id as mrp_id','range_id','product_category_id','brand_id')
                ->whereNull('mrp.ended_at')*/
                ->where('product_category_id', $category)
                ->where('status', 1)
                ->whereNotIn('4ever_product.id', $expt)
                ->get();
        } else {
            return Product::with(['custom_price_book' => function ($query) use ($outlet) {
                $query->select('4ever_price_book_detail.*', 'cpb.*')
                    ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                    ->join('4ever_price_book_custom as cpb', 'pb.id', '=', 'cpb.price_book_id')
                    ->whereRaw('pb.type = 2 and pb.category = 3 and cpb.user_id=' . $outlet . ' and effective_date <= curDate() and (cpb.ended_at is null or cpb.ended_at >= curDate())');
            }, 'stranded_price_book' => function ($query) {
                $query->select('4ever_price_book_detail.*')
                    ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                    ->where('pb.type', 1)
                    ->where('pb.category', 3);
            }, 'stock' => function ($query) use ($warehouse) {
                $query->join('4ever_mrp as mrp', 'mrp.id', '=', '4ever_stock.mrp_id')->where('warehouse_id', $warehouse->id);
            }, 'range', 'category'])
                /* ->join('4ever_mrp as mrp','mrp.product_id','=','4ever_product.id')
                 ->select('4ever_product.id','4ever_product.product_name','4ever_product.pack_size','4ever_product.short_code','mrp.mrp','mrp.id as mrp_id','range_id','product_category_id','brand_id')
                 ->whereNull('mrp.ended_at')*/
                ->where('brand_id', $brand)
                ->where('status', 1)
                ->whereNotIn('4ever_product.id', $expt)
                ->get();

        }
    }

    public function getProductByRange(Request $request)
    {
        $expt = explode(',', $request->get('expectProduct'));
        $range = $request->range;
        $brand = $request->brand;
        $category = $request->category;
        $outlet = $request->outlet;
        $user = Sentinel::getUser();
        $warehouse = Warehouse::where('distributor_id', $user->employee_id)->first();
        if ($range > 0) {
            return Product::with(['custom_price_book' => function ($query) use ($outlet) {
                $query->select('4ever_price_book_detail.*', 'cpb.*')
                    ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                    ->join('4ever_price_book_custom as cpb', 'pb.id', '=', 'cpb.price_book_id')
                    ->whereRaw('pb.type = 2 and pb.category = 3 and cpb.user_id=' . $outlet . ' and effective_date <= curDate() and (cpb.ended_at is null or cpb.ended_at >= curDate())');
            }, 'stranded_price_book' => function ($query) {
                $query->select('4ever_price_book_detail.*')
                    ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                    ->where('pb.type', 1)
                    ->where('pb.category', 3);
            }, 'stock' => function ($query) use ($warehouse) {
                $query->join('4ever_mrp as mrp', 'mrp.id', '=', '4ever_stock.mrp_id')->where('warehouse_id', $warehouse->id);
            }, 'range', 'category'])
                /*->join('4ever_mrp as mrp','mrp.product_id','=','4ever_product.id')
                ->select('4ever_product.id','4ever_product.product_name','4ever_product.pack_size','4ever_product.short_code','mrp.mrp','mrp.id as mrp_id','range_id','product_category_id','brand_id')
                ->whereNull('mrp.ended_at')*/
                ->where('range_id', $range)
                ->where('status', 1)
                ->whereNotIn('4ever_product.id', $expt)
                ->get();
        } else {
            if ($category > 0) {
                return Product::with(['custom_price_book' => function ($query) use ($outlet) {
                    $query->select('4ever_price_book_detail.*', 'cpb.*')
                        ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                        ->join('4ever_price_book_custom as cpb', 'pb.id', '=', 'cpb.price_book_id')
                        ->whereRaw('pb.type = 2 and pb.category = 3 and cpb.user_id=' . $outlet . ' and effective_date <= curDate() and (cpb.ended_at is null or cpb.ended_at >= curDate())');
                }, 'stranded_price_book' => function ($query) {
                    $query->select('4ever_price_book_detail.*')
                        ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                        ->where('pb.type', 1)
                        ->where('pb.category', 3);
                }, 'stock' => function ($query) use ($warehouse) {
                    $query->join('4ever_mrp as mrp', 'mrp.id', '=', '4ever_stock.mrp_id')->where('warehouse_id', $warehouse->id);
                }, 'range', 'category'])
                    /*->join('4ever_mrp as mrp','mrp.product_id','=','4ever_product.id')
                    ->select('4ever_product.id','4ever_product.product_name','4ever_product.pack_size','4ever_product.short_code','mrp.mrp','mrp.id as mrp_id','range_id','product_category_id','brand_id')
                    ->whereNull('mrp.ended_at')*/
                    ->where('product_category_id', $category)
                    ->where('status', 1)
                    ->whereNotIn('4ever_product.id', $expt)
                    ->get();
            } else {
                return Product::with(['custom_price_book' => function ($query) use ($outlet) {
                    $query->select('4ever_price_book_detail.*', 'cpb.*')
                        ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                        ->join('4ever_price_book_custom as cpb', 'pb.id', '=', 'cpb.price_book_id')
                        ->whereRaw('pb.type = 2 and pb.category = 3 and cpb.user_id=' . $outlet . ' and effective_date <= curDate() and (cpb.ended_at is null or cpb.ended_at >= curDate())');
                }, 'stranded_price_book' => function ($query) {
                    $query->select('4ever_price_book_detail.*')
                        ->join('4ever_price_book as pb', 'pb.id', '=', '4ever_price_book_detail.price_book_id')
                        ->where('pb.type', 1)
                        ->where('pb.category', 3);
                }, 'stock' => function ($query) use ($warehouse) {
                    $query->join('4ever_mrp as mrp', 'mrp.id', '=', '4ever_stock.mrp_id')->where('warehouse_id', $warehouse->id);
                }, 'range', 'category'])
                    /*->join('4ever_mrp as mrp','mrp.product_id','=','4ever_product.id')
                    ->select('4ever_product.id','4ever_product.product_name','4ever_product.pack_size','4ever_product.short_code','mrp.mrp','mrp.id as mrp_id','range_id','product_category_id','brand_id')
                    ->whereNull('mrp.ended_at')*/
                    ->where('brand_id', $brand)
                    ->where('status', 1)
                    ->whereNotIn('4ever_product.id', $expt)
                    ->get();
            }
        }
    }

    /**
     * insert invoice of order
     */
    public function add(Request $request)
    {
        //return $request->get('orderList');
        $orderList = $request->get('orderList');
        $invId = array();
        for ($i = 0; $i < sizeof($orderList); $i++) {
            $order = SalesOrder::with('details', 'discounts')->find($orderList[$i]);
            array_push($invId, $this->insertInvoiceOfWeb($order));
        }
        return response()->json(implode(',', $invId));
    }

    /**
     * insert invoice of amend order
     */
    public function addAmendInvoice(Request $request)
    {
        $order = $request->get('invoice');
        $invoiceId = 0;
        try {
            DB::transaction(function () use ($order, &$invoiceId) {

                $location = Location::find($order['invoice']['location_id']);
                $loc = $location->ancestors()->get();
                $total = 0;

                $rep = Employee::select('parent')->find($order['invoice']['rep_id']);
                $invoiceObj = Invoice::create([
                    'rep_id' => $rep->parent,
                    'location_id' => $order['invoice']['location_id'],
                    'loading_id' => $order['invoice']['loading_id'],
                    'payment_status' => $order['invoice']['payment_status'],
                    'total' => $order['invoice']['total'],
                    'manual_id' => $this->generateNo($rep->parent, 'WI', 0),
                    'lat' => $order['invoice']['lat'],
                    'lon' => $order['invoice']['lon'],
                    'territory_id' => $loc[3]->id,
                    'route_id' => $loc[4]->id,
                    'discount' => 0,
                    'brand_id' => $order['invoice']['brand_id'],
                    'order_id' => $order['invoice']['id'],
                    'device' => 0,
                    'delivery_status' => 0,
                    'created_date' => date('Y-m-d H:i:s')
                ]);
                $invoiceId = $invoiceObj->id;
                if (!$invoiceObj) {
                    throw new TransactionException('Something wrong.invoice wasn\'t updated', 100);
                }
                //$i =0;
                foreach ($order['invoiceDetail'] as $detail) {

                    if (is_array($detail)) {
                        $stocks = StockHandler::getDistributorStockOf($rep->parent, $detail['product_id']);
                        /* if($i>=1){
                             return $stocks;
                         }
                         $i++;*/
                        $qty = $detail['qty'];

                        foreach ($stocks as $stock) {
                            if ($stock->qty > 0) {
                                if ($stock->qty >= $qty) {
                                    $detailObj = InvoiceDetail::create([
                                        'product_id' => $detail['product_id'],
                                        'batch_id' => $stock->batch_id,
                                        'unit_price' => $detail['unit_price'],
                                        'qty' => $qty,
                                        'free_qty' => $detail['free_qty'],
                                        'invoice_id' => $invoiceObj->id,
                                        'group_id' => $detail['group_id'],
                                        'order_detail_type' => 1,
                                        'price_book_detail_id' => $detail['price_book_detail_id'],
                                    ]);
                                    $total += floatval($detail['unit_price']) * $qty;
                                    if (!$detailObj) {
                                        throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                                    } else {
                                        if ($qty > 0) {
                                            $transactionObj = StockHandler::stockOut($qty, $detail['product_id'], $stock->batch_id, $rep->parent, $invoiceObj->id, 1, 1);
                                            if (!$transactionObj) {
                                                throw new TransactionException('Something wrong.stock wasn\'t updated', 100);
                                            }
                                        }
                                    }
                                    break;
                                } else {
                                    $qty -= $stock->qty;
                                    $detailObj = InvoiceDetail::create([
                                        'product_id' => $detail['product_id'],
                                        'batch_id' => $stock->batch_id,
                                        'unit_price' => $detail['unit_price'],
                                        'qty' => $stock->qty,
                                        'free_qty' => $detail['free_qty'],
                                        'invoice_id' => $invoiceObj->id,
                                        'group_id' => $detail['group_id'],
                                        'order_detail_type' => 1,
                                        'price_book_detail_id' => $detail['price_book_detail_id'],
                                    ]);
                                    $total += floatval($detail['unit_price']) * $stock->qty;
                                    if (!$detailObj) {
                                        throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                                    } else {
                                        if ($stock->qty > 0) {
                                            $transactionObj = StockHandler::stockOut($stock->qty, $detail['product_id'], $stock->batch_id, $rep->parent, $invoiceObj->id, 1, 1);
                                            if (!$transactionObj) {
                                                throw new TransactionException('Something wrong.stock wasn\'t updated', 100);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (isset($order['invoiceFreeIssue'])) {
                    foreach ($order['invoiceFreeIssue'] as $detail) {
                        if (is_array($detail)) {
                            $stocks = StockHandler::getDistributorStockOf($rep->parent, $detail['product_id']);
                            /* if($i>=1){
                                 return $stocks;
                             }
                             $i++;*/
                            $qty = $detail['qty'];
                            $type = $detail['gorup_id'] > 0 ? 2 : 3;
                            foreach ($stocks as $stock) {
                                if ($stock->qty > 0) {
                                    if ($stock->qty >= $qty) {
                                        $detailObj = InvoiceDetail::create([
                                            'product_id' => $detail['product_id'],
                                            'batch_id' => $stock->batch_id,
                                            'unit_price' => 0,
                                            'qty' => 0,
                                            'free_qty' => $qty,
                                            'invoice_id' => $invoiceObj->id,
                                            'order_detail_type' => $type,
                                            'group_id' => $detail['gorup_id'],
                                            'price_book_detail_id' => $detail['mrp'],
                                        ]);
                                        if (!$detailObj) {
                                            throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                                        } else {
                                            if ($qty > 0) {
                                                $transactionObj = StockHandler::stockOut($qty, $detail['product_id'], $stock->batch_id, $rep->parent, $invoiceObj->id, 1, 1);
                                                if (!$transactionObj) {
                                                    throw new TransactionException('Something wrong.stock wasn\'t updated', 100);
                                                }
                                            }
                                        }
                                        break;
                                    } else {
                                        $qty -= $stock->qty;
                                        $detailObj = InvoiceDetail::create([
                                            'product_id' => $detail['product_id'],
                                            'batch_id' => $stock->batch_id,
                                            'unit_price' => 0,
                                            'qty' => 0,
                                            'free_qty' => $stock->qty,
                                            'invoice_id' => $invoiceObj->id,
                                            'order_detail_type' => $type,
                                            'group_id' => $detail['gorup_id'],
                                            'price_book_detail_id' => $detail['mrp'],
                                        ]);
                                        if (!$detailObj) {
                                            throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                                        } else {
                                            if ($stock->qty > 0) {
                                                $transactionObj = StockHandler::stockOut($stock->qty, $detail['product_id'], $stock->batch_id, $rep->parent, $invoiceObj->id, 1, 1);
                                                if (!$transactionObj) {
                                                    throw new TransactionException('Something wrong.stock wasn\'t updated', 100);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (isset($order['invoiceDiscount'])) {
                    foreach ($order['invoiceDiscount'] as $discount) {
                        $discountType = $discount['group_id'] > 0 ? 1 : 2;
                        $inDiscountObj = InvoiceDiscount::create([
                            'rule_id' => $discount['rule_id'],
                            'invoice_id' => $invoiceObj->id,
                            'product_id' => $discount['product'],
                            'discount' => $discount['discount'],
                            'added_date' => date('y-M-d h:M:s'),
                            'discount_type' => $discountType,
                            'discount_group_id' => $discount['group_id'],
                        ]);
                        if (!$inDiscountObj) {
                            throw new TransactionException('Something wrong.discount wasn\'t updated', 100);
                        }
                    }
                }

                $salesOrder = SalesOrder::find($order['invoice']['sales_order_id']);
                $salesOrder->sales_order_approve_status = 2;
                $salesOrder->save();
                if (!$salesOrder) {
                    throw new TransactionException('Something wrong.sales order approve status wasn\'t updated', 100);
                }

                $inv = Invoice::find($invoiceObj->id);
                $inv->total = $total;
                $inv->save();
                if (!$inv) {
                    throw new TransactionException('Something wrong.invoice total wasn\'t updated', 100);
                }

                return response()->json($invoiceObj->id);
            });

        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return response()->json('#');
            } else if ($e->getCode() == 101) {
                return response()->json('#');
            }
        } catch (Exception $e) {
            return response()->json('#');
        }
        return response()->json($invoiceId);

    }

    /**
     * insert invoice of order
     */
    public function toPrint(Request $request)
    {
        //create pdf object
        $data = ['no' => ''];
        $pdf = new InvoicePdfTemplate($data);


        $invoice_ids = $request->ids;
        $ids = explode(',', $invoice_ids);
        $invoices = '';
        foreach ($ids as $id) {

            $pdf->SetMargins(10, 15, 10);
            $pdf->setHeaderTemplateAutoreset(true);

            $invoices = Invoice::with(['customer', 'discounts', 'employee', 'create_by', 'details' => function ($query) {
                $query->select('free_qty', 'qty', 'unit_price', 'invoice_id', 'discount', 'product_id', 'editor')
                    ->with('product.sizes', 'product.mrp', 'editor_');
            }, 'recipt'])->find($id);

            $credit_amt = CreditNote::where('invoice_id', $id)->sum('credit_amount');
            $credit_amt = $credit_amt ?: 0;

            if (sizeof($invoices) > 0) {
                $id = $invoices->employee->employee_type_id == 5 ? $invoices->employee->parent : $invoices->employee->id;
                $distributor = Employee::where('id', $id)->get();
                //$brand = Product_Brand::where('id', $invoices->brand_id)->get();
            }

            $distributor_data = [
                'name' => $distributor[0]->first_name . ' ' . $distributor[0]->last_name,
                'address' => str_replace(',', '<br>', $distributor[0]->address),
                'mobile' => $distributor[0]->mobile,
                'cheque_name' => $distributor[0]->cheque_name
            ];

            $sql = 'select
                        format(ifnull(sum(tmp.remain),0),2) as remain,count(tmp.id) as count from
                        (select
                           invoice.id,
                           total,
                           (IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = invoice.id group by invoice.id ),0) - (select IFNULL(sum(discount),0) from invoice_discount where invoice_id=invoice.id))-IFNULL((select sum(payment_amount) from recipt_detail where invoice_id=invoice.id),0) - IFNULL( ( SELECT sum( credit_amount ) FROM credit_note WHERE invoice_id = invoice.id ), 0 ) as remain
                         from invoice
                         where location_id=' . $invoices->location_id . ' AND invoice.deleted_at is NULL
                         ) as tmp where tmp.remain > 0';

            $outlet_invoice_details = DB::select($sql);

            //return $invoices;
            if (count($invoices) > 0) {
                $page1 = view('invoiceManage::print.invoice')->with(['invoice' => $invoices, 'distributor_data' => $distributor_data, 'pdf' => $pdf, 'outlet_invoice_details' => $outlet_invoice_details[0], 'credit_amt' => $credit_amt])->render();
            } else {
                return response()->view("errors.404");
            }


            $pdf->SetHeaderData(array($distributor_data, $invoices), 0, '', '', array(), array());
            $pdf->setPrintHeader(true);
            $pdf->SetMargins(28.35 / $pdf->k, 80);
            $pdf->SetAutoPageBreak(TRUE, 35);
            $pdf->AddPage();
            $pdf->writeHtml($page1);

        }

        $pdf->output("test.pdf", 'I');

    }

    /**
     * insert invoice on orders
     * @param $order
     */
    public function insertInvoiceOfWeb($order)
    {
        $inId = 0;
        try {
            DB::transaction(function () use ($order, &$inId) {

                $rep = Employee::select('parent')->find($order->rep_id);
                Log::info($order->id);
                $invoiceObj = Invoice::create([
                    'rep_id' => $order->rep_id,
                    'location_id' => $order->location_id,
                    'loading_id' => $order->loading_id,
                    'payment_status' => $order->payment_status,
                    'total' => $order->total,
                    'manual_id' => $this->generateNo($order->rep_id, 'WI', 0),
                    'lat' => $order->lat,
                    'lon' => $order->lon,
                    'territory_id' => $order->territory_id,
                    'route_id' => $order->route_id,
                    'discount' => $order->discount,
                    'brand_id' => $order->brand_id,
                    'order_id' => $order->id,
                    'payment_type' => $order->payment_type,
                    'device' => 0,
                    'delivery_status' => 0,
                    'created_date' => date('Y-m-d H:i:s')
                ]);
                if (!$invoiceObj) {
                    throw new TransactionException('Something wrong.invoice wasn\'t updated', 100);
                }
                $inId = $invoiceObj->id;
                //$i =0;
                foreach ($order->details as $detail) {
                    $stocks = StockHandler::getDistributorStockOf($rep->parent, $detail->product_id);
                    /* if($i>=1){
                         return $stocks;
                     }
                     $i++;*/
                    $qty = $detail->qty;
                    //$free_qty = $detail->free_qty;
                    $tmp_free_qty = $detail->free_qty;
                    $totQty = $qty + $tmp_free_qty;
                    //$inv_qty = $qty;

                    foreach ($stocks as $stock) {
                        if ($stock->qty > 0) {
                            if ($stock->qty >= $totQty) {
                                $detailObj = InvoiceDetail::create([
                                    'product_id' => $detail->product_id,
                                    'batch_id' => $stock->batch_id,
                                    'unit_price' => $detail->unit_price,
                                    'qty' => ($totQty - $tmp_free_qty),
                                    'free_qty' => $tmp_free_qty,
                                    'invoice_id' => $invoiceObj->id,
                                    'group_id' => $detail->group_id,
                                    'order_detail_type' => $detail->order_detail_type,
                                    'price_book_detail_id' => $detail->price_book_detail_id,
                                ]);
                                if (!$detailObj) {
                                    throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                                } else {
                                    if ($totQty > 0) {
                                        $transactionObj = StockHandler::stockOut($totQty, $detail->product_id, $stock->batch_id, $rep->parent, $invoiceObj->id, 1, 1);
                                        if (!$transactionObj) {
                                            throw new TransactionException('Something wrong.stock wasn\'t updated', 100);
                                        }
                                    }
                                }
                                break;
                            } else {
                                $totQty -= $stock->qty;
                                if ($qty >= $tmp_free_qty) {
                                    $detailObj = InvoiceDetail::create([
                                        'product_id' => $detail->product_id,
                                        'batch_id' => $stock->batch_id,
                                        'unit_price' => $detail->unit_price,
                                        'qty' => $stock->qty,
                                        'free_qty' => 0,
                                        'invoice_id' => $invoiceObj->id,
                                        'group_id' => $detail->group_id,
                                        'order_detail_type' => $detail->order_detail_type,
                                        'price_book_detail_id' => $detail->price_book_detail_id,
                                    ]);
                                } else {
                                    $detailObj = InvoiceDetail::create([
                                        'product_id' => $detail->product_id,
                                        'batch_id' => $stock->batch_id,
                                        'unit_price' => $detail->unit_price,
                                        'qty' => 0,
                                        'free_qty' => $stock->qty,
                                        'invoice_id' => $invoiceObj->id,
                                        'group_id' => $detail->group_id,
                                        'order_detail_type' => $detail->order_detail_type,
                                        'price_book_detail_id' => $detail->price_book_detail_id,
                                    ]);
                                    $tmp_free_qty -= $stock->qty;
                                }

                                if (!$detailObj) {
                                    throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                                } else {
                                    if ($stock->qty > 0) {
                                        $transactionObj = StockHandler::stockOut($stock->qty, $detail->product_id, $stock->batch_id, $rep->parent, $invoiceObj->id, 1, 1);
                                        if (!$transactionObj) {
                                            throw new TransactionException('Something wrong.stock wasn\'t updated', 100);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (sizeof($order->discounts) > 0) {
                    foreach ($order->discounts as $discount) {
                        $inDiscountObj = InvoiceDiscount::create([
                            'rule_id' => $discount->rule_id,
                            'invoice_id' => $invoiceObj->id,
                            'product_id' => $discount->product_id,
                            'discount' => $discount->discount,
                            'added_date' => date('y-M-d h:M:s'),
                            'discount_type' => $discount->discount_type,
                            'discount_group_id' => $discount->discount_group_id,
                        ]);
                        if (!$inDiscountObj) {
                            throw new TransactionException('Something wrong.discount wasn\'t updated', 100);
                        }
                    }
                }

                $salesOrder = SalesOrder::find($order->id);
                $salesOrder->sales_order_approve_status = 2;
                $salesOrder->save();
                if (!$salesOrder) {
                    throw new TransactionException('Something wrong.sales order approve status wasn\'t updated', 100);
                }
                return $invoiceObj->id;
            });
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return response()->json('#');
            } else if ($e->getCode() == 101) {
                return response()->json('#');
            }
        } catch (Exception $e) {
            return response()->json('#');
        }
        return $inId;
    }

    public function listOutlet()
    {
        return view('invoiceManage::outlet.list');
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
        where('status', 1)->
        skip($request->get('start'))->
        take($request->get('length'))->
        get();
        $user = Sentinel::getUser();
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
                           (IFNULL((select sum((ind.qty * ind.unit_price) - ifnull(ind.discount,0)) from invoice_detail as ind where ind.invoice_id = invoice.id group by invoice.id ),0) - (select IFNULL(sum(discount),0) from invoice_discount where invoice_id=invoice.id))-IFNULL((select sum(payment_amount) from recipt_detail where invoice_id=invoice.id),0) as remain
                         from invoice
                         where location_id=' . $value['id'] . ' AND deleted_at is null
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
                $outlet_tel = $outlet_tel . '<br><span class="icon"><i class="fa fa-phone"></i></span> ' . $value->mobile;
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

            /* } else {
                 array_push($aa, '-');
             }*/
            array_push($aa, $outlet_tel . '<br>' . $outlet_fax);
            array_push($aa, $outlet_email . '<br>' . $outlet_address);
            array_push($aa, '<span class="badge" style="background-color:green">' .
                $outlet_invoice_details[0]->remain . '</span>');
            array_push($aa, 'Limit - ' . $value->credit_limit . '<br> Period - ' . $value->credit_period);

            $permissions = Permission::whereIn('name', ['invoice.add', 'admin'])->where('status', '=', 1)->pluck('name');
            if ($user->hasAnyAccess($permissions)) {
                array_push($aa, '<a href="#" class="gray" onclick="window.location.href=\'' . url('invoice/add/' . $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="Create New Invoice"><i class="fa fa-file-text"></i></a>');
            } else {
                array_push($aa, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Create New Invoice Disabled - Persmisson Deined"><i class="fa fa-file-text"></i></a>');
            }

            array_push($data, $aa);

        }
        //array_pop($data);

        return Response::json(['draw' => (int)$request->get('draw'),
            'recordsTotal' => (int)Customer::get(['id'])->count(),
            'recordsFiltered' => (int)Customer::whereRaw('(CONCAT(LOWER(TRIM(f_name)), " " ,LOWER(TRIM(l_Name)))  like "%' . $search . '%" )')->count(), "data" => $data]);


    }


    /**
     * get the outlets.
     *
     * @return Response with pagination
     */
    public function getInvoices(Request $request)
    {
        //return $request->id;
        $user = Sentinel::getUser();

        $search = $request->get('search')['value'];
        if (strlen($search) == 0) {
            if ($request->get('length') != -1) {
                if ($request->id > 0) {
                    $emps = Employee::find($request->id)->getDescendantsAndSelf()->pluck('id');
                    $rowData = Invoice::with(['employee', 'details.product', 'customer'])
                        ->whereIn('rep_id', $emps)
                        ->skip($request->get('start'))
                        ->take($request->get('length'))
                        ->orderBy('created_date', 'DESC')
                        ->get();
                } elseif ($request->id == 0) {
                    $rowData = Invoice::with(['employee', 'details.product', 'customer'])
                        ->skip($request->get('start'))
                        ->take($request->get('length'))
                        ->orderBy('created_date', 'DESC')
                        ->get();
                }
            } else {
                $all = Employee::find($user->employee_id)->getDescendantsAndSelf()->pluck('id');
                $rowData = Invoice::with(['employee', 'details.product', 'customer'])
                    ->whereIn('rep_id', $all)
                    ->skip($request->get('start'))
                    ->take($request->get('length'))
                    ->orderBy('created_date', 'DESC')
                    ->get();
            }
        } else {
            if ($request->get('length') != -1) {
                if ($request->id > 0) {
                    $emps = Employee::find($request->id)->getDescendantsAndSelf()->pluck('id');
                    $rowData = Invoice::with(['employee', 'details.product', 'customer'])
                        ->whereIn('rep_id', $emps)
                        ->whereRaw('(manual_id like "%' . $search . '%" OR created_date like "%' . $search . '%")')
                        ->skip($request->get('start'))
                        ->take($request->get('length'))
                        ->orderBy('created_date', 'DESC')
                        ->get();
                } elseif ($request->id == 0) {
                    $rowData = Invoice::with(['employee', 'details.product', 'customer'])
                        ->whereRaw('(manual_id like "%' . $search . '%" OR created_date like "%' . $search . '%")')
                        ->skip($request->get('start'))
                        ->take($request->get('length'))
                        ->orderBy('created_date', 'DESC')
                        ->get();
                }
            } else {

                $rowData = Invoice::with(['employee', 'details.product', 'customer'])
                    ->whereRaw('(manual_id like "%' . $search . '%" OR created_date like "%' . $search . '%")')
                    ->skip($request->get('start'))
                    ->take($request->get('length'))
                    ->orderBy('created_date', 'DESC')
                    ->get();
            }
        }

        $data = array();

        foreach ($rowData as $key => $value) {
            $aa = array();
            array_push($aa, $value->id);
            array_push($aa, $key + 1);
            array_push($aa, $value->manual_id);
            array_push($aa, $value->created_date);
            array_push($aa, number_format(($value->total) - $value->discounts->sum('discount'), 2));
            array_push($aa, $value->customer->f_name . ' ' . $value->customer->l_name);
            //array_push($aa,$value->manual_id);
            array_push($aa, $value->employee->first_name . ' ' . $value->employee->last_name);

            $permissions = Permission::whereIn('name', ['invoice.print', 'admin'])->where('status', '=', 1)->pluck('name');
            if ($user->hasAnyAccess($permissions)) {
                array_push($aa, '<a href="#" class="gray" onclick="window.location.href=\'' . url('invoice/print?ids=' . $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="Outlet Details"><i class="fa fa-print"></i></a>');
            } else {
                array_push($aa, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Outlet View Disabled - Persmisson Deined"><i class="fa fa-eye"></i></a>');
            }
            array_push($data, $aa);
        }
        return Response::json(['draw' => (int)$request->get('draw'),
            'recordsTotal' => (int)Invoice::get(['id'])->count(),
            'recordsFiltered' => (int)Invoice::get(['id'])->count(), "data" => $data]);
    }

    /**
     * insert invoice of amend order
     */
    public function addNewInvoice(Request $request)
    {
        $order = $request->get('invoice');
        $inId = 0;
        //return response()->json($order['invoice']['rep_id']);

        try {
            DB::transaction(function () use ($order, &$inId) {

                $total = 0;
                $disc = 0;
                $aging_total = 0;
                $user = Sentinel::getUser();
                $loc = array();
                $createdDate = date('y-m-d H:i:s');
                if ($order['invoice']['created_date']) {
                    $createdDate = $order['invoice']['created_date'] . ' 00:00:00';
                }
                $invoiceObj = Invoice::create([
                    'rep_id' => $order['invoice']['rep_id'],
                    'location_id' => $order['invoice']['location_id'],
                    'loading_id' => $order['invoice']['loading_id'],
                    'payment_status' => $order['invoice']['payment_status'],
                    'total' => $order['invoice']['total'],
                    'manual_id' => $this->generateNo($order['invoice']['rep_id'], 'WI', 0),
                    'job_no' => $order['invoice']['job_no'],/*$this->generateNo($order['invoice']['rep_id'], 'JOB', 0),*/
                    'lat' => $order['invoice']['lat'],
                    'lon' => $order['invoice']['lon'],
                    'territory_id' => 0,
                    'route_id' => 0,
                    'discount' => 0,
                    'brand_id' => $order['invoice']['brand_id'],
                    'couple_name' => $order['invoice']['couple_name'],
                    'payment_type' => $order['payment_type'],
                    'remark' => $order['invoice']['comment'],
                    'device' => 0,
                    'delivery_status' => 0,
                    'created_by' => $user->employee_id,
                    'created_date' => $createdDate
                ]);
                $inId = $invoiceObj->id;


                if (!$invoiceObj) {
                    throw new TransactionException('Something wrong.invoice wasn\'t updated', 100);
                }

                $aging = PaymentAgingReport::where('manual_id', $invoiceObj->manual_id)->first();

                if (!$aging) {
                    $customer = Customer::find($order['invoice']['location_id']);
                    $date1 = date_create($invoiceObj->created_date);
                    $date2 = date_create(date('y-m-d'));
                    $diff = date_diff($date1, $date2);
                    $aging = PaymentAgingReport::create([
                        'manual_id' => $invoiceObj->manual_id,
                        'job_no' => $invoiceObj->job_no,
                        'customer_name' => $customer->f_name . ' ' . $customer->l_name,
                        'total' => $invoiceObj->total,
                        'invoice_due' => $invoiceObj->total,
                        'no_of_days' => $diff->format('%a'),
                        'created_date' => $invoiceObj->created_date,
                        'marketeer_id' => $invoiceObj->rep_id,
                        'customer' => $invoiceObj->location_id,
                        'area' => $customer->area,
                        'couple_name' => $invoiceObj->couple_name,
                    ]);
                }

                foreach ($order['invoiceDetail'] as $detail) {
                    if (sizeof($detail) > 1) {
                        $qty = $detail['qty'];
                        $editor = 0;
                        if ($detail['free_qty'] > 0) {
                            $editor = $detail['free_qty'];
                        }
                        $detailObj = InvoiceDetail::create([
                            'product_id' => $detail['product_id'],
                            'batch_id' => 0,
                            'unit_price' => $detail['unit_price'],
                            'discount' => $detail['discount'],
                            'qty' => $qty,
                            'free_qty' => 0,
                            'invoice_id' => $invoiceObj->id,
                            'order_detail_type' => 1,
                            'group_id' => $detail['group_id'],
                            'editor' => $editor,
                            'price_book_detail_id' => $detail['price_book_detail_id'],
                        ]);
                        $total += floatval($detail['unit_price']) * $qty;
                        $aging_total += (floatval($detail['unit_price']) * $qty) - floatval($detail['discount']);
                        if (!$detailObj) {
                            throw new TransactionException('Something wrong.invoice detail wasn\'t updated', 100);
                        }
                    }
                }
                if (isset($order['invoiceDiscount'])) {
                    foreach ($order['invoiceDiscount'] as $discount) {
                        $discountType = $discount['group_id'] > 0 ? 1 : 2;
                        $inDiscountObj = InvoiceDiscount::create([
                            'rule_id' => $discount['rule_id'],
                            'invoice_id' => $invoiceObj->id,
                            'product_id' => $discount['product'],
                            'discount' => $discount['discount'],
                            'added_date' => date('y-M-d h:M:s'),
                            'discount_type' => $discountType,
                            'discount_group_id' => $discount['group_id'],
                        ]);
                        $aging_total -= floatval($discount['discount']);
                        //$disc += floatval($discount['discount']);
                        if (!$inDiscountObj) {
                            throw new TransactionException('Something wrong.discount wasn\'t updated', 100);
                        }
                    }
                }
                $inv = Invoice::find($invoiceObj->id);
                $inv->total = $total;
                $inv->manual_id = 'WI-'.$inv->rep_id.'-'.$inv->id;
                $inv->save();
                $agn = PaymentAgingReport::find($aging->id);
                $agn->total = $aging_total;
                $agn->manual_id = 'WI-'.$inv->rep_id.'-'.$inv->id;
                $agn->invoice_due = $aging_total;
                $agn->save();
                if (!$inv) {
                    throw new TransactionException('Something wrong.invoice total wasn\'t updated', 100);
                }
                return response()->json($inv->id);

            });

        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return response()->json('#');
            } else if ($e->getCode() == 101) {
                return response()->json('#');
            }
        } catch (Exception $e) {
            return response()->json('#');
        }
        return response()->json($inId);

    }

    private function generateNo($user, $prefix, $device)
    {
        $count = DB::select('SELECT count(id) as  `count` FROM invoice WHERE device = ' . $device . ' AND rep_id = ' . $user);
        $count = $count[0]->count;
        //$count = Invoice::where('device', $device)->where('rep_id', $user)->count();
        $count++;
        return $prefix . '-' . $user . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * invoice list
     */


    public function invoiceList()
    {
        $log_user = Sentinel::getUser();
        $customerList = Customer::all();
        //$invoiceList = [];
        if ($log_user->id > 1) {
            if (trim($log_user->roles[0]->name) == 'marketer') {
                $marketeerList = Employee::where('id', $log_user->employee_id)->get();
                $invoiceList = Invoice::with('details', 'employee', 'customer', 'discounts')->where('rep_id', $log_user->employee_id);
            } elseif (trim($log_user->roles[0]->name) == 'customer') {
                $customer = Customer::where('user_id', $log_user->id)->first();
                $customerList = Customer::where('user_id', $log_user->id)->get();
                $marketeerList = [];
                $invoiceList = Invoice::with('details', 'employee', 'customer', 'discounts')->where('location_id', $customer->id);
            } else {
                $marketeerList = Employee::where('employee_type_id', 2)->get();
                $invoiceList = Invoice::with('details', 'employee', 'customer', 'discounts');
            }
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $invoiceList = Invoice::with('details', 'employee', 'customer', 'discounts');
        }

        $invoiceList = $invoiceList->orderBy('created_date', 'DESC')->paginate(20);


        //return ['orders' => $invoiceList, 'invoice_no' => '', 'marketeerList' => $marketeerList, 'marketeer' => '', 'from' => '', 'to' => ''];
        return view('invoiceManage::list')->with(['orders' => $invoiceList, 'invoice_no' => '', 'marketeerList' => $marketeerList, 'marketeer' => '', 'from' => '', 'to' => '', 'customerList' => $customerList, 'customer' => '', 'payment_type' => '']);
    }

    public function search(Request $request)
    {

        $log_user = Sentinel::getUser();
        $customerList = Customer::all();
        if (trim($log_user->roles[0]->name) == 'marketer') {
            $marketeerList = Employee::where('id', $log_user->employee_id)->get();

            $orders = Invoice::with('employee', 'discounts');

            if (strlen($request->get('invoice_no')) > 0) {
                $orders = $orders->where('manual_id', $request->get('invoice_no'));
            }
            if (strlen($request->get('from')) > 0) {
                $from = $request->get('from');
                if (strlen($request->get('to')) > 0) {
                    $to = $request->get('to');
                } else {
                    $to = date('Y-m-d');
                }
                $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
            }
            /*if ($request->get('status')) {
                $orders = $orders->where('purchase_order_type', $request->get('status'));
            }*/
            if ($request->get('marketeer')) {
                $orders = $orders->where('rep_id', $request->get('marketeer'));
            }

            if ($request->get('payment_type')) {
                $orders = $orders->where('payment_type', $request->get('payment_type'));
            }

            if ($request->get('customer')) {
                $orders = $orders->where('location_id', $request->get('customer'));
            }

            $orders = $orders->where('rep_id', $log_user->employee_id);
            $orders = $orders->orderBy('created_date', 'DESC');
            $orders = $orders->paginate(20);

        } elseif (trim($log_user->roles[0]->name) == 'customer') {
            $marketeerList = [];
            $customer = Customer::where('user_id', $log_user->id)->first();
            $customerList = Customer::where('user_id', $log_user->id)->get();

            $orders = Invoice::with('employee', 'discounts');

            if (strlen($request->get('invoice_no')) > 0) {
                $orders = $orders->where('manual_id', $request->get('invoice_no'));
            }
            if (strlen($request->get('from')) > 0) {
                $from = $request->get('from');
                if (strlen($request->get('to')) > 0) {
                    $to = $request->get('to');
                } else {
                    $to = date('Y-m-d');
                }
                $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
            }
            /*if ($request->get('status')) {
                $orders = $orders->where('purchase_order_type', $request->get('status'));
            }*/
            if ($request->get('marketeer')) {
                $orders = $orders->where('rep_id', $request->get('marketeer'));
            }

            if ($request->get('payment_type')) {
                $orders = $orders->where('payment_type', $request->get('payment_type'));
            }

            if ($request->get('customer')) {
                $orders = $orders->where('location_id', $request->get('customer'));
            }

            $orders = $orders->where('location_id', $customer->id);
            $orders = $orders->orderBy('created_at', 'DESC');
            $orders = $orders->paginate(20);
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $orders = Invoice::with('employee', 'discounts', 'details');

            if (strlen($request->get('invoice_no')) > 0) {
                $orders = $orders->whereRaw('(manual_id = "' . $request->get('invoice_no') . '" OR job_no="' . $request->get('invoice_no') . '"' . ' OR couple_name LIKE "%' . $request->get('invoice_no') . '%")');
            }
            if (strlen($request->get('from')) > 0) {
                $from = $request->get('from');
                if (strlen($request->get('to')) > 0) {
                    $to = $request->get('to');
                } else {
                    $to = date('Y-m-d');
                }
                $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
            }
            /*if ($request->get('status')) {
                $orders = $orders->where('purchase_order_type', $request->get('status'));
            }*/
            if ($request->get('marketeer')) {
                $orders = $orders->where('rep_id', $request->get('marketeer'));
            }

            if ($request->get('payment_type')) {
                $orders = $orders->where('payment_type', $request->get('payment_type'));
            }

            if ($request->get('customer')) {
                $orders = $orders->where('location_id', $request->get('customer'));
            }

            $orders = $orders->orderBy('created_at', 'DESC');
            $orders = $orders->paginate(20);
        }

        return view('invoiceManage::list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'invoice_no' => $request->get('invoice_no'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'customerList' => $customerList, 'customer' => $request->get('customer'), 'payment_type' => $request->get('payment_type')]);
    }

    public function delete(Request $request)
    {
        $is_paid = ReciptDetail::where('invoice_id', $request->order_id)->first();
        if (!$is_paid) {
            $invoice = Invoice::where('id', $request->order_id)->first();
            if ($invoice) {
                $invoice->delete();
                $aging = PaymentAgingReport::where('manual_id', $invoice->manual_id)->first();
                $aging->delete();
                return 1;
            } else {
                return 0;
            }
        } else {
            return -1;
        }

    }

    public function agingDownload(Request $request)
    {

        // return $this->generateNo(5,'WI',0);
        $orders = Invoice::with('employee', 'discounts', 'details')
            ->select('invoice.id', 'created_date', 'manual_id', 'invoice.location_id', 'invoice.rep_id', 'invoice.payment_type')
            ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id');

        if (strlen($request->get('invoice_no')) > 0) {
            $orders = $orders->whereRaw('(manual_id = "' . $request->get('invoice_no') . '" OR job_no="' . $request->get('invoice_no') . '")');
            $no = $request->get('invoice_no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('marketeer')) {
            $orders = $orders->where('rep_id', $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        $payment_type = 'All';
        if ($request->get('payment_type')) {
            $orders = $orders->where('payment_type', $request->get('payment_type'));
            $payment_type = $request->get('payment_type');
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

        $orders = $orders->groupBy('invoice.id');
        $orders = $orders->orderBy('f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'payment_type' => $payment_type];

        //return $orders;
        //Log::info($orders);

        if ($orders) {
            $page1 = view('invoiceManage::print.invoice-aging')->with(['orders' => $orders, 'page_header' => $header]);
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

    public function agingExcel(Request $request)
    {

        // return $this->generateNo(5,'WI',0);
        $orders = Invoice::with('employee', 'discounts', 'details')
            ->select('invoice.id', 'created_date', 'manual_id', 'invoice.location_id', 'invoice.rep_id', 'invoice.payment_type')
            ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id');

        if (strlen($request->get('invoice_no')) > 0) {
            $orders = $orders->whereRaw('(manual_id = "' . $request->get('invoice_no') . '" OR job_no="' . $request->get('invoice_no') . '")');
            $no = $request->get('invoice_no');
        } else {
            $no = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }
        /*if ($request->get('status')) {
            $orders = $orders->where('purchase_order_type', $request->get('status'));
        }*/
        if ($request->get('marketeer')) {
            $orders = $orders->where('rep_id', $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        $payment_type = 'All';
        if ($request->get('payment_type')) {
            $orders = $orders->where('payment_type', $request->get('payment_type'));
            $payment_type = $request->get('payment_type');
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

        $orders = $orders->groupBy('invoice.id');
        $orders = $orders->orderBy('f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'payment_type' => $payment_type];

        //return $orders;
        //Log::info($orders);

        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/sales_details.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('F' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('F' . 3, $header['to']);
                $excel->getActiveSheet()->setCellValue('F' . 4, $header['payment_type']);

                foreach ($orders as $detail) {
                    $total = 0;
                    foreach ($detail->details as $order)
                        $total += (($order->unit_price * $order->qty) - $order->discount);

                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->created_date);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $detail->manual_id);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $detail->payment_type);
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, number_format($total - (is_object($detail->discounts) ? $detail->discounts->discount : 0), 2));
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('invoice/aging/excel')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function adminAuthentication(Request $request)
    {
        $credentials = [
            'username' => 'yasasp',
            'password' => $request->password,
        ];

        $user = Sentinel::findUserById(4);

        if (Sentinel::validateCredentials($user, $credentials)) {
            return 1;
        } else {
            $credentials = [
                'username' => 'amila',
                'password' => $request->password,
            ];

            $user = Sentinel::findUserById(5);

            if (Sentinel::validateCredentials($user, $credentials)) {
                return 1;
            }
            return 0;
        }
    }

    public function getData($id, Request $request)
    {

        $customer = Customer::with('marketeer')->find($id);

        $marketeer_outstanding = DB::select("SELECT SUM(tt.invoice_due) as outstanding,COUNT(tt.invoice_due) FROM (SELECT
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
                             `recipt_detail`.`id`
                            FROM
                                `invoice`
                            LEFT JOIN `recipt_detail` ON `recipt_detail`.`invoice_id` = `invoice`.`id`
                            LEFT JOIN `invoice_discount` ON `invoice_discount`.`invoice_id` = `invoice`.`id`
                            WHERE
                                `invoice`.`deleted_at` IS NULL
                            AND rep_id = " . $customer->marketeer_id .
            " GROUP BY
                                `invoice`.`id`
                            HAVING
                                (
                                    recipt_detail.id IS NULL
                                    OR invoice_due > 0
                                )
                            AND total > 0)as tt");


        $credit_limit = $customer->marketeer->credit_limit - $marketeer_outstanding[0]->outstanding;

        $auth = AdminAuth::firstOrCreate([
            'customer_id' => $request->customer,
            'amount' => $request->amount,
            'job_no' => $request->job_no
        ]);

        if ($auth) {
            $auth = AdminAuth::find($auth->id);
        }

        return array('customer' => $customer, 'outstanding' => $credit_limit, 'auth' => $auth);
    }

    public function payment_aging(Request $request)
    {

        if ($request->get('id')) {
            $orders = Invoice::with('recipt.bill.types', 'employee', 'customer')
                ->select('invoice.*')
                ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id')->where('invoice.id', $request->get('id'))->get();
            if ($orders) {
                $customer = $orders[0]->customer->f_name . ' ' . $orders[0]->customer->l_name;
                $marketeer = $orders[0]->employee->first_name . ' ' . $orders[0]->employee->last_name;
                $from = 'All';
                $to = 'All';
                $payment_type = 'All';
                $cus_all = $orders[0]->customer->mobile . ' / ' . $orders[0]->customer->telephone;
                $no = $orders[0]->manual_id;
            } else {
                return response()->view("errors.404");
            }
        } else {
            $orders = Invoice::with('employee', 'recipt.bill.types', 'customer')
                ->select('invoice.*')
                ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id');

            if (strlen($request->get('invoice_no')) > 0) {
                $orders = $orders->whereRaw('(manual_id = "' . $request->get('invoice_no') . '" OR job_no="' . $request->get('invoice_no') . '")');
                $no = $request->get('invoice_no');
            } else {
                $no = 'All';
            }

            $to = date('Y-m-d');
            if (strlen($request->get('from')) > 0) {
                $from = $request->get('from');

                if (strlen($request->get('to')) > 0) {
                    $to = $request->get('to');
                }
                $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
            } else {
                $from = 'All';
            }
            /*if ($request->get('status')) {
                $orders = $orders->where('purchase_order_type', $request->get('status'));
            }*/
            if ($request->get('marketeer')) {
                $orders = $orders->where('rep_id', $request->get('marketeer'));
                $marketeer = Employee::find($request->get('marketeer'));
                $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
            } else {
                $marketeer = 'All';
            }

            $payment_type = 'All';
            if ($request->get('payment_type')) {
                $orders = $orders->where('payment_type', $request->get('payment_type'));
                $payment_type = $request->get('payment_type');
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

            $orders = $orders->groupBy('invoice.id');
            $orders = $orders->orderBy('f_name', 'asc');
            $orders = $orders->orderBy('created_date', 'asc');
            $orders = $orders->get();
        }

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no, 'payment_type' => $payment_type];

        $view = view('invoiceManage::print.invoice-payment-aging')->with(['orders' => $orders, 'page_header' => $header]);
        $pdf = new PdfTemplate();
        $pdf->SetMargins(28.35 / $pdf->k, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($view);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function paymentAgingExcel(Request $request)
    {

        if ($request->get('id')) {
            $orders = Invoice::with('recipt.bill.types', 'employee', 'customer')
                ->select('invoice.*')
                ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id')->where('invoice.id', $request->get('id'))->get();
            if ($orders) {
                $customer = $orders[0]->customer->f_name . ' ' . $orders[0]->customer->l_name;
                $marketeer = $orders[0]->employee->first_name . ' ' . $orders[0]->employee->last_name;
                $from = 'All';
                $to = 'All';
                $cus_all = $orders[0]->customer->mobile . ' / ' . $orders[0]->customer->telephone;
                $no = $orders[0]->manual_id;
            } else {
                return response()->view("errors.404");
            }
        } else {
            $orders = Invoice::with('employee', 'recipt.bill.types', 'customer')
                ->select('invoice.*')
                ->join('remon_customer', 'remon_customer.id', '=', 'invoice.location_id');

            if (strlen($request->get('invoice_no')) > 0) {
                $orders = $orders->whereRaw('(manual_id = "' . $request->get('invoice_no') . '" OR job_no="' . $request->get('invoice_no') . '")');
                $no = $request->get('invoice_no');
            } else {
                $no = 'All';
            }

            $to = date('Y-m-d');
            if (strlen($request->get('from')) > 0) {
                $from = $request->get('from');

                if (strlen($request->get('to')) > 0) {
                    $to = $request->get('to');
                }
                $orders = $orders->whereRaw("DATE(invoice.created_date) BETWEEN '" . $from . "' AND '" . $to . "'");
            } else {
                $from = 'All';
            }
            /*if ($request->get('status')) {
                $orders = $orders->where('purchase_order_type', $request->get('status'));
            }*/
            if ($request->get('marketeer')) {
                $orders = $orders->where('rep_id', $request->get('marketeer'));
                $marketeer = Employee::find($request->get('marketeer'));
                $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
            } else {
                $marketeer = 'All';
            }

            $payment_type = 'All';
            if ($request->get('payment_type')) {
                $orders = $orders->where('payment_type', $request->get('payment_type'));
                $payment_type = $request->get('payment_type');
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

            $orders = $orders->groupBy('invoice.id');
            $orders = $orders->orderBy('f_name', 'asc');
            $orders = $orders->orderBy('created_date', 'asc');
            $orders = $orders->get();
        }

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];

        if (sizeof($orders) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/invoice_payment_aging.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('E' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('E' . 3, $header['to']);

                foreach ($orders as $detail) {
                    $inv_total = 0;
                    foreach ($detail->details as $order)
                        $inv_total += (($order->unit_price * $order->qty) - $order->discount);
                    $total = 0;
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->manual_id);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->customer->f_name . ' ' . $detail->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, number_format($inv_total - (is_object($detail->discounts) ? $detail->discounts->discount : 0), 2));
                    foreach ($detail->recipt as $recipt) {
                        $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $recipt->bill->recipt_no);
                        $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $recipt->bill->types->name);
                        $excel->getActiveSheet()->setCellValue('G' . $tbl_column, $recipt->bill->recipt_date);
                        $excel->getActiveSheet()->setCellValue('H' . $tbl_column, number_format($recipt->payment_amount, 2));

                        $total += $recipt->payment_amount;
                        $tbl_column++;
                    }
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, 'Invoice Total');
                    $excel->getActiveSheet()->setCellValue('H' . $tbl_column++, number_format($inv_total - (is_object($detail->discounts) ? $detail->discounts->discount : 0), 2));
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, 'Total Paid');
                    $excel->getActiveSheet()->setCellValue('H' . $tbl_column++, number_format($total, 2));
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, 'Due');
                    $excel->getActiveSheet()->setCellValue('H' . $tbl_column++, number_format((($inv_total - (is_object($detail->discounts) ? $detail->discounts->discount : 0)) - $total), 2));

                    $index++;
                }

            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        } else {
            return redirect('invoice/aging/excel')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }

        return redirect()->back();
    }

    public function pending_approve(Request $request)
    {
        $marketeerList = Employee::where('employee_type_id', 2)->get();
        $customerList = Customer::all();
        $orders = AdminAuth::with('customer');

        if (strlen($request->get('invoice_no')) > 0) {
            $orders = $orders->whereRaw('job_no="' . $request->get('invoice_no') . '"');
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(created_at) BETWEEN '" . $from . "' AND '" . $to . "'");
        }

        if ($request->get('customer')) {
            $orders = $orders->where('customer_id', $request->get('customer'));
        }

        if ($request->get('status')) {
            $orders = $orders->where('status', $request->get('status'));
        } else {
            $orders = $orders->where('status', 0);
        }

        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->paginate(20);

        return view('invoiceManage::approve-list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'invoice_no' => $request->get('invoice_no'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'customerList' => $customerList, 'customer' => $request->get('customer'), 'status' => $request->get('status')]);
    }

    public function reject(Request $request)
    {
        $auth = AdminAuth::find($request->id);
        if ($auth) {
            $auth->status = -1;
            $auth->save();
        }
        return redirect()->back();
    }

    public function approve(Request $request)
    {
        $auth = AdminAuth::find($request->id);
        if ($auth) {
            $auth->status = 1;
            $auth->save();
        }
        return redirect()->back();
    }

    public function getCreditData($id)
    {
        //return $user = Sentinel::getUser();
        $inv = Invoice::with('details', 'discounts')->find($id);
        $credit = CreditNote::where('invoice_id', $id)->orderBy('id', 'DESC')->limit(1)->get();
        $tot = 0;
        if ($inv) {
            foreach ($inv->details as $detail) {
                $tot += ((floatval($detail->unit_price) * floatval($detail->qty)) - floatval($detail->discount));
            }
            $dis = 0;
            if ($inv->discounts) {
                $dis = floatval($inv->discounts->discount);
            }
            $tot = $tot - $dis;
        }
        $inv->total = $tot;
        return response()->json(array('invoice' => $inv, 'credit_note' => $credit));
    }

    public function addCreditNote(Request $request)
    {

        DB::beginTransaction();
        $user = Sentinel::getUser();
        $credit_due = sizeof($request->credit_note) > 0 ? ($request->credit_note['credit_due'] - $request->credit_amount) : ($request->invoice['total'] - $request->credit_amount);
        //return $request->invoice['manual_id'];
        $credit_note = CreditNote::create([
            'credit_amount' => $request->credit_amount,
            'invoice_no' => $request->invoice['manual_id'],
            'invoice_id' => $request->invoice['id'],
            'credit_due' => $credit_due,
            'invoice_amount' => $request->invoice['total'],
            'create_by' => $user->employee_id,
            'comment' => $request->comment,
        ]);

        $sql = 'SELECT
                         ifnull( sum( tmp.remain ), 0 ) AS remain
                    FROM
                        (
                    SELECT
                        invoice.id,
                        total,
                        (
                        IFNULL(
                        (
                    SELECT
                        sum( ( ind.qty * ind.unit_price ) - ifnull( ind.discount, 0 ) ) 
                    FROM
                        invoice_detail AS ind 
                    WHERE
                        ind.invoice_id = invoice.id 
                    GROUP BY
                        invoice.id 
                        ),
                        0 
                        ) - ( SELECT IFNULL( sum( discount ), 0 ) FROM invoice_discount WHERE invoice_id = invoice.id ) 
                        ) - IFNULL( ( SELECT sum( payment_amount ) FROM recipt_detail WHERE invoice_id = invoice.id ), 0 )
                    - IFNULL( ( SELECT sum( credit_amount ) FROM credit_note WHERE invoice_id = invoice.id ), 0 )	AS remain 
                    FROM
                        invoice 
                    WHERE
                        id = ' . $request->invoice['id'] . ' 
                        AND invoice.deleted_at IS NULL 
                        ) AS tmp ';

        $outlet_invoice_details = DB::select($sql);
        $status = true;
        if ($outlet_invoice_details[0]->remain < 0) {
            $overpayment = OverpaidHandler::add($request->invoice['location_id'], abs($outlet_invoice_details[0]->remain), 3, $credit_note);
            if (!$overpayment) {
                $status = false;
            } else {
                $status = true;
            }
        }
        /*else {
            $ov_amt = $outlet_invoice_details[0]->remain;
            if ($ov_amt < 0) {
                $overpayment = OverpaidHandler::add($request->invoice['location_id'], $ov_amt, 3, $credit_note);

                if (!$overpayment) {
                    $status = false;
                } else {
                    $status = true;
                }
            } else {
                $status = true;
            }
        }*/

        $inv = Invoice::find($request->invoice['id']);
        if ($inv) {
            $aging = PaymentAgingReport::where('manual_id', $inv->manual_id)->first();
            if ($aging) {
                $aging->invoice_due = $aging->invoice_due - $request->credit_amount;
                $aging->save();
            }
        }
        if ($status) {
            DB::commit();
            return Response::json(["status" => 'success', "title" => 'Success', 'result' => 'credit note added success']);
        } else {
            DB::rollBack();
            return Response::json(["status" => 'warning', "title" => 'Failed', 'result' => 'credit note added fail']);
        }
    }


}