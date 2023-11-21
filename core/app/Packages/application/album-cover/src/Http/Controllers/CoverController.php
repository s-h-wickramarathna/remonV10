<?php
namespace Application\AlbumCover\Http\Controllers;

use Application\AlbumCover\Http\Requests\AlbumCoverRequest;
use Application\AlbumCover\Models\AlbumCover;
use Application\ProductCategory\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Excel;
use Illuminate\Support\Facades\DB;

class CoverController extends Controller {

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
		return view( 'coverViews::cover.add');
	}


	/**
	 * Add new product data to database
	 *
	 * @return Redirect to product add
	 */
	public function add(AlbumCoverRequest $request)
	{


		DB::beginTransaction();
		$type = AlbumCover::create([
			'cover'	=>  $request->get('cover')
		]);


        if(!$type){
            DB::rollback();
            return redirect( 'albumCover/add' )->with([ 'error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title'   => 'Failed..!' ])->withInput();
        }else{
            DB::commit();
            return redirect( 'albumCover/add' )->with([ 'success' => true,
                'success.message' => 'Cover  successfully! added',
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
        $permissions = Permission::whereIn('name', ['cover.list', 'admin'])->where('status', '=', 1)->pluck('name');
        return view( 'coverViews::cover.list' )->with(['permissions'=>$user->hasAnyAccess($permissions)]);

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
								          cover,active_status

								FROM
								    ramon_cover

								ORDER BY `cover`
								");
            $jsonList = array();
            $i=1;

            foreach ($data as $key => $cover) {
                $dd = array();
                array_push($dd, $cover->id);
                array_push($dd, $cover->cover);

				$status_permission = Permission::whereIn('name', ['cover.status', 'admin'])->where('status', '=', 1)->pluck('name');
				if($user->hasAnyAccess($status_permission)){
					if($cover->active_status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$cover->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$cover->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}

				}else{
					if($cover->active_status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="'.$cover->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="'.$cover->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}

                $permissions = Permission::whereIn('name', ['cover.edit', 'admin'])->where('status', '=', 1)->pluck('name');
                if($user->hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('albumCover/edit/'.$cover->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit product" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
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

			$size = AlbumCover::find($id);
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
        $cover = AlbumCover::find($id);

		if($cover){
			return view('coverViews::cover.edit')->with(['cover' => $cover]);
		}else{
			return view('errors.404');
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(AlbumCoverRequest $request, $id)
	{
		DB::beginTransaction();



		$cover = AlbumCover::find($id);
		$cover->cover=$request->get('cover');

		$cover->save();

		if(  !$cover)
		{
			DB::rollback();
			return redirect( 'albumCover/edit/'.$id )->with([ 'error' => true,
				'error.message' => 'Same thing went wrong...!',
				'error.title'   => 'Failed..!' ])->withInput();

		} else {
			DB::commit();
			return redirect( 'albumCover/list' )->with([ 'success' => true,
				'success.message'=> 'Cover updated successfully!',
				'success.title'   => 'Success..!' ]);
		}
	}


}
