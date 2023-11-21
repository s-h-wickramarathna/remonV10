<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\EmployeeManage\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Core\UserManage\Models\User;
use Illuminate\Http\Request;
use Application\EmployeeManage\Http\Requests\EmployeeRequest;
use Application\EmployeeManage\Models\Employee;
use Application\EmployeeManage\Models\EmployeeType;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Application\EmployeeManage\Models\Rep;


class EmployeeController extends Controller
{

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function addView()
    {
        $empType = EmployeeType::select('type', 'id')->get();
        $parentList = Employee::where('status', 1)->select('first_name', 'last_name', 'id')->get();
        return view('employeeManage::employee.add')->with(['type' => $empType, 'parentList' => $parentList]);
    }

    /**
     * add new employee type data to database
     * @param $request
     * @return Redirect to type add
     */
    public function add(EmployeeRequest $request)
    {
        if ($request->get('empType') > 0) {
            //if ($request->get('parent') > 0) {

            $parent = Employee::find(2);

            $employee = Employee::create([
                'first_name' => $request->get('fName'),
                'last_name' => $request->get('lName'),
                'email' => $request->get('email'),
                'employee_type_id' => $request->get('empType'),
                'mobile' => $request->get('mobile'),
                'land' => $request->get('land'),
                'cheque_name' => $request->get('cheque_name'),
                'business_name' => $request->get('business_name'),
                'code' => $request->get('sCode'),
                'address' => $request->get('address'),
                'credit_limit' => $request->get('credit_limit')
            ]);


            $employee->makeChildOf($parent);
            //$type = EmployeeType::find($request->get('empType'));


            /*if ($type->type == 'Rep') {
                $rep = Rep::create([
                    'employee_id' => $employee->id,
                    'csr_dsr' => 'csr',
                    'mobile_user_name' => $request->get('uName'),
                    'short_code' => $request->get('sCode'),
                    'mobile_password' => md5($request->get('cPassword'))
                ]);

                $rep->user_token = md5($rep->id);
                $rep->save();
            }*/


            return redirect('employee/add')->with(['success' => true,
                'success.message' => 'Employee added successfully!',
                'success.title' => 'Well Done!']);

        } else {
            return redirect('employee/add')->withInput()->withErrors(array('empType' => 'Employee Type is required'));
        }

    }

    /**
     * Show the menu list screen to the user.
     *
     * @return Response
     */
    public function listView(Request $r)
    {
        return view('employeeManage::employee.list');
    }

    /**
     * Show the employee list data to the user.
     *
     * @return Response
     */
    public function jsonList(Request $r)
    {
        $cur = $r->get('currentPage');
        $size = $r->get('pageSize');
        $skip = ($cur * $size) - ($size);
        $empl = DB::table('employee')
            ->join('employee_type', 'employee_type.id', '=', 'employee.employee_type_id')
            ->select('employee.id','employee.first_name', 'employee.last_name', 'employee.email', 'employee_type.type', 'employee.id', 'employee.mobile', 'employee.address')
            ->where('employee.parent', '>', 0)
            ->take($size)
            ->skip($skip)
            ->get();
        $count = Employee::count();
        return response()->json(array('data' => $empl, 'count' => $count));
    }

