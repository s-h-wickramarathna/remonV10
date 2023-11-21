<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/10/2015
 * Time: 10:23 AM
 */

namespace Application\EmployeeManage\Http\Controllers;

use App\Http\Controllers\Controller;
use Core\Permissions\Models\Permission;
use Application\EmployeeManage\Http\Requests\EmployeeTypeRequest;
use Application\EmployeeManage\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class EmployeeTypeController extends Controller
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
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function addView()
    {
        $typeList = EmployeeType::where('status',1)->get()->pluck('type','id');
        $typeList->prepend('-- select parent --', "");
        return view( 'employeeManage::type.add' )->with(['parentList'=>$typeList]);
    }

    /**
     * add new employee type data to database
     * @param $request
     * @return Redirect to type add
     */
    public function add(EmployeeTypeRequest $request)
    {
       $type = EmployeeType::create(['type' => $request->get('type'),'parent'=>$request->get('parent')]);
       return redirect()->back()->with([ 'success' => true,
            'success.message' => 'Employee type added successfully!',
            'success.title'   => 'Success..!' ]);

    }

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function listView()
    {
        $permissions = Permission::whereIn('name', ['employee.type.add', 'admin'])->where('status', '=', 1)->pluck('name');
        return view('employeeManage::type.list')->with(['permission' => Sentinel::hasAnyAccess($permissions)]);
    }

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function jsonList()
    {
        $data = EmployeeType::all();
        $jsonList = array();
        $i=1;
        foreach ($data as $type) {
            $rowData= array();
            array_push($rowData,$i);
            array_push($rowData,$type->type);
            array_push($rowData,$type->getParent($type->parent)->type);

            $permissions = Permission::whereIn('name', ['employee.type.edit', 'admin'])->where('status', '=', 1)->pluck('name');

            if (Sentinel::hasAnyAccess($permissions)) {
                array_push($rowData, '<a href="#" class="blue" onclick="window.location.href=\'' . url('employee/type/edit/' . $type->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Type" style="background: #3F51B5;padding: 5px;border-radius: 2px;"><i class="fa fa-pencil"></i></a>');
            } else {
                array_push($rowData, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
            }

            $permissions = Permission::whereIn('name', ['employee.type.delete', 'admin'])->where('status', '=', 1)->pluck('name');
            if (Sentinel::hasAnyAccess($permissions)) {
                array_push($rowData, '<a href="#" class="red type-delete" data-id="' . $type->id . '" data-toggle="tooltip" data-placement="top" title="Delete Type" style="background: #CC1A6C;padding: 5px;border-radius: 2px;"><i class="fa fa-trash-o"></i></a>');
            } else {
                array_push($rowData, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled"><i class="fa fa-trash-o"></i></a>');
            }


            array_push($jsonList, $rowData);
            $i++;

        }
        return Response::json(array('data' => $jsonList));

    }

    /**
     * Delete a Employee Type
     * @param  Request $request type id
     * @return Json           	json object with status of success or failure
     */
    public function delete(Request $request)
    {
        if($request->ajax()){
            $id = $request->input('id');
            $type = EmployeeType::find($id);
            if($type){
                $type->delete();
                return response()->json(['status' => 'success']);
            }else{
                return response()->json(['status' => 'invalid_id']);
            }
        }else{
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
        $type = EmployeeType::find($id);
        $typeList = EmployeeType::where('status',1)->get()->pluck('type','id');
        return view( 'employeeManage::type.edit' )->with(['type'=>$type, 'parentList'=>$typeList]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function edit(EmployeeTypeRequest $request, $id)
    {
        $type = EmployeeType::find($id);
        $type->type = $request->get('type');
        $type->parent = $request->get('parent');
        $type->save();
        return redirect( 'employee/type/list' )->with([ 'success' => true,
            'success.message'=> 'Type updated successfully!',
            'success.title' => 'Success...!' ]);
    }
}