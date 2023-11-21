<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\TargetManage\Http\Controllers;


use App\Http\Controllers\Controller;
use Application\EmployeeManage\Models\Rep;
use Application\TargetManage\Http\Requests\TargetRequest;
use Application\TargetManage\Models\MarketeerTarget;
use Illuminate\Http\Request;
use Application\EmployeeManage\Models\Employee;
use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Classes\Common;


class TargetController extends Controller
{

    /**
     * Show the vehicle add screen to the user.
     *
     * @return Response
     */
    public function addView()
    {
        $repList = Employee::where('employee_type_id',2)->get()->pluck('full_name', 'id');
        return view('targetManage::add')->with(['marketeerList' =>  $repList]);
    }

    /**
     * add new target data to database
     * @param $request
     * @return Redirect to target add
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
                `marketeer_target`
            WHERE
                `marketeer_target`.`deleted_at` IS NULL
                    AND `employee_id` = '$marketeer'
                    AND ('$from' BETWEEN `from` AND `to`
                    OR '$to' BETWEEN `from` AND `to`)"
        );
        if (count($isAdded) > 0) {
            return redirect('marketeerTarget/add')->with(['warning' => true,
                'warning.message' => 'Target already added...!',
                'warning.title' => 'Warning..!'])->withInput();
        }

        MarketeerTarget::create(
            [
                'employee_id' => $request->get('marketeer'),
                'value' => $request->get('value'),
                'from' => explode(' - ', $date)[0],
                'to' => explode(' - ', $date)[1]
            ]
        );
        return redirect('marketeerTarget/add')->with(['success' => true,
            'success.message' => 'Target added successfully!',
            'success.title' => 'Success..!']);

    }

    /**
     * Show the menu list screen to the user.
     *
     * @return Response
     */
    public function listView()
    {
        $per = [];
        $isAdd = false;
        $per['viewable'] = false;
        $permissions = Permission::whereIn('name', ['marketeerTarget.target.edit', 'admin'])->where('status', '=', 1)->pluck('name');
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

       
        
        return view('targetManage::list')->with(['permissons' => json_encode($per),'isadd'=>$isadd]);
    }

    /**
     * Show the vehicle list data to the user.
     *
     * @return Response
     */
    public function jsonList(Request $r)
    {
        $cur = $r->get('currentPage');
        $size = $r->get('pageSize');
        $skip = ($cur * $size) - ($size);
        $user =  Sentinel::getUser();
        if($user->employee_id == 1 || trim($user->roles[0]->name) == 'admin'){
            $targets = MarketeerTarget::take($size)->skip($skip)->get();
        }elseif(trim($user->roles[0]->name) == 'marketer'){
            $targets = MarketeerTarget::take($size)->skip($skip)->where('employee_id',$user->employee_id)->get();
        }
        $count = MarketeerTarget::count();
        $targetList = array();
        foreach ($targets as $target) {
            array_push($targetList,
                array(
                    'id' => $target->id,
                    'first_name' => $target->employee->first_name . ' ' . $target->employee->last_name,
                    'value' => 'Rs. '.$target->value,
                    'from' => $target->from,
                    'to' => $target->to
                )
            );
        }
        return response()->json(array('data' => $targetList, 'count' => $count));
    }

    /**
     * Show the edit target to the user.
     * @param type id
     * @return Response
     */
    public function editView($id)
    {
        $target = MarketeerTarget::with('employee')->find($id);

        //$repList = Employee::where('employee_type_id',$id)->get()->pluck('full_name', 'id');
        return view('targetManage::edit')->with(['target' => $target]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function edit(Request $request, $id)
    {
        $date = $request->get('dateRange');
        $newdate=date_create($date);
        $startdate= date_format($newdate,"Y-m-01");
        $finishdate= date_format($newdate,"Y-m-31");
        $target = MarketeerTarget::find($id);
        $target->employee_id = $request->get('empID');
        $target->value = $request->get('value');
        $target->from = $startdate;
        $target->to = $finishdate;
        $target->save();
        return redirect()->back()->with(['success' => true,
            'success.message' => 'Target updated successfully!',
            'success.title' => 'Success...!']);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to vehicle edit
     */
    public function filter(Request $request)
    {
        $filterArr = json_decode($request->get('data'));
        $cur = $request->get('currentPage');
        $size = $request->get('pageSize');
        $skip = ($cur * $size) - ($size);
        $arr = array();
        $count = 0;
        $sql = 'SELECT * FROM ramon_marketeer_target INNER JOIN employee ON ramon_marketeer_target.employee_id=employee.id ';
        if ($filterArr[0]->value != "") {
            $sql .= 'WHERE ';
            for ($i = 0; $i < count($filterArr); $i++) {
                if ($i > 0) {
                    $sql .= ' AND ';
                }
                $sql .= ' `' . $filterArr[$i]->name . '` LIKE "%' . $filterArr[$i]->value . '%" ';
            }
//            $sql .= 'AND 4ever_rep_target.deleted_at = null ';
            $targets = DB::select($sql);
            $count = count($targets);
            $sql .= ' LIMIT ' . $skip . ',' . ($cur * $size);
            $targets = DB::select($sql);

            foreach ($targets as $target) {
                array_push($arr,
                    array(
                        'id' => $target->id,
                        'first_name' => $target->first_name . ' ' . $target->last_name,
                        'value' => $target->value,
                        'from' => $target->from,
                        'to' => $target->to
                    )
                );
            }
        } else {
            $targets = MarketeerTarget::take($size)->skip($skip)->get();
            foreach ($targets as $target) {
                array_push($arr,
                    array(
                        'id' => $target->id,
                        'first_name' => $target->employee[0]->first_name . ' ' . $target->employee[0]->last_name,
                        'value' => $target->value,
                        'from' => $target->from,
                        'to' => $target->to
                    )
                );
            }
            $count = MarketeerTarget::count();
        }
        return response()->json(array('data' => $arr, 'count' => $count));
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
            $target = RepTarget::find($id);
            if ($target) {
                $target->delete();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }
}