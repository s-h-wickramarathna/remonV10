<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\SalesOrderManage\Http\Controllers;


use App\Classes\Common;
use App\Classes\DiscountHandler;
use App\Classes\FreeIssueHandler;
use App\Classes\StockHandler;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\WarehouseType;
use Application\OutletListManagement\Models\Outlet;
use Application\PaymentManage\Models\Recipt;
use Application\SalesOrderManage\Models\SalesOrder;
use Application\SalesOrderManage\Models\SalesOrderDetail;
use Application\SalesOrderManage\Models\SalesOrderDiscount;
use Illuminate\Http\Request;
use Application\BatchPrice\Models\BatchPrice;
use Application\EmployeeManage\Http\Controllers\EmployeeController;
use Application\EmployeeManage\Models\Employee;
use Application\InvoiceManage\Models\Invoice;
use Application\InvoiceManage\Models\InvoiceDetail;
use Application\LocationManage\Models\Location;
use Application\LocationManage\Models\LocationType;
use Application\Product\Models\Product;
use Application\Product\Models\Product_Brand;
use Application\Product\Models\Product_Range;
use Application\ProductCategory\Models\ProductCategory;
use Application\PurchaseOrderManage\Models\PurchaseOrder;
use Application\PurchaseOrderManage\Models\PurchaseOrderDetail;
use Application\VanLoading\Models\VanLoading;
use Application\VanLoading\Models\VanLoadingDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;


class SalesOrderController extends Controller
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
    public function addView()
    {
        $brandList = Product_Brand::select('brand', 'id')->where('status', 1)->get();
        $categoryList = ProductCategory::select('category_name', 'id')->where('status', 1)->get();
        $rangeList = Product_Range::select('range_name', 'id')->where('status', 1)->get();
        $productList = Product::select('CONCAT(short_code," - ",product_name) as product_name', 'id')->with('price_book', 'stock')->where('status', 1)->get();
        return view('invoiceManage::add')->with(['brandList' => $brandList, 'categoryList' => $categoryList, 'rangeList' => $rangeList, 'productList' => $productList]);
    }


    public function insertOrder($order, $details, $discounts, $resArray)
    {

        $location = Location::find($order->outletId);
        $is_exist = SalesOrder::where('rep_id',$order->userId)->where('location_id',$order->outletId)->where('manual_id',$order->manualId)->where('created_date',$order->salesOrderDateTime)->first();
        if(sizeof($is_exist)>0){
            $orderObj = $is_exist;
        }else{
            $orderObj = SalesOrder::create([
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
                'total' => $order->totalAmount
            ]);
        }

        $orderResArray = $resArray['invoices'];
        $orderDetailsResArray = $resArray['salesOrderDetails'];
        $orderDiscountResArray = $resArray['salesOrderDiscount'];

        array_push($orderResArray, array('clientKey' => $order->salesOrder_Id, 'serverKey' => $orderObj->id));

        $orderDetailsResArray = $this->insertInvoiceDetail($orderObj->id, $details, $order->userId, $orderDetailsResArray);
        $orderDiscountResArray = $this->insertOrderDiscount($orderObj->id, $discounts, $orderDiscountResArray);

        static::updateLocationOfOutlet($order->outletId,$order->gps_latitude,$order->gps_longitude);

        $resArray['invoices'] = $orderResArray;
        $resArray['salesOrderDetails'] = $orderDetailsResArray;
        $resArray['salesOrderDiscount'] = $orderDiscountResArray;
        return $resArray;
    }

    public function insertInvoiceDetail($orderId, $details, $repId, $orderDetailsResArray)
    {
        $detail_exist =  SalesOrderDetail::where('sales_order_id',$orderId)->get();
        if(sizeof($detail_exist) > 0){
           foreach($detail_exist as $detail_exist){
               foreach ($details as $detail) {
                   if($detail->productId == $detail_exist->productId && $detail->batchId == $detail_exist->batchId){
                       array_push($orderDetailsResArray, array('clientKey' => $detail->salesOrderDetails_Id, 'serverKey' => $detail_exist->id));
                       break;
                   }
               }
           }
        }else {
            $tot_Amount = 0;
            foreach ($details as $detail) {
                $detailObj = SalesOrderDetail::create([
                    'product_id' => $detail->productId,
                    'batch_id' => $detail->batchId,
                    'unit_price' => $detail->unitPrice,
                    'qty' => $detail->quantity,
                    'free_qty' => $detail->freeQuantity,
                    'group_id' => $detail->groupId,
                    'sales_order_id' => $orderId,
                    'order_detail_type' => $detail->orderDetailType,
                    'price_book_detail_id' => $detail->priceBookDetailId
                ]);
                $tot_Amount += ($detail->unitPrice * $detail->quantity);
                array_push($orderDetailsResArray, array('clientKey' => $detail->salesOrderDetails_Id, 'serverKey' => $detailObj->id));
            }
            $order = SalesOrder::find($orderId);
            $order->total = $tot_Amount;
            $order->save();
        }
        return $orderDetailsResArray;
    }

    public function insertOrderDiscount($orderId, $discounts, $orderDiscountResArray)
    {
        $discounts_exist = SalesOrderDiscount::where('sales_order_id',$orderId)->get();
        if(sizeof($discounts_exist)>0){
            foreach($discounts_exist as $discount_exist){
                foreach ($discounts as $discount) {
                    if($discount->packageId == $discount_exist->packageId && $discount->discountRuleId == $discount_exist->discountRuleId && $discount->discountType == $discount_exist->discountType && $discount->discountAmount == $discount_exist->discountAmount){
                        array_push($orderDiscountResArray, array('clientKey' => $discount->salesOrderDiscountId, 'serverKey' => $discount_exist->id));
                        break;
                    }

                }
            }
        }else {
            foreach ($discounts as $discount) {
                $discountObj = SalesOrderDiscount::create([
                    'product_id' => 0,
                    'batch_id' => 0,
                    'discount_group_id' => $discount->packageId,
                    'discount' => $discount->discountAmount,
                    'rule_id' => $discount->discountRuleId,
                    'discount_type' => $discount->discountType,
                    'sales_order_id' => $orderId
                ]);
                array_push($orderDiscountResArray, array('clientKey' => $discount->salesOrderDiscountId, 'serverKey' => $discountObj->id));
            }
        }
        return $orderDiscountResArray;
    }


    public function updateVanStock($detailObj, $repId)
    {
        $vanLoad = VanLoadingDetails::join('wp_van_loading as vl', 'vl.id', '=', 'loading_id')
            ->select('wp_van_loading_detail.id', 'qty')
            ->whereRaw('vl.rep_id =' . $repId)
            ->where('product_id', $detailObj->product_id)
            ->where('batch_id', $detailObj->batch_id)
            ->orderBy('vl.id', 'ASE')
            ->first();
        $toUpdateVanLoading = VanLoadingDetails::find($vanLoad->id);
        $toUpdateVanLoading->qty = ($vanLoad->qty) - ($detailObj->qty);
        $toUpdateVanLoading->save();
    }

    public function getProductByBrand(Request $request)
    {
        return Product::with('range', 'category', 'price_book', 'stock')->where('brand_id', $request->get('brand'))->get();
    }

    public function getProductByCategory(Request $request)
    {
        $user = Sentinel::getUser();
        return Product::with(['range', 'price_book', 'stock'=>function($query) use($user){
            $query->with(['warehouse'=>function($query) use($user){
                $query->where('distributor_id',$user->employee_id);
            }]);
        }])->where('product_category_id', $request->category)->get();
    }

    /**
     * order list view
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listView()
    {
        return view('salesOrderManage::order.list');
    }

    /**
     * get order list
     */
    public function getOrderList()
    {
        $greenList = array();
        $redList = array();
        $temp = array();
        $distributor = Employee::where('id', Sentinel::getUser()->employee_id)
            ->where('employee_type_id', 4)->first();
        if(is_object($distributor)) {
            $repList = Employee::find($distributor->id)->descendants()->get()->pluck('id');//4 ids example distributor id
            $orderList = SalesOrder::
                select('4ever_sales_order.*','(sum(sd.discount)) as discount')
                ->leftJoin('4ever_sales_order_discount as sd','sd.sales_order_id','=','4ever_sales_order.id')
                ->with('details')
                ->where('sales_order_approve_status', 1)
                ->where('4ever_sales_order.status',1)
                ->whereIn('rep_id', $repList)
                ->groupBy('4ever_sales_order.id')
                ->get();

            //get distributor stock qty product vice
            $stocks = StockHandler::getDistributorStockAt($distributor->id);


            //return $stocks;

            //iterate order list with detail
            foreach ($orderList as $order) {
                $details = $this->getOrderDetailAt($order->id);
                //iterate order detail

                $orderStatus = 0;
                foreach ($details as $detail) {
                    $qty = 0;
                    //sum all qty in same product of each stock
                    foreach ($stocks as $stock) {
                        if ($detail->product_id == $stock->product_id) {
                            $qty = $stock->qty;
                            break;
                        }
                    }
                    //check stock qty and order qty
                    if ($qty >= $detail->qty) {
                        $orderStatus = 1;
                    } else {
                        $orderStatus = 0;
                        break;
                    }
                }
                if ($orderStatus > 0) {
                    $stock = $this->deduct($order, $stock);
                    array_push($greenList, $order);
                } else {
                    array_push($redList, $order);
                }
            }
        }

        return response()->json(['greenList' => $greenList, 'redList' => $redList]);
    }

    /**
     * get order data
     */
    public function getOrderData($id)
    {
        $orderDetailList = SalesOrder::with(['details' => function ($query) {
            $query->with('product');
        }, 'location' => function ($query) {
            $query->with('outlet');
        }, 'discount' => function ($query) {
            $query->select('sum(discount) as discount,sales_order_id')->groupBy('sales_order_id');
        }, 'employee' => function ($query) {
            $query->select('concat(first_name," ",last_name) as name,id');
        }])->where('id', $id)->get();

        return response()->json($orderDetailList);
    }

    public function getOrderDetailAt($orderID)
    {
        return SalesOrderDetail::select(
            'product_id',
            'sum((qty+free_qty)) as qty')
            ->where('sales_order_id', $orderID)
            ->groupBy('product_id')
            ->get();
    }


    public function deduct($order, $stock)
    {
        $detail = $this->getOrderDetailAt($order);
        foreach ($detail as $orderItem) {
            foreach ($stock as $stockItem) {
                $stockItem->qty -= $orderItem->qty;
            }
        }
        return $stock;
    }

    public function editView($id)
    {
        $brandList = Product_Brand::select('brand', 'id')->where('status', 1)->get();
        $categoryList = ProductCategory::select('category_name', 'id')->where('status', 1)->get();
        $rangeList = Product_Range::select('range_name', 'id')->where('status', 1)->get();
        $productList = Product::select('CONCAT(short_code," - ",product_name) as product_name', 'id')->with('price_book', 'stock')->where('status', 1)->get();
        $salesOrder = SalesOrder::find($id);
        $outlet =  Location::with('locOutlet')->where('id',$salesOrder->location_id)->get();
        return view('salesOrderManage::order.edit')->with(['id' => $id, 'brandList' => $brandList, 'categoryList' => $categoryList, 'rangeList' => $rangeList, 'productList' => $productList, 'outlet' => $outlet]);
    }

    public function getOrderDetail(Request $request)
    {
         $order = SalesOrder::where('id',$request->get('id'))->first();

        $invoices =  Invoice::select('4ever_invoice.id', '(total - ifnull((sum(sd.discount)),0)) as total')
            ->leftJoin('4ever_invoice_discount as sd','sd.invoice_id','=','4ever_invoice.id')
            ->where('location_id',$order->location_id)
            ->groupBy('4ever_invoice.id')
            ->get();
        $totAmount =0;
        $invoiceId = array();
        foreach($invoices as $invoice){
            $totAmount += $invoice->total;
            $invoiceId[]=$invoice->id;
        }

        $payment =  Recipt::select('sum(amount) as payment')->where('location_id',$order->location_id)->groupBy('location_id')->get();
        if(sizeof($payment)>0){
            $payment = $payment[0]->payment;
        }else{
            $payment = 0;
        }
        $credit_limit =  Outlet::where('4ever_location_id',$order->location_id)->pluck('credit_limit')->first();
        $outstanding =  $totAmount - $payment;
        $rep =  Employee::find($order->rep_id);
        try {
            $warehouseID = Warehouse::where('distributor_id', $rep->parent)->first();

            $order = SalesOrder::with(['details' => function ($query) use ($warehouseID) {
                $query->with(['product', 'stock' => function ($query) use ($warehouseID) {
                    $query->where('warehouse_id', $warehouseID->id)->groupBy('4ever_stock.product_id', '4ever_stock.id');
                }])->where('qty','>',0);
            }, 'discount','location'=>function($query){$query->with('outlet');},'employee','brand'])->where('id', $request->get('id'))->first();
        }catch(Exception $e){
            throw new Exception('no stock');
        }
        $creditCheck = array('outstanding'=>$outstanding,'credit_limit'=>$credit_limit);

        return response()->json(array($order,$creditCheck));
    }

    public function getFreeIssue(Request $request)
    {
        return response()->json(FreeIssueHandler::generateFreeIssue($request->get('details'), $request->get('outletId')));
    }

    public function getDiscount(Request $request)
    {
        return response()->json(DiscountHandler::generateDiscount($request->get('details'), $request->get('outletId')));
    }

    /**
     * get order data
     */
    public function discardOrder($id)
    {
        $order = SalesOrder::find($id);
        $order->status = 0;
        $order->save();
        return response()->json($order);
    }


    public function testReturnTo()
    {

       // return Common::getOutletsAuthWise(Sentinel::getUser());
        /**
         * 1st param > item array
         * 2nd param > discount group id
         */
        /*return DiscountHandler::generateDiscountForReturn(
            Array (
                [0] => Array ( [batch_id] => [price_book_detail_id] => [product_id] => 1 [qty] => 100 [unit_price] => 900 [product_name] => )
                [1] => Array ( [batch_id] => [price_book_detail_id] => [product_id] => 3 [qty] => 1000 [unit_price] => 900 [product_name] => )
                [2] => Array ( [batch_id] => [price_book_detail_id] => [product_id] => 5 [qty] => 1000 [unit_price] => 900 [product_name] => ) )
        ,Array ( [0] => 2 [1] => 3 ));*/
        return DiscountHandler::generateDiscount(array (
            0 =>
                array (
                    'batch_id' => '1',
                    'free_qty' => '0',
                    'group_id' => '0',
                    'price_book_detail_id' => '4',
                    'product_id' => '1',
                    'qty' => '100',
                    'unit_price' => '900',
                    'product_name' => 'F001 - Kohomba Kaha Whitening Face Wash Bottle 210ml',
                ),
            1 =>
                array (
                    'batch_id' => '1',
                    'free_qty' => '0',
                    'group_id' => '0',
                    'price_book_detail_id' => '4',
                    'product_id' => '4',
                    'qty' => '50',
                    'unit_price' => '900',
                    'product_name' => 'F002 - Rose Acne Face Wash Bottle 100ml',
                ),
            2 =>
                array (
                    'batch_id' => '1',
                    'free_qty' => '0',
                    'group_id' => '0',
                    'price_book_detail_id' => '4',
                    'product_id' => '5',
                    'qty' => '50',
                    'unit_price' => '900',
                    'product_name' => 'F005 - Rose Acne Face Wash Bottle 100ml',
                )
        ),34);


    }

    static function updateLocationOfOutlet($outletID,$lat,$lon){
        if($lat != 0 && $lon != 0) {
            $outlet = Outlet::where('4ever_location_id', $outletID)->first();
            $outlet->gps_latitude = $lat;
            $outlet->gps_longitude = $lon;
            $outlet->save();
        }
    }

    /*
     * array (
            0 =>
                array (
                    'batch_id' => '1',
                    'free_qty' => '0',
                    'group_id' => '0',
                    'price_book_detail_id' => '4',
                    'product_id' => '1',
                    'qty' => '100',
                    'unit_price' => '85',
                    'product_name' => 'F001 - Kohomba Kaha Whitening Face Wash Bottle 210ml',
                ),
            1 =>
                array (
                    'batch_id' => '1',
                    'free_qty' => '0',
                    'group_id' => '0',
                    'price_book_detail_id' => '4',
                    'product_id' => '2',
                    'qty' => '100',
                    'unit_price' => '85',
                    'product_name' => 'F002 - Rose Acne Face Wash Bottle 100ml',
                ),
            2 =>
                array (
                    'batch_id' => '1',
                    'free_qty' => '0',
                    'group_id' => '0',
                    'price_book_detail_id' => '4',
                    'product_id' => '3',
                    'qty' => '100',
                    'unit_price' => '85',
                    'product_name' => 'F003 - Kohomba Kaha Deep Cleansing Milk Bottle 100ml',
                ),
        )
     */

}