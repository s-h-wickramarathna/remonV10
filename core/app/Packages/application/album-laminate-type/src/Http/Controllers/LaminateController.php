<?php
namespace Application\LaminateType\Http\Controllers;

use Application\LaminateType\Http\Requests\LaminateTypeRequest;
use Application\LaminateType\Models\LaminateType;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Excel;
use Illuminate\Support\Facades\DB;

class LaminateController extends Controller {

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
		return view( 'laminateViews::laminate.add');
	}


	/**
	 * Add new product data to database
	 *
	 * @return Redirect to product add
	 */
	public function add(LaminateTypeRequest $request)
	{


		DB::beginTransaction();
		$type = LaminateType::create([
			'laminate_type'	=>  $request->get('laminate')
		]);


        if(!$type){
            DB::rollback();
            return redirect( 'laminateType/add' )->with([ 'error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title'   => 'Failed..!' ])->withInput();
        }else{
            DB::commit();
            return redirect( 'laminateType/add' )->with([ 'success' => true,
                'success.message' => 'Laminate  successfully! added',
                'success.title'   => 'Well Done!' ]);

        }


	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
    public function listView(Request $request)
    {
        $user = Sentinel::getUser();
        $permissions = Permission::whereIn('name', ['laminate.list', 'admin'])->where('status', '=', 1)->pluck('name');
        return view( 'laminateViews::laminate.list' )->with(['permissions'=>$user->hasAnyAccess($permissions)]);

    }

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
        $set= $this->listViewdata($request);
        return Response::json(array('data'=>$set));

	}

    public function listViewdata(Request $request)
    {


            $user = Sentinel::getUser();
            $data = DB::select(" SELECT
									      id,
								          laminate_type,active_status

								FROM
								    ramon_laminate_type

								ORDER BY `laminate_type`
								");
            $jsonList = array();
            $i=1;

            foreach ($data as $key => $laminate) {
                $dd = array();
                array_push($dd, $laminate->id);
                array_push($dd, $laminate->laminate_type);

				$status_permission = Permission::whereIn('name', ['laminate.status', 'admin'])->where('status', '=', 1)->pluck('name');
				if($user->hasAnyAccess($status_permission)){
					if($laminate->active_status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$laminate->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$laminate->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}

				}else{
					if($laminate->active_status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="'.$laminate->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="'.$laminate->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}

                $permissions = Permission::whereIn('name', ['laminate.edit', 'admin'])->where('status', '=', 1)->pluck('name');
                if($user->hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('laminateType/edit/'.$laminate->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit product" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }else{
                    array_push($dd, '<a href="#" class="blue" onclick="false" data-toggle="tooltip" data-placement="top" title="Edit Product Disabled - Persmisson Deined" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;

            }
            return  $jsonList;


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

			$size = LaminateType::find($id);
			if($size){
				$size->active_status = $status;
				$size->save();
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
        $laminate = LaminateType::find($id);

		if($laminate){
			return view('laminateViews::laminate.edit')->with(['laminateType' => $laminate]);
		}else{
			return view('errors.404');
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(LaminateTypeRequest $request, $id)
	{
		DB::beginTransaction();

		$laminate = LaminateType::find($id);
		$laminate->laminate_type=$request->get('laminate');

		$laminate->save();

		if(!$laminate)
		{
			DB::rollback();
			return redirect( 'laminateType/edit/'.$id )->with([ 'error' => true,
				'error.message' => 'Same thing went wrong...!',
				'error.title'   => 'Failed..!' ])->withInput();

		} else {
			DB::commit();
			return redirect( 'laminateType/list' )->with([ 'success' => true,
				'success.message'=> 'Laminate updated successfully!',
				'success.title'   => 'Success..!' ]);
		}
	}


}
