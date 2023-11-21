<?php
namespace Application\CommissionManage\Http\Controllers;


use Application\CommissionManage\Models\MarketeerCommission;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Application\EmployeeManage\Models\Employee;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Excel;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Menu Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function addView()
	{

        $marketeersList = Employee::where('employee_type_id',2)->get()->pluck('full_name', 'id');
		return view( 'commissionViews::commission.add' )->with(['marketeers' => $marketeersList]);
	}


	/**
	 * Add new product data to database
	 *
	 * @return Redirect to product add
	 */
	public function add(Request $request)
	{

        $this->validate($request, [
            'marketeer'         => 'required',
            'month_picker_txt'  => 'required',
            'value'             => 'required'
        ]);

        $date = $request->get('dateRange');
        $from = explode(' - ', $date)[0];
        $to = explode(' - ', $date)[1];
        $marketeer = $request->get('marketeer');

        $isAdded = DB::select(
            "SELECT
                *
            FROM
                marketeer_commission
            WHERE
                `deleted_at` IS NULL
                    AND `employee_id` = '$marketeer'
                    AND ('$from' BETWEEN `from` AND `to`
                    OR '$to' BETWEEN `from` AND `to`)"
        );
        if (count($isAdded) > 0) {
            return redirect('commission/add')->with(['warning' => true,
                'warning.message' => 'Commission already added...!',
                'warning.title' => 'Warning..!'])->withInput();
        }

        MarketeerCommission::create(
            [
                'employee_id' => $request->get('marketeer'),
                'value' => $request->get('value'),
                'from' => explode(' - ', $date)[0],
                'to' => explode(' - ', $date)[1]
            ]
        );
        return redirect('commission/add')->with(['success' => true,
            'success.message' => 'Commission added successfully!',
            'success.title' => 'Success..!']);

	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
    public function listView(Request $request)
    {
        $per = [];
        $isAdd = false;
        $per['viewable'] = false;
        $permissions = Permission::whereIn('name', ['commission.target.edit', 'admin'])->where('status', '=', 1)->pluck('name');
        if (Sentinel::hasAnyAccess($permissions)) {
            $per['editable'] = true;
        } else {
            $per['editable'] = false;
        }
        $permissions = Permission::whereIn('name', ['marketeerTarget.target.edit', 'admin'])->where('status', '=', 1)->pluck('name');
        if (Sentinel::hasAnyAccess($permissions)) {
            $per['deletable'] = true;
        } else {
            $per['deletable'] = false;
        }
        $aa = Permission::whereIn('name', ['targetManage.target.add', 'admin'])->where('status', '=', 1)->pluck('name');
        if (Sentinel::hasAnyAccess($aa)) {
            $isadd  = true;
        } else {
            $isadd = false;
        }



        return view('commissionViews::commission.list')->with(['permissons' => json_encode($per),'isadd'=>$isadd]);

    }

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
        $cur = $request->get('currentPage');
        $size = $request->get('pageSize');
        $skip = ($cur * $size) - ($size);
        $user =  Sentinel::getUser();
        if($user->employee_id == 1 || trim($user->roles[0]->name) == 'admin'){
            $commissions = MarketeerCommission::take($size)->skip($skip)->get();
        }elseif(trim($user->roles[0]->name) == 'marketer'){
            $commissions = MarketeerCommission::take($size)->skip($skip)->where('employee_id',$user->employee_id)->get();
        }
        $count = MarketeerCommission::count();
        $commissionList = array();

        foreach ($commissions as $commission) {
            array_push($commissionList,
                array(
                    'id' => $commission->id,
                    'first_name' => $commission->employee->first_name . ' ' . $commission->employee->last_name,
                    'value' => 'Rs. '.$commission->value,
                    'from' => $commission->from,
                    'to' => $commission->to
                )
            );
        }
        return response()->json(array('data' => $commissionList, 'count' => $count));

	}



    public function exceltolist(Request $request){

        $data = Product::with('brand','range','category')->where('status',1)->get();
        $ss = array();
        foreach ($data as $key => $product) {
            if($product->status = 1){
                $status ='Active';
            }else{
                $status = 'Inactive';
            }
            $dd = array();
            array_push($dd, $product->product_name);
            array_push($dd, $product->description);
            array_push($dd, $product->brand->brand);
            array_push($dd, $product->category->category_name);
            array_push($dd, $product->range->range_name);
            array_push($dd, $product->pack_size);
            array_push($dd, $product->short_code);
            array_push($dd, $product->tax_code);
            array_push($dd, $status);

            array_push($ss,$dd);
        }

        $aa=array();
        $aa[]="Name";
        $aa[]="Description";
        $aa[]="Brand";
        $aa[]="Category";
        $aa[]="Range";
        $aa[]="Pack Size";
        $aa[]="Short Code";
        $aa[]="Tax Code";
        $aa[]="Status";

       array_unshift($ss,$aa);

        Excel::create('Product List', function($excel) use($ss){

            $excel->sheet('datasheet', function($sheet) use($ss){
                $sheet->fromArray($ss,null,'A1',true,false);
                $sheet->setAutoSize(true);
            });
            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        })->download('xls');
    }

	/**
	 * Activate or Deactivate Menu
	 * @param  Request $request menu id with status to change
	 * @return Json           	json object with status of success or failure
	 */
	public function status(Request $request)
	{
		if($request->ajax()){
			$id = $request->input('id');
			$status = $request->input('status');

			$product = Product::find($id);
			if($product){
				$product->status = $status;
				$product->save();
				return response()->json(['status' => 'success']);
			}else{
				return response()->json(['status' => 'invalid_id']);
			}
		}else{
			return response()->json(['status' => 'not_ajax']);
		}
	}
	/**
	 * Show the product edit screen to the user.
	 *
	 * @return Response
	 */
	public function editView($id)
	{
        $commission = MarketeerCommission::with("employee")->find($id);

		if($commission){
            return view('commissionViews::commission.edit')->with(['commission' => $commission]);
		}else{
			//return view('errors.404');
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(Request $request, $id)
	{
        $date = $request->get('dateRange');
        $newdate=date_create($date);
        $startdate= date_format($newdate,"Y-m-01");
        $finishdate= date_format($newdate,"Y-m-31");
        $target = MarketeerCommission::find($id);
        $target->employee_id = $request->get('empId');
        $target->value = $request->get('value');
        $target->from = $startdate;
        $target->to = $finishdate;
        $target->save();
        return redirect()->back()->with(['success' => true,
            'success.message' => 'Target updated successfully!',
            'success.title' => 'Success...!']);
	}

	public function listcode(Request $request)
	{
		if($request->ajax()){
		$product = DB::select("SELECT
										   short_code

										FROM
										    wp_Product
										       where short_code= '$request->short'
										GROUP BY id
										 "
		);
			return Response::json(array('data'=>$product));
		}else{
			return Response::json(array('data'=>[]));
		}
	}
	public function listproductcat(Request $request)
	{
		if($request->ajax()){
			$cat = DB::select("SELECT
										   category_name

										FROM
										    4ever_product_category
										       where category_name= '$request->cat'
										GROUP BY id
										 "
			);
			return Response::json(array('data'=>$cat));
		}else{
			return Response::json(array('data'=>[]));
		}
	}
}
