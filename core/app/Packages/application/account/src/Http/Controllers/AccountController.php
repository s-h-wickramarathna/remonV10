<?php

namespace Application\AccountManage\Http\Controllers;


use Application\AccountManage\Models\Account;
use Application\CommissionManage\Models\MarketeerCommission;
use App\Http\Controllers\Controller;
use Application\PaymentManage\Models\Banks;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Application\EmployeeManage\Models\Employee;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Excel;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

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

        $bankList = Banks::get()->pluck('name', 'id');
        return view('accountViews::commission.add')->with(['banks' => $bankList]);
    }


    /**
     * Add new product data to database
     *
     * @return Redirect to product add
     */
    public function add(Request $request)
    {

        $this->validate($request, [
            'bank' => 'required',
            'acc_no' => 'required'
        ]);

        $branch = $request->get('branch');
        $bank = $request->get('bank');
        $acc_no = $request->get('acc_no');

        $isAdded = DB::select(
            "SELECT
                *
            FROM
                bank_account
            WHERE
                `deleted_at` IS NULL
                    AND `bank` = '$bank'
                    AND `branch` = '$branch'
                    AND `account_no` = '$acc_no'"
        );
        if (count($isAdded) > 0) {
            return redirect('account/add')->with(['warning' => true,
                'warning.message' => 'Account already added...!',
                'warning.title' => 'Warning..!'])->withInput();
        }

        Account::create(
            [
                'bank' => $bank,
                'branch' => $branch,
                'account_no' => $acc_no
            ]
        );
        return redirect('account/add')->with(['success' => true,
            'success.message' => 'Account added successfully!',
            'success.title' => 'Success..!']);

    }

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function listView(Request $request)
    {
        $user = Sentinel::getUser();
        $permissions = Permission::whereIn('name', ['account.list', 'admin'])->where('status', '=', 1)->pluck('name');
        return view('accountViews::commission.list')->with(['permissions' => $user->hasAnyAccess($permissions)]);

    }

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function jsonList(Request $request)
    {
        if (true) {
            $user = Sentinel::getUser();
            $data = DB::select(" SELECT
									      bank_account.id,
								          banks.name,
								          branch,
								          account_no,
								          status
								FROM
								    bank_account INNER JOIN banks ON bank = banks.id

								ORDER BY bank_account.`id`
								");
            $jsonList = array();
            $i = 1;

            foreach ($data as $key => $size) {
                $dd = array();
                array_push($dd, $size->id);
                array_push($dd, $size->account_no);
                array_push($dd, $size->name);
                array_push($dd, $size->branch);

                $status_permission = Permission::whereIn('name', ['size.status', 'admin'])->where('status', '=', 1)->pluck('name');
                if($user->hasAnyAccess($status_permission)){
                    if($size->status == 1){
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }else{
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }

                }else{
                    if($size->status == 1){
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }else{
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }
                }

                $permissions = Permission::whereIn('name', ['account.edit', 'admin'])->where('status', '=', 1)->pluck('name');
                if ($user->hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\'' . url('account/edit/' . $size->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit account" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                } else {
                    array_push($dd, '<a href="#" class="blue" onclick="false" data-toggle="tooltip" data-placement="top" title="Edit Account Disabled - Persmisson Deined" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;

            }
            //return  $jsonList;
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }

    }


    public function exceltolist(Request $request)
    {

        $data = Product::with('brand', 'range', 'category')->where('status', 1)->get();
        $ss = array();
        foreach ($data as $key => $product) {
            if ($product->status = 1) {
                $status = 'Active';
            } else {
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

            array_push($ss, $dd);
        }

        $aa = array();
        $aa[] = "Name";
        $aa[] = "Description";
        $aa[] = "Brand";
        $aa[] = "Category";
        $aa[] = "Range";
        $aa[] = "Pack Size";
        $aa[] = "Short Code";
        $aa[] = "Tax Code";
        $aa[] = "Status";

        array_unshift($ss, $aa);

        Excel::create('Product List', function ($excel) use ($ss) {

            $excel->sheet('datasheet', function ($sheet) use ($ss) {
                $sheet->fromArray($ss, null, 'A1', true, false);
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
     * @return Json            json object with status of success or failure
     */
    public function status(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $product = Account::find($id);
            if ($product) {
                $product->status = $status;
                $product->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
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
        $bankList = Banks::get()->pluck('name', 'id');
        $account = Account::with('banks')->find($id);

        if ($account) {
            return view('accountViews::commission.edit')->with(['account' => $account, 'banks' => $bankList]);
        } else {
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
        $target = Account::find($id);
        $target->bank = $request->get('bank');
        $target->branch = $request->get('branch');
        $target->account_no = $request->get('acc_no');
        $target->save();
        return redirect('account/list')->with(['success' => true,
            'success.message' => 'Account updated successfully!',
            'success.title' => 'Success...!']);
    }

    public function listcode(Request $request)
    {
        if ($request->ajax()) {
            $product = DB::select("SELECT
										   short_code

										FROM
										    wp_Product
										       where short_code= '$request->short'
										GROUP BY id
										 "
            );
            return Response::json(array('data' => $product));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function listproductcat(Request $request)
    {
        if ($request->ajax()) {
            $cat = DB::select("SELECT
										   category_name

										FROM
										    4ever_product_category
										       where category_name= '$request->cat'
										GROUP BY id
										 "
            );
            return Response::json(array('data' => $cat));
        } else {
            return Response::json(array('data' => []));
        }
    }
}
