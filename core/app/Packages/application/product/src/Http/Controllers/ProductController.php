<?php
namespace Application\Product\Http\Controllers;

use Application\AlbumSize\Models\AlbumSize;
use Application\Product\Http\Requests\ProductRequests;
use Application\Product\Models\Product;
use Application\ProductCategory\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Core\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Excel;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductController extends Controller {

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
		$categoryList = ProductCategory::where('status',1)->orderBy('category_name', 'asc')->pluck('category_name', 'id');
		$categoryList->prepend('-- select category / add new category --', '');
		$sizeList = AlbumSize::where('active_status',1)->orderBy('sizes', 'asc')->pluck('sizes', 'id');
		$sizeList->prepend('-- select sizes / add new sizes --', '');
		return view( 'productViews::product.add' )->with(['sizeList' => $sizeList, 'categoryList' => $categoryList]);
	}


	/**
	 * Add new product data to database
	 *
	 * @return Redirect to product add
	 */
	public function add(ProductRequests $request)
	{

		DB::beginTransaction();

		$category = $request->get('category');
		if (!is_numeric($category)) {
			$category = ProductCategory::firstOrCreate(
				[
					'category_name' => $category
				]
			);
			$category = $category->id;

		}

		$size = $request->get('size');
		if (!is_numeric($size)) {
            $size = AlbumSize::firstOrCreate(
				[
					'sizes' => $size
				]
			);
            $size = $size->id;
		}

		$product = Product::create([
			'product_name'	=>  $request->get('productName'),
			'description'	=>  $request->get('description'),
			'pack_size'	=>  $request->get('pack_size'),
			'short_code'	=>  $request->get('short_code'),
			'tax_code'	=>  $request->get('tax_code'),
			'product_category_id' =>  $category,
			'size_id' =>  $size

		]);

		if( !$category  || !$product)
		{
			DB::rollback();
			return redirect( 'product/add' )->with([ 'error' => true,
				'error.message' => 'Same thing went wrong...!',
				'error.title'   => 'Failed..!' ])->withInput();

		} else {
			DB::commit();
			return redirect( 'product/add' )->with([ 'success' => true,
				'success.message' => 'Product added successfully!',
				'success.title'   => 'Success..!' ]);
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
        $permissions = Permission::whereIn('name', ['product.add', 'admin'])->where('status', '=', 1)->pluck('name');
        return view( 'productViews::product.list' )->with(['permissions'=>$user->hasAnyAccess($permissions)]);

    }

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
        $set= $this->listViewdata();
        return Response::json(array('data'=>$set));

	}

    public function listViewdata()
    {

            $data = Product::with('category')->where('status',1)->get();
            $jsonList = array();
            $i=1;
            $user = Sentinel::getUser();
            foreach ($data as $key => $product) {
                $dd = array();
                array_push($dd, $product->id);
                array_push($dd, $product->product_name);
                array_push($dd, $product->description);
                array_push($dd, $product->category->category_name);
                array_push($dd, $product->short_code);


				$status_permission = Permission::whereIn('name', ['product.status', 'admin'])->where('status', '=', 1)->pluck('name');
				if($user->hasAnyAccess($status_permission)){
					if($product->status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$product->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$product->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}else{
					if($product->status == 1){
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Inactive Disabled - Permission Deaned"><input class="menu-activate" disabled checked type="checkbox" value="'.$product->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}else{
						array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Product Active Disabled - Permission Deaned"><input class="menu-activate" type="checkbox" disabled value="'.$product->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
					}
				}

                $permissions = Permission::whereIn('name', ['product.edit', 'admin'])->where('status', '=', 1)->pluck('name');
                if($user->hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('product/edit/'.$product->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit product" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }else{
                    array_push($dd, '<a href="#" class="blue" onclick="false" data-toggle="tooltip" data-placement="top" title="Edit Product Disabled - Persmisson Deined" style="test-align:center;background: #3F51B5;padding: 5px;border-radius: 2px;"><i  class="fa fa-pencil"></i></a>');
                }



                array_push($jsonList, $dd);
                $i++;
            }
            return $jsonList;


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
		$product = Product::with('category')->find($id);
		$categoryList = ProductCategory::where('status',1)->orderBy('category_name', 'asc')->pluck('category_name', 'id');
		$sizeList = AlbumSize::where('active_status',1)->orderBy('sizes', 'asc')->pluck('sizes', 'id');
		if($product){
			return view('productViews::product.edit')->with(['product' => $product, 'categoryList' => $categoryList, 'sizeList'=>$sizeList]);
		}else{
			return view('errors.404');
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(ProductRequests $request, $id)
	{
		DB::beginTransaction();
		$category = $request->get('category');
		if (!is_numeric($category)) {
			$category = ProductCategory::firstOrCreate(
				[
					'category_name' => $category
				]
			);
			$category = $category->id;
		}

		$size = $request->get('size');
		if (!is_numeric($size)) {
			$size = AlbumSize::firstOrCreate(
				[
					'sizes' => $size
				]
			);
			$size = $size->id;
		}

		$product = Product::find($id);
		$product->product_name=$request->get('productName');
		$product->description=$request->get('description');
		$product->short_code=$request->get('short_code');
		$product->product_category_id = $category;
		$product->size_id = $size;
		$product->save();


		if( !$category  || !$product || !$size)
		{
			DB::rollback();
			return redirect( 'product/edit/'.$id )->with([ 'error' => true,
				'error.message' => 'Same thing went wrong...!',
				'error.title'   => 'Failed..!' ])->withInput();

		} else {
			DB::commit();
			return redirect( 'product/list' )->with([ 'success' => true,
				'success.message'=> 'Product updated successfully!',
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
