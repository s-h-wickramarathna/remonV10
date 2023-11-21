<?php
namespace Application\AlbumType\Http\Controllers;

use Application\AlbumType\Http\Requests\AlbumTypeRequest;
use Application\AlbumType\Models\AlbumType;
use Application\ProductCategory\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Excel;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller {

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
		//$categoryList = ProductCategory::where('status',1)->orderBy('category_name', 'asc')->pluck('category_name', 'id');
		//$categoryList->prepend('-- select category / add new category --', '');
		//$brandList = Product_Brand::where('status',1)->orderBy('brand', 'asc')->pluck('brand', 'id');
		//$brandList->prepend('-- select brand / add new brand --', '');
		//$rangeList = Product_Range::where('status',1)->orderBy('range_name', 'asc')->pluck('range_name', 'id');
		//$rangeList->prepend('-- select range / add new range --', '');
		return view( 'typeViews::types.add');
	}


	/**
	 * Add new product data to database
	 *
	 * @return Redirect to product add
	 */
	public function add(AlbumTypeRequest $request)
	{

		DB::beginTransaction();
		$type = AlbumType::create([
			'type'	=>  $request->get('type')
		]);

        if(!$type){
            DB::rollback();
            return redirect( 'albumType/add' )->with([ 'error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title'   => 'Failed..!' ])->withInput();
        }else{
            DB::commit();
            return redirect( 'albumType/add' )->with([ 'success' => true,
                'success.message' => 'Album type  successfully! added',
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
        $permissions = Permission::whereIn('name', ['size.list', 'admin'])->where('status', '=', 1)->pluck('name');
        return view( 'typeViews::types.list' )->with(['permissions'=>$user->hasAnyAccess($permissions)]);

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

        //$request->ajax()
        if(true){
            $user = Sentinel::getUser();
            $data = DB::select(" SELECT
									      id,
								          type,
								          active_status

								FROM
								    ramon_album_type

								ORDER BY `type`
								");
            $jsonList = array();
            $i=1;

            foreach ($data as $key => $size) {
                $dd = array();
                array_push($dd, $size->id);
                array_push($dd, $size->type);

				$status_permission = Permission::whereIn('name', ['albumType.status', 'admin'])->where('status', '=', 1)->pluck('name');
				if($user->hasAnyAccess($status_permission)){
					if($size->active_status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}

				}else{
					if($size->active_status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="'.$size->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}

                $permissions = Permission::whereIn('name', ['albumType.edit', 'admin'])->where('status', '=', 1)->pluck('name');
                if($user->hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('albumType/edit/'.$size->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit product" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }else{
                    array_push($dd, '<a href="#" class="blue" onclick="false" data-toggle="tooltip" data-placement="top" title="Edit Product Disabled - Persmisson Deined" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;

            }
   return  $jsonList;
            return Response::json(array('data'=>$jsonList));
        }else{
            return Response::json(array('data'=>[]));
        }

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

			$size = AlbumType::find($id);
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
		$type = AlbumType::find($id);

		if($type){
			return view('typeViews::types.edit')->with(['type' => $type]);
		}else{
			return view('errors.404');
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(Request $request, $id)
	{
		DB::beginTransaction();



		$type = AlbumType::find($id);
		$type->type=$request->get('type');

		$type->save();

		if(  !$type)
		{
			DB::rollback();
			return redirect( 'albumType/edit/'.$id )->with([ 'error' => true,
				'error.message' => 'Same thing went wrong...!',
				'error.title'   => 'Failed..!' ])->withInput();

		} else {
			DB::commit();
			return redirect( 'albumType/list' )->with([ 'success' => true,
				'success.message'=> 'Types updated successfully!',
				'success.title'   => 'Success..!' ]);
		}
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

}
