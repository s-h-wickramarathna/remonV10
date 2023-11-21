<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\CustomerManage\Http\Controllers;

use App\Classes\PdfTemplate;
use App\Classes\VehicleStockHandler;
use App\Http\Controllers\Controller;
use Application\CustomerManage\Models\Area;
use Application\CustomerManage\Models\Customer;
use Application\EmployeeManage\Http\Controllers\EmployeeController;
use Application\EmployeeManage\Models\Employee;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Core\Permissions\Models\Permission;
use Core\UserManage\Models\RoleUsers;
use Core\UserManage\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Application\CustomerManage\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{

    /**
     * Show the vehicle add screen to the user.
     *
     * @return Response
     */
    public function addView()
    {

        $user = Sentinel::getUser();
        if ($user->roles[0]->id == 1 || $user->roles[0]->id == 3) {
            $marketieerList = Employee::select('first_name', 'last_name', 'id')->where('employee_type_id', 2)->orderBy('first_name', 'asc')->get();
        } else {
            $marketieerList = Employee::select('first_name', 'last_name', 'id')->where('id', $user->employee_id)->orderBy('first_name', 'asc')->get();
        }

        $areas = Area::get();

        return view('customerManage::customer.add')->with(['marketeer' => $marketieerList, 'areas' => $areas]);
    }

    /**
     * add new vehicle data to database
     * @param $request
     * @return Redirect to vehicle add
     */
    public function add(CustomerRequest $request)
    {

        if ($request->get('marketeer') == 0) {
            return redirect('customer/add')->withInput()->withErrors(array('marketeer' => 'Marketeer is required'));
        }

        DB::beginTransaction();
        $customer = Customer::create(
            [
                'f_name' => $request->get('customer_fName'),
                'l_name' => $request->get('customer_lName'),
                'address' => $request->get('customer_address'),
                'area' => $request->get('area'),
                'nic' => $request->get('nic'),
                'mobile' => $request->get('customer_mobile'),
                'telephone' => $request->get('customer_telephone'),
                'email' => $request->get('customer_email'),
                'credit_limit' => $request->get('customer_credit_limit'),
                'credit_period' => $request->get('customer_credit_period'),
                'type' => 2,
                'marketeer_id' => $request->get('marketeer'),
            ]
        );

        if (!$customer) {
            DB::rollback();
            return redirect('customer/add')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!'])->withInput();

        } else {
            DB::commit();
            return redirect('customer/add')->with(['success' => true,
                'success.message' => 'Customer added successfully!',
                'success.title' => 'Success..!']);
        }


    }

    /**
     * Show the menu list screen to the user.
     *
     * @return Response
     */
    public function listView()
    {

        $user = Sentinel::getUser();
        $permissions = Permission::whereIn('name', ['customer.list', 'admin'])->where('status', '=', 1)->pluck('name');
        return view('customerManage::customer.list')->with(['permissions' => $user->hasAnyAccess($permissions)]);

    }

    public function getReps(Request $request)
    {
        $reps = Employee::with('type')
            ->where('employee_type_id', 5)
            ->where('parent', $request->dis_id)
            ->where(function ($query) {
                $query->whereNotIn('id', function ($q1) {
                    $q1->from('4ever_rep_vehicle')->select('rep_employee_id');
                })->orWhereIn('id', function ($q1) {
                    $q1->from('4ever_rep_vehicle')->select('rep_employee_id')
                        ->whereNotNull('ended_at');
                });
            })->get();

        return Response::json(array('data' => $reps));
    }

    /**
     * Show the vehicle list data to the user.
     *
     * @return Response
     */
    public function jsonList(Request $request)
    {
        $search = $request->get('search')['value'];
        $start = $request->get('start');
        $length = $request->get('length');
        $customer_array = array();

        if ($request->ajax()) {
            $user = Sentinel::getUser();
            $user->roles[0]->id;
            if ($user->roles[0]->id == 1 || $user->roles[0]->id == 3) {
                $data = DB::select("select
                              c.id,c.f_name,c.l_name,c.address,c.nic,
                              c.mobile,c.telephone,c.email,c.credit_limit,c.credit_period,c.is_credit_limit_block,
                              c.`status`,c.`type`,marketeer_id,CONCAT(e.first_name,' ',e.last_name) as marketeer_name
                              from remon_customer AS c left join employee as e ON c.marketeer_id = e.id WHERE  CONCAT(LOWER(TRIM(c.f_name)),' ',LOWER(TRIM(c.l_Name)))  like '%$search%'   Limit $length OFFSET $start ");
            } else {
                $data = DB::select("select
                              c.id,c.f_name,c.l_name,c.address,c.nic,
                              c.mobile,c.telephone,c.email,c.credit_limit,c.credit_period,c.is_credit_limit_block,
                              c.`status`,c.`type`,marketeer_id,CONCAT(e.first_name,' ',e.last_name) as marketeer_name
                              from remon_customer AS c left join employee as e ON c.marketeer_id = e.id WHERE c.marketeer_id = $user->employee_id AND CONCAT(LOWER(c.f_name),' ',LOWER(c.l_Name))  like '%$search%' ");
            }

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

                $status_credit = Permission::whereIn('name', ['customer.credit_status', 'admin'])->where('status', '=', 1)->pluck('name');
                if ($user->hasAnyAccess($status_credit)) {
                    if ($customers->is_credit_limit_block == 1) {
                        array_push($dd, $customers->credit_limit . '<br/> <label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="credit-activate" type="checkbox" checked value="' . $customers->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    } else {
                        array_push($dd, $customers->credit_limit . '<br/> <label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="credit-activate" type="checkbox" value="' . $customers->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }
                } else {
                    array_push($dd, $customers->credit_limit);
                }

                array_push($dd, $customers->credit_period);

                $status_permission = Permission::whereIn('name', ['customer.status', 'admin'])->where('status', '=', 1)->pluck('name');
                if ($user->hasAnyAccess($status_permission)) {
                    if ($customers->status == 1) {
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="' . $customers->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    } else {
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="' . $customers->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }
                } else {
                    if ($customers->status == 1) {
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Category Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="' . $customers->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    } else {
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Category Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="' . $customers->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }
                }

                $permissions = Permission::whereIn('name', ['customer.status', 'admin'])->where('status', '=', 1)->pluck('name');
                if ($user->hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\'' . url('customer/edit/' . $customers->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit product Category" style="text-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');

                } else {
                    array_push($dd, '<a href="#" class="blue" onclick="false" data-toggle="tooltip" data-placement="top" title="Edit Product Disabled - Persmisson Deined" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(['draw' => (int)$request->get('draw'),
                'recordsTotal' => (int)Customer::get(['id'])->count(),
                'recordsFiltered' => (int)Customer::whereRaw('(f_name like "%' . $search . '%" OR l_name like "%' . $search . '%")')->count(), "data" => $jsonList]);
        } else {
            return Response::json(array('data' => []));
        }
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

            $productCategory = Customer::find($id);
            if ($productCategory) {
                $productCategory->status = $status;
                $productCategory->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }


    }

    /**
     * Activate or Deactivate Menu
     * @param  Request $request menu id with status to change
     * @return Json            json object with status of success or failure
     */
    public function creditLimitStatus(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $customer = Customer::find($id);
            if ($customer) {
                $customer->is_credit_limit_block = $status;
                $customer->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }


    }

    /**
     * Show the vehicle to the user.
     * @param type id
     * @return Response
     */
    public function editView($id)
    {
        $areas = Area::get();
        $customer = Customer::with('user')->find($id);
        $marketeer = EmployeeController::getUserById($customer->marketeer_id);
        $marketieerList = Employee::select('first_name', 'last_name', 'id')->where('employee_type_id', 2)->orderBy('first_name', 'asc')->get();

        return view('customerManage::customer.edit')->with(['customer' => $customer, 'marketeer' => $marketieerList, 'areas' => $areas]);
    }


    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function edit(CustomerRequest $request, $id)
    {


        $customer = Customer::find($id);
        $customer->f_name = $request->get('customer_fName');
        $customer->l_name = $request->get('customer_lName');
        $customer->address = $request->get('customer_address');
        $customer->area = $request->get('area');
        $customer->nic = $request->get('nic');
        $customer->mobile = $request->get('customer_mobile');
        $customer->telephone = $request->get('customer_telephone');
        $customer->email = $request->get('customer_email');
        $customer->credit_limit = $request->get('customer_credit_limit');
        $customer->credit_period = $request->get('customer_credit_period');
        $customer->marketeer_id = $request->get('marketeer');

        if ($customer->user_id) {
            $user = Sentinel::findUserById($request->id);
            if($request->get('password')) {
                if ($request->get('password') == $request->get('cPassword')) {
                    if (Sentinel::validateCredentials($user, ['username' => $request->uName, 'password' => $request->get('oPassword')])) {
                        $credentials['password'] = $request->get('cPassword');
                    } else {
                        return redirect()->back()->with(['warning' => true,
                            'warning.message' => 'Old Password mismatch..!',
                            'warning.title' => 'Warning..!'])->withInput();
                    }
                } else {
                    return redirect()->back()->with(['warning' => true,
                        'warning.message' => 'New Password mismatch..!',
                        'warning.title' => 'Warning..!'])->withInput();
                }
                Sentinel::update($user, $credentials);
            }
        }else{
            if($request->get('password')) {
                $user = Sentinel::register([
                    'username' => $request->get('uName'),
                    'employee_id' => $request->get('employee'),
                    'password' => $request->get('password'),
                    'permissions' => ['index' => true],
                    'last_login' => date("Y-m-d H:i:s")
                ]);

                $roleusers = RoleUsers::create([
                    'user_id' => $user->id,
                    'role_id' => 12
                ]);

                $customer->user_id = $user->id;

                $acUser = Activation::create($user);
                Activation::complete($user, $acUser->code);
            }
        }
        $customer->save();

        return redirect('customer/list')->with(['success' => true,
            'success.message' => 'Customer updated successfully!',
            'success.title' => 'Success...!']);
    }


    /**
     * Delete a Vehicle
     * @param  Request $request id
     * @return Json            json object with status of success or failure
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $vehicle = Vehicle::find($id);
            if ($vehicle) {
                $vehicle->delete();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }


    /**
     * get steward list of filter the type
     * @param Request $request employee type id
     * @return steward list
     */
    public function getSteward(Request $request)
    {
        $emp = Employee::find(Sentinel::getUser()->employee_id);
        if ($emp->employee_type_id > 1) {
            $employees = Employee::with('parent')->where('id', $emp->id)->get();
        } else {
            $employees = Employee::with('parent')->where('employee_type_id', '=', $request->get('type'))->get();
        }

        $employeeList = array();
        foreach ($employees as $emp) {
            array_push($employeeList, array($emp->id, $emp->full_name));
        }
        return response()->json($employeeList);
    }

    public function reportView(Request $request)
    {
        $users = User::with('employee_')->get();

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

        $orders = Customer::with('marketeer');

        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereRaw("DATE(created_at) BETWEEN '" . $from . "' AND '" . $to . "'");
        }

        if ($request->get('marketeer') && $request->get('marketeer') > 0) {
            $orders = $orders->whereRaw('marketeer_id =' . $request->get('marketeer'));
        }

        if ($request->get('customer')) {
            $orders = $orders->where('id', $request->get('customer'));
        }

        $orders = $orders->orderBy('f_name', 'ASC');
        $orders = $orders->paginate(20);

        return view('customerManage::customer.report-list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'customerList' => $customerList, 'customer' => $request->get('customer'), 'from' => $request->get('from'), 'to' => $request->get('to')]);
    }

    public function customerReportDownload(Request $request)
    {
        $orders = Customer::with('marketeer');

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');

            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
            $orders = $orders->whereRaw("DATE(created_at) BETWEEN '" . $from . "' AND '" . $to . "'");
        } else {
            $from = 'All';
        }

        $marketeer = '';
        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('marketeer_id =' . $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }


        $cus_all = '';
        if ($request->get('customer')) {
            $orders = $orders->where('id', $request->get('customer'));
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
        } else {
            $customer = 'All';
        }

        /*$orders = $orders->groupBy('invoice.id');*/
        $orders = $orders->orderBy('f_name', 'asc');
        $orders = $orders->get();

        $header = ['customer' => $customer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'marketeer' => $marketeer];


        if ($orders) {
            $page1 = view('customerManage::print.customer-list')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

        //return $orders;
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



}