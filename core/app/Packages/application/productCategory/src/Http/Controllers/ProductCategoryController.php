<?php
namespace Application\ProductCategory\Http\Controllers;

use Application\ProductCategory\Models\ProductCategory;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Application\ProductCategory\Http\Requests\ProductCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ProductCategoryController extends Controller {

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
		return view( 'productCategoryViews::productCategory.add' );
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function add(ProductCategoryRequest $request)
	{
		$productCategory = $request->get( 'productCategory' );

		$productCategory = ProductCategory::create([
			'category_name'			=> $productCategory
		]);

		return redirect( 'productCategory/add' )->with([ 'success' => true,
			'success.message' => 'Product category successfully! added',
			'success.title'   => 'Well Done!' ]);
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		$user = Sentinel::getUser();
		$permissions = Permission::whereIn('name', ['product.add', 'admin'])->where('status', '=', 1)->pluck('name');
		return view( 'productCategoryViews::productCategory.list' )->with(['permissions'=>$user->hasAnyAccess($permissions)]);
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		// dd($request->ajax());

		if($request->ajax()){
			$user = Sentinel::getUser();
			// dd($user);
			$data = DB::select("SELECT
									id,
								    category_name,
								    status
								FROM
								    
								    product_category
								GROUP BY id
								ORDER BY category_name
									   "
			);

			$jsonList = array();
			$i=1;
			foreach ($data as $key => $productCategory) {
				$dd = array();
				array_push($dd, $productCategory->id);
				array_push($dd, $productCategory->category_name);


				$status_permission = Permission::whereIn('name', ['ProductCategory.status', 'admin'])->where('status', '=', 1)->pluck('name');
				if($user->hasAnyAccess($status_permission)){
					if($productCategory->status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$productCategory->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$productCategory->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}else{
					if($productCategory->status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Category Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="'.$productCategory->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Category Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="'.$productCategory->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}

				$permissions = Permission::whereIn('name', ['ProductCategory.status', 'admin'])->where('status', '=', 1)->pluck('name');
				if($user->hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('productCategory/edit/'.$productCategory->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit product Category" style="text-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');

				}else{
					array_push($dd, '<a href="#" class="blue" onclick="false" data-toggle="tooltip" data-placement="top" title="Edit Product Disabled - Persmisson Deined" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
				}
		        array_push($jsonList, $dd);
				$i++;
			}
			return Response::json(array('data'=>$jsonList));
		}else{
			dd('ajax not work');
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

			$productCategory = ProductCategory::find($id);
			if($productCategory){
				$productCategory->status = $status;
				$productCategory->save();
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
	 *
	 * @return Response
	 */
	public function editView($id)
	{
		 $productCategory = DB::select("SELECT 
										    category_name
										FROM
										    product_category  where id= '$id'
										GROUP BY id
										 "
									    );
		if($productCategory){
			return view('productCategoryViews::productCategory.edit')->with([
				'productCategory' => $productCategory
			]);
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
		$productCategory = ProductCategory::find($id);
		$productCategory->category_name=$request['productCategory'];
		$productCategory->save();
		return redirect( 'productCategory/list' )->with([ 'success' => true,
				'success.message'=> 'Product Category updated successfully!',
				'success.title' => 'Well Done!' ]);
	}
}