    /**
     * Activate or Deactivate Employee
     * @param  Request $request employee id with status to change
     * @return Json            json object with status of success or failure
     */
    public function status(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');
            $employee = Employee::find($id);
            if ($employee) {
                $employee->status = $status;
                $employee->save();
                $dsi_user = User::where('employee_id', $id)->get();
                $user = Sentinel::findById($dsi_user[0]->id);
                if ($dsi_user) {
                    $update_user = User::find($dsi_user[0]->id);
                    $update_user->status = $status;
                    $update_user->save();
                    if ($status != 0) {
                        $acUser = Activation::create($user);
                        Activation::complete($user, $acUser->code);
                    } else {
                        Activation::remove($user);
                    }
                }
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
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
        $employer = Employee::with('type')->find($id);
        $employee = array();
        if (is_object($employer)) {
            if ($employer->type->type == 'Rep') {
                $employee = Employee::join('rep as r', 'r.employee_id', '=', 'employee.id')
                    ->select(
                        'employee.first_name',
                        'employee.last_name',
                        'employee.employee_type_id',
                        'employee.email',
                        'employee.parent',
                        'r.mobile_user_name',
                        'r.short_code',
                        'r.id as repId',
                        'employee.mobile',
                        'employee.address'
                    )
                    ->find($id);
            } else {
                $employee = Employee::
                select(
                    'first_name',
                    'last_name',
                    'employee_type_id',
                    'email',
                    'parent',
                    'first_name as mobile_user_name',
                    'id as repId',
                    'employee.mobile',
                    'employee.cheque_name',
                    'employee.business_name',
                    'employee.code as short_code',
                    'employee.address',
                    'employee.credit_limit'
                )
                    ->find($id);
            }
        }
        $empType = EmployeeType::all()->pluck('type', 'id');
        $parentList = Employee::where('status', 1)->get()->pluck('full_name', 'id');
        //return ['employee' => $employee, 'type' => $empType, 'parentList' => $parentList];
        return view('employeeManage::employee.edit')->with(['employee' => $employee, 'type' => $empType, 'parentList' => $parentList]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function edit(EmployeeRequest $request, $id)
    {
        //return $request;

        $parent = 2;
        $emp = Employee::find($id);

        $emp->first_name = $request->get('fName');
        $emp->last_name = $request->get('lName');
        $emp->email = $request->get('email');
        $emp->employee_type_id = $request->get('empType');
        $emp->mobile = $request->get('mobile');
        $emp->land = $request->get('land');
        $emp->address = $request->get('address');
        $emp->cheque_name = $request->get('cheque_name');
        $emp->business_name = $request->get('business_name');
        $emp->code = $request->get('dis_sCode');
        $emp->credit_limit = $request->get('credit_limit');
        $emp->parent = $parent;
        $emp->save();


        return redirect('employee/list')->with(['success' => true,
            'success.message' => 'Employee updated successfully!',
            'success.title' => 'Success..!']);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function filter(Request $request)
    {
        $filterArr = json_decode($request->get('data'));
        $cur = $request->get('currentPage');
        $size = $request->get('pageSize');
        $skip = ($cur * $size) - ($size);
        $count = 0;
        $sql = 'SELECT emp.id,emp.first_name,emp.last_name,emp.email,emp_type.type,emp.mobile,emp.address FROM employee as emp INNER  JOIN employee_type as emp_type ON emp.employee_type_id=emp_type.id ';
        if ($filterArr[0]->value != "") {
            $sql .= 'WHERE ';
            for ($i = 0; $i < count($filterArr); $i++) {
                if ($i > 0) {
                    $sql .= ' AND ';
                }
                $sql .= $filterArr[$i]->name . ' LIKE "%' . $filterArr[$i]->value . '%" ';
            }
            $emp = DB::select($sql);
            $count = count($emp);
            $sql .= ' LIMIT ' . $skip . ',' . ($cur * $size);
            $emp = DB::select($sql);
        } else {
            $emp = Employee::join('employee_type as type', 'type.id', '=', 'employee_type_id')
                ->take($size)
                ->skip($skip)
                ->get();
            $count = Employee::count();
        }
        return response()->json(array('data' => $emp, 'count' => $count));
    }

    /**
     * get data of for selected id
     * @param Request $request location type id
     * @return data object
     */
    public function getViewData(Request $request)
    {
        $employer = Employee::find($request->get('id'));
        if ($employer->type->type == 'Rep') {
            $employer = Employee::join('rep as r', 'r.employee_id', '=', 'employee.id')
                ->find($request->get('id'));
            $arr = array(
                'first_name' => $employer->first_name,
                'last_name' => $employer->last_name,
                'email' => $employer->email,
                'type' => $employer->type->type,
                'mobile_user_name' => $employer->mobile_user_name,
                'short_code' => $employer->short_code,
                'mobile' => $employer->mobile,
                'address' => $employer->address,
                'parent' => count($employer->parent()->get()) > 0 ? $employer->parent()->get()[0]->full_name : ''

            );
            $employee = ['first_name', 'last_name', 'address', 'email', 'mobile', 'type', 'parent', 'mobile_user_name', 'short_code'];
            return response()->json(array($arr, $employee));
        } else if ($employer->type->id == '4') {
            $arr = array(
                'first_name' => $employer->first_name,
                'last_name' => $employer->last_name,
                'mobile' => $employer->mobile,
                'address' => $employer->address,
                'email' => $employer->email,
                'cheque_name' => $employer->cheque_name,
                'business_name' => $employer->business_name,
                'short_code' => $employer->code,
                'type' => $employer->type->type,
                'parent' => count($employer->parent()->get()) > 0 ? $employer->parent()->get()[0]->full_name : ''
            );
            $employee = ['first_name', 'last_name', 'address', 'email', 'mobile', 'type', 'parent', 'cheque_name', 'business_name', 'short_code'];
        } else {
            $arr = array(
                'first_name' => $employer->first_name,
                'last_name' => $employer->last_name,
                'mobile' => $employer->mobile,
                'address' => $employer->address,
                'email' => $employer->email,
                'type' => $employer->type->type,
                'parent' => count($employer->parent()->get()) > 0 ? $employer->parent()->get()[0]->full_name : ''

            );
            $employee = ['first_name', 'last_name', 'address', 'email', 'mobile', 'type', 'parent'];
        }
        return response()->json(array($arr, $employee));
    }

    /**
     * user token check
     * @param $token
     * @return count of users
     *
     */
    public static function checkToken($token)
    {
        $rep = User::where('token', $token)->first();
        return count($rep);
    }

    /**
     * return user id
     * @param $token
     * @return user id
     *
     */
    public static function getUserById($token)
    {
        $rep = User::where('token', $token)->first();
        if (count($rep) > 0) {
            return $rep;
        }
        return 0;

    }

    /**
     * get parent list of filter the type
     * @param Request $request location type id
     * @return parent list
     */
    public function getParent(Request $request)
    {
        $parentArr = array();
        if ($request->get('type') > 0) {
            $type = EmployeeType::find($request->get('type'));
            $employees = Employee::where('employee_type_id', $type->parent)->get();
            foreach ($employees as $employee) {
                array_push($parentArr, array($employee->id, $employee->first_name . ' ' . $employee->last_name));
            }
        }
        return response()->json($parentArr);
    }

    public function genBarCode(Request $request, $id)
    {
        $d = new DNS1D();
        $destinationPath = 'storage/images/qr/emp/' . $id . '/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $d->setStorPath($destinationPath);
        $d->getBarcodePNGPath($id, "C39+", 2, 80,array(0,0,0),true);
        return response()->download('storage/images/qr/emp/' . $id . '/' . $id . '.png');
    }

}