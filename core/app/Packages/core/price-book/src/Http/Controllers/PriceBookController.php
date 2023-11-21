<?php
namespace Core\PriceBook\Http\Controllers;

use App\Exceptions\InvalidExcelException;
use App\Http\Requests\ExcelRequest;
use Core\Permissions\Models\Permission;
use Core\PriceBook\Models\PriceBook;
use Core\PriceBook\Models\PriceBookType;
use Core\PriceBook\Models\PriceBookDetail;
use Core\PriceBook\Models\PriceBookCustom;
use Core\PriceBook\Models\Mrp;
use Application\ProductCategory\Models\ProductCategory;
use Application\Product\Models\Product;
use Application\OutletListManagement\Models\Outlet;
use Application\EmployeeManage\Models\Employee;

use App\Http\Controllers\Controller;
use App\Models\Sbu;
use App\Models\PriceBookCategory;
use Core\PriceBook\Http\Requests\PriceBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TransactionException;
use Exception;

class PriceBookController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Price Book Controller
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
        ini_set('max_input_vars', 10000);
        //$this->middleware('guest');
    }

    /**
     * Show the price book type add screen to the user.
     *
     * @return Response
     */
    public function addPriceBookTypeView()
    {
        return view('priceBook::type.add');
    }

    /**
     * Show the standerd price book add screen to the user.
     *
     * @return Response
     */
    public function addStanderdPriceBookView()
    {
        $sbu = Sbu::all()->pluck('name', 'id');
        $sale_party = PriceBookCategory::all()->pluck('name', 'id')->prepend('Select Category', 0);
        $category = ProductCategory::all()->pluck('category_name', 'id')->prepend('All', 0);
        return view('priceBook::standerd.add')->with(['sbu' => $sbu, 'sale_party' => $sale_party, 'category' => $category]);
    }


    /**
     * Get the Product List.
     *
     * @return Response
     */
    public function jsonListProduct(PriceBookRequest $request)
    {

        if ($request->get('category') == 0) {
            $product = Product::where('status', '=', 1)->get();
            return Response::json([$product]);
        } else {
            $product = ProductCategory::where('id', $request->get('category'))->with(['products'])->first();
            return Response::json([$product->products]);
        }
    }


    /**
     * Get the Product List Details.
     *
     * @return Response
     */
    public function jsonListProductDetail(PriceBookRequest $request)
    {
        //return $request->all();
        $product = [];
        foreach ($request->get('product') as $value) {
            array_push($product, Product::where('id', $value)->with(['category', 'mrp'])->first());
        }

        return Response::json($product);
    }

    /**
     * Get the User List Details.
     *
     * @return Response
     */
    public function jsonListUsers(PriceBookRequest $request)
    {
        if ($request->get('book_category')) {
            if ($request->get('book_category') == 1) {
                $users = Employee::where('employee_type_id', 4)->where('status', '=', 1)->get();
            } else if ($request->get('book_category') == 2) {
                $users = Outlet::where('outlet_sale_type', '=', 3)->where('active_status', '=', 1)->select('id', 'outlet_name', '4ever_location_id as loc_id')->get();
            } else if ($request->get('book_category') == 3) {
                $users = Outlet::where('outlet_sale_type', '=', 1)->where('active_status', '=', 1)->select('id', 'outlet_name', '4ever_location_id as loc_id')->get();
            } else if ($request->get('book_category') == 4) {
                $users = Outlet::where('outlet_sale_type', '=', 2)->where('active_status', '=', 1)->select('id', 'outlet_name', '4ever_location_id as loc_id')->get();
            }
            return Response::json($users);
        } else {
            return Response::json([]);
        }
    }


    /**
     * Return insert status to view
     *
     * @return Response
     */
    public function addStanderdPriceBook(PriceBookRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {

                if ($request->get('basic')) {

                    $pb = PriceBook::where('type', '=', $request->get('basic')[0]['price_book_type'])
                        ->where('category', '=', $request->get('basic')[0]['price_book_category'])
                        ->whereNull('ended_at')
                        ->get();

                    foreach ($pb as $value) {
                        $value->ended_at = date('Y-m-d h:i');
                        $value->status = 0;
                        $value->save();

                        $pd = PriceBookDetail::where('price_book_id', '=', $value->id)
                            ->whereNull('ended_at')
                            ->get();

                        foreach ($pd as $pdd) {
                            $pdd->ended_at = date('Y-m-d h:i');
                            $pdd->save();
                        }

                    }

                    $price_book = PriceBook::create([
                        'type' => $request->get('basic')[0]['price_book_type'],
                        'category' => $request->get('basic')[0]['price_book_category'],
                        'name' => $request->get('basic')[0]['price_book_name'],
                    ]);

                    if ($price_book) {
                        if (!$request->get('detail')) {
                            throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                        } else {

                            if ($request->get('clue') == 0) {

                                foreach ($request->get('detail') as $value) {

                                    $price_book_detail = PriceBookDetail::create([
                                        'price_book_id' => $price_book->id,
                                        'category_id' => $value['category_id'],
                                        'product_id' => $value['product_id'],
                                        'price' => $value['price'],
                                        'effective_date' => $value['effective_date'],
                                    ]);

                                    if (!$price_book_detail) {
                                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                                    }
                                }

                            } else if ($request->get('clue') == 1) {
                                foreach ($request->get('detail') as $key => $value) {
                                    $price_book_detail = PriceBookDetail::create([
                                        'price_book_id' => $price_book->id,
                                        'category_id' => $value['category_id'],
                                        'product_id' => $value['pro_id'],
                                        'price' => $value['price'],
                                        'effective_date' => $value['effective_date'],
                                    ]);
                                    if (!$price_book_detail) {
                                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                                    }
                                }
                            }
                        }
                    } else {
                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                    }

                } else {
                    throw new TransactionException('No name', 101);
                }
            });
            return Response::json([1]);
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json([0]);
            } else if ($e->getCode() == 101) {
                return Response::json([0]);
            }
        } catch (Exception $e) {
            return Response::json([0]);
        }


    }

    /**
     * Show the price book edit view
     *
     * @return Response
     */

    public function editStanderdPriceBookView($id)
    {
        $date = PriceBook::with(['details', 'details.product.mrp', 'details.getProductCategory'])->where('id', '=', $id)->where('type', '=', 1)->first();
        $sbu = Sbu::all()->pluck('name', 'id');
        $sbu->prepend('Select a SBU', '');
        $sale_party = PriceBookCategory::all()->pluck('name', 'id');
        $sale_party->prepend('Select a Price Book Category', '');
        $category = ProductCategory::all()->pluck('category_name', 'id');
        return view('priceBook::standerd.edit')->with(['sbu' => $sbu, 'sale_party' => $sale_party, 'category' => $category, 'dateSet' => $date]);
    }


    /**
     * edit standard price book.
     *
     * @return Response
     */
    public function editStanderdPriceBook(PriceBookRequest $request)
    {
        //return $request->all();
        try {
            DB::transaction(function () use ($request) {

                if ($request->get('basic')) {

                    $PriceBook = PriceBook::find($request->get('id'));
                    $PriceBook->name = $request->get('basic')[0]['price_book_name'];
                    $PriceBook->timestamp = date("Y-m-d H:i:s");
                    $PriceBook->updated_at = date("Y-m-d H:i:s");
                    $PriceBook->save();

                    if (!$request->get('detail')) {
                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                    } else {

                        foreach ($request->get('detail') as $key => $value) {

                            if ($value['details_id'] && $value['details_id'] > 0) {
                                $PriceBookDetail = PriceBookDetail::find($value['details_id']);
                                $PriceBookDetail->ended_at = date("Y-m-d H:i:s");
                                $PriceBookDetail->save();
                            }

                            $price_book_detail = PriceBookDetail::create([
                                'price_book_id' => $request->get('id'),
                                'category_id' => $value['category_id'],
                                'product_id' => $value['pro_id'],
                                'price' => $value['price'],
                                'effective_date' => $value['effective_date'],
                            ]);
                            if (!$price_book_detail) {
                                throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                            }
                        }
                    }

                } else {
                    throw new TransactionException('No name', 101);
                }
            });
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json([0]);
            } else if ($e->getCode() == 101) {
                return Response::json([0]);
            }
        } catch (Exception $e) {
            return Response::json([0]);
        }

        return Response::json([1]);
    }


    public function jsonProductList(Request $request)
    {

        if ($request->ajax()) {

            $data = DB::select(
                    'SELECT 
							`4ever_product`.id as product_id,
							`4ever_product`.product_name as product_name,
							`4ever_product`.description as description,
							`4ever_product`.short_code as short_code,
							`4ever_product`.product_category_id as category_id,
							`4ever_product_category`.category_name as category_name,
							pm.id as mrp_id,
							pm.mrp,
						    pd.price,
						    pd.detail_id
						FROM
							`4ever_product`									
								LEFT JOIN 
							(select * from `4ever_mrp` where `4ever_mrp`.ended_at IS NULL)pm ON `4ever_product`.id = pm.product_id
								LEFT JOIN
							`4ever_product_category` ON `4ever_product`.product_category_id = `4ever_product_category`.id
						    	LEFT JOIN
						    (SELECT 
								`4ever_price_book_detail`.product_id,
						    	`4ever_price_book_detail`.price,
						    	`4ever_price_book_detail`.id as detail_id
							FROM
								`4ever_price_book`
									LEFT JOIN
								`4ever_price_book_detail` ON `4ever_price_book`.id = `4ever_price_book_detail`.price_book_id    	
							WHERE
								`4ever_price_book_detail`.ended_at IS NULL
									AND 
								`4ever_price_book`.id = ' . $request->get('price') . ') pd ON `4ever_product`.id = pd.product_id group by `4ever_product`.id'
                );


            $jsonList = array();
            $i = 1;

            //return $data;
            foreach ($data as $details) {
                $dd = array();
                array_push($dd, $i);
                array_push($dd, $details->short_code . '<input type="hidden" id="details_id_' . $i . '" name="details_id_' . $i . '" value="' . $details->detail_id . '">');
                array_push($dd, $details->product_name . '<input type="hidden" id="product_id_' . $i . '" name="product_id_' . $i . '" value="' . $details->product_id . '">');
                array_push($dd, $details->description);
                array_push($dd, $details->category_name . '<input type="hidden" name="category_id_' . $i . '" id="category_id_' . $i . '" value="' . $details->category_id . '">');
                if ($details->mrp != null) {
                    array_push($dd, $details->mrp);
                } else {
                    array_push($dd, '0.00');
                }
                if ($details->price != null) {
                    array_push($dd, '<input type="number" min="1" id="product_price_' . $i . '" width="100%" class="form-control" name="product_price_' . $i . '" value="' . $details->price . '">');
                } else {
                    array_push($dd, '<input type="number" min="1" id="product_price_' . $i . '" width="100%" class="form-control" name="product_price_' . $i . '" value="0.00">');
                }
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    /**
     * Show the custom price book add screen to the user.
     *
     * @return Response
     */
    public function addCustomPriceBookView()
    {
        $sbu = Sbu::all()->pluck('name', 'id');
        $sale_party = PriceBookCategory::all()->pluck('name', 'id')->prepend('Select Category', 0);
        $category = ProductCategory::all()->pluck('category_name', 'id')->prepend('All', 0);
        return view('priceBook::custom.add')->with(['sbu' => $sbu, 'sale_party' => $sale_party, 'category' => $category]);
    }


    /**
     * Return price book type json list to view
     *
     * @return Response
     */
    public function jsonListPriceBookType(Request $request)
    {
        if ($request->ajax()) {
            $data = PriceBookType::all();

            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $priceBookType) {
                $dd = array();
                array_push($dd, $i);

                array_push($dd, $priceBookType->name);

                $permissions = Permission::whereIn('name', ['price.type.add', 'admin'])->where('status', '=', 1)->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="set_update(' . $priceBookType->id . ')" data-toggle="tooltip" data-placement="top" title="Edit Price Book Type"><i class="fa fa-pencil"></i></a><input type="hidden" id="value_' . $priceBookType->id . '" name="value_' . $priceBookType->id . '" value="' . $priceBookType->name . '">');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
                }

                $permissions = Permission::whereIn('name', ['price.type.add', 'admin'])->where('status', '=', 1)->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="red role-delete" data-id="' . $priceBookType->id . '" data-toggle="tooltip" disabled="disabled" data-placement="top" title="Delete Price"><i class="fa fa-trash-o"></i></a>');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled" disabled="disabled"><i class="fa fa-trash-o"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    /**
     * Return insert status to view
     *
     * @return Response
     */
    public function addPriceBookType(PriceBookRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {

                $type = PriceBookType::create([
                    'name' => $request->get('type')
                ]);

                if (!$type) {
                    throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                }
            });
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json([0]);
            }
        } catch (Exception $e) {
            return Response::json([0]);
        }

        return Response::json([1]);
    }

    /**
     * Return edit status to view
     *
     * @return Response
     */
    public function editPriceBookType(PriceBookRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {

                $type = PriceBookType::find($request->get('type_id'));
                $type->name = $request->get('type');
                $ups = $type->save();
                if (!$ups) {
                    throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                }
            });
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json([0]);
            }
        } catch (Exception $e) {
            return Response::json([0]);
        }

        return Response::json([1]);
    }


    /**
     * Return insert status to view
     *
     * @return Response
     */
    public function addCustomPriceBook(PriceBookRequest $request)
    {


        try {
            DB::transaction(function () use ($request) {

                if ($request->get('basic')) {

                    $price_book = PriceBook::create([
                        'type' => $request->get('basic')[0]['price_book_type'],
                        'category' => $request->get('basic')[0]['price_book_category'],
                        'name' => $request->get('basic')[0]['price_book_name'],
                    ]);
                    $ed;
                    if ($price_book) {
                        if (!$request->get('detail')) {
                            throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                        } else {
                            if ($request->get('clue') == 0) {

                                foreach ($request->get('detail') as $value) {
                                    $ed = $value['effective_date'];

                                    $price_book_detail = PriceBookDetail::create([
                                        'price_book_id' => $price_book->id,
                                        'category_id' => $value['category_id'],
                                        'product_id' => $value['product_id'],
                                        'price' => $value['price'],
                                        'effective_date' => $value['effective_date'],
                                    ]);

                                    if (!$price_book_detail) {
                                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                                    }


                                }

                            } else if ($request->get('clue') == 1) {
                                foreach ($request->get('detail') as $value) {
                                    $ed = $value['effective_date'];
                                    $price_book_detail = PriceBookDetail::create([
                                        'price_book_id' => $price_book->id,
                                        'category_id' => $value['category_id'],
                                        'product_id' => $value['pro_id'],
                                        'price' => $value['price'],
                                        'effective_date' => $value['effective_date'],
                                    ]);
                                    if (!$price_book_detail) {
                                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                                    }
                                }
                            }
                        }
                        if (count($request->get('users')) > 0) {

                            foreach ($request->get('users') as $value) {

                                $tmpd = PriceBookCustom::where('user_id', '=', $value)->get();

                                foreach ($tmpd as $old_price) {
                                    $old_price->ended_at = $ed;
                                    $old_price->save();
                                }

                                // $tmpd=PriceBookCustom::where('user_id','=',$value)->get();
                                // foreach ($tmpd as $pp) {
                                // 	$pp->ended_at=date('Y-m-d H:i:s');
                                // 	$pp->deleted_at=date('Y-m-d H:i:s');
                                // 	$pp->save();
                                // }

                                // $tmpd=PriceBookCustom::where('user_id','=',$value)->delete();

                                if ($request->get('basic')[0]['price_book_category'] == 2 || $request->get('basic')[0]['price_book_category'] == 3 || $request->get('basic')[0]['price_book_category'] == 4) {

                                    $price_book_user = PriceBookCustom::create([
                                        'price_book_id' => $price_book->id,
                                        'user_id' => $value,
                                    ]);
                                } else {

                                    $price_book_user = PriceBookCustom::create([
                                        'price_book_id' => $price_book->id,
                                        'user_id' => $value,
                                    ]);
                                }

                                if (!$price_book_user) {
                                    throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                                }
                            }
                        }

                    } else {
                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                    }

                } else {
                    throw new TransactionException('No name', 101);
                }
            });
            return Response::json([1]);
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json(['Something went wrong']);
            } else if ($e->getCode() == 101) {
                return Response::json(['Something went wrong']);
            } else if ($e->getCode() == 102) {
                return Response::json(["Missing Product Code"]);
            }
        } catch (Exception $e) {
            return Response::json(['Something went wrong']);
        }

    }


    /**
     * Show the standard price book list screen to the user.
     *
     * @return Response
     */
    public function listStanderdPriceBookView()
    {
        return view('priceBook::standerd.list');
    }


    /**
     * Show the custom price book list screen to the user.
     *
     * @return Response
     */
    public function listCustomPriceBookView()
    {
        return view('priceBook::custom.list');
    }


    /**
     * Return standard price book json list to view
     *
     * @return Response
     */
    public function jsonListStanderdPriceBook(Request $request)
    {
        if ($request->ajax()) {
            $data = PriceBook::where('type', '1')->get();

            $jsonList = array();
            $i = 1;
            foreach ($data as $priceBook) {
                $dd = array();
                array_push($dd, $i);

                array_push($dd, $priceBook->name);
                array_push($dd, PriceBookCategory::where('id', $priceBook->category)->get()[0]->name);
                array_push($dd, $priceBook->created_at->toDateTimeString());
                if ($priceBook->ended_at != NULL) {
                    array_push($dd, '<span class="label label-danger" style="font-size: 100%">' . $priceBook->ended_at . '</span>');
                } else {
                    array_push($dd, '<span class="label label-success" style="font-size: 100%">Active</span>');
                }

                $permissions = Permission::whereIn('name', ['price.standerd.add', 'admin'])->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="view_detail(' . $priceBook->id . ')" data-toggle="tooltip" data-placement="top" title="View Price Book"><i class="fa fa-eye"></i></a><input type="hidden" id="value_' . $priceBook->id . '" name="value_' . $priceBook->id . '" value="' . $priceBook->name . '">');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="View Disabled"><i class="fa fa-eye"></i></a>');
                }

                $permissions = Permission::whereIn('name', ['price.standerd.add', 'admin'])->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\'' . url('price/standerd/edit/' . $priceBook->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Standard Price Book"><i class="fa fa-pencil"></i></a><input type="hidden" id="value_' . $priceBook->id . '" name="value_' . $priceBook->id . '" value="' . $priceBook->name . '">');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }


    /**
     * Return standard price book details json list to view
     *
     * @return Response
     */
    public function jsonListStanderdPriceBookDetail(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = PriceBookDetail::where('price_book_id', $id)->whereNull('ended_at')->with(['priceBook', 'product.category'])->get();

            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $priceBookDetail) {
                $dd = array();
                array_push($dd, $i);

                array_push($dd, $priceBookDetail->priceBook->name);
                array_push($dd, $priceBookDetail->product->category->category_name);
                array_push($dd, $priceBookDetail->product->short_code);
                array_push($dd, $priceBookDetail->product->product_name);
                array_push($dd, $priceBookDetail->effective_date);
                array_push($dd, $priceBookDetail->price);

                if ($priceBookDetail->ended_at != NULL) {
                    array_push($dd, '<span class="label label-danger" style="font-size: 100%">' . $priceBookDetail->ended_at . '</span>');
                } else {
                    array_push($dd, '<span class="label label-success" style="font-size: 100%">Active</span>');
                }

                $permissions = Permission::whereIn('name', ['price.standerd.add', 'admin'])->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="view_detail(' . $priceBookDetail->id . ')" data-toggle="tooltip" data-placement="top" title="View Price Book"><i class="fa fa-eye"></i></a><input type="hidden" id="value_' . $priceBookDetail->id . '" name="value_' . $priceBookDetail->id . '" value="' . $priceBookDetail->name . '">');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-eye"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }


    /**
     * Return standard price book json list to view
     *
     * @return Response
     */
    public function jsonListCustomPriceBook(Request $request)
    {
        if ($request->ajax()) {
            $data = PriceBook::where('type', '2')->get();

            $jsonList = array();
            $i = 1;
            foreach ($data as $priceBook) {
                $dd = array();
                array_push($dd, $i);

                array_push($dd, $priceBook->name);
                array_push($dd, PriceBookCategory::where('id', $priceBook->category)->get()[0]->name);

                $price_bu = PriceBook::where('id', $priceBook->id)->with(['price_book_custom_list'])->get();

                $owner = '';

                foreach ($price_bu as $value) {
                    foreach ($value->price_book_custom_list as $emp) {
                        if ($emp->ended_at == NULL) {
                            $owner .= '<span style="color: green">' . $emp->users($value->category)->first()->name . '</span>' . ", ";
                        } else {
                            // $owner.='<span style="color: red">'.$emp->users($value->category)->first()->name.'</span>'.", ";
                        }
                    }
                }
                if ($owner != "") {
                    array_push($dd, $owner);
                } else {
                    array_push($dd, " - ");
                }

                array_push($dd, $priceBook->created_at->toDateTimeString());

                if ($priceBook->ended_at != NULL) {
                    array_push($dd, '<span class="label label-danger" style="font-size: 100%">' . $priceBook->ended_at . '</span>');
                } else {
                    array_push($dd, '<span class="label label-success" style="font-size: 100%">Active</span>');
                }

                $permissions = Permission::whereIn('name', ['price.standerd.add', 'admin'])->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="view_detail(' . $priceBook->id . ')" data-toggle="tooltip" data-placement="top" title="View Price Book"><i class="fa fa-eye"></i></a><input type="hidden" id="value_' . $priceBook->id . '" name="value_' . $priceBook->id . '" value="' . $priceBook->name . '">');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-eye"></i></a>');
                }

                $permissions = Permission::whereIn('name', ['price.custom.edit', 'admin'])->where('status', '=', 1)->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\'' . url('price/custom/edit/' . $priceBook->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Price Book" style="padding: 5px;border-radius: 2px;"><i class="fa fa-pencil"></i></a>');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled" style="background: #3F51B5;padding: 5px;border-radius: 2px;"><i class="fa fa-pencil"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }


    /**
     * Return standard price book details json list to view
     *
     * @return Response
     */
    public function jsonListCustomPriceBookDetail(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = PriceBookDetail::where('price_book_id', $id)->whereNull('ended_at')->with(['priceBook', 'product.category'])->get();

            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $priceBookDetail) {
                $dd = array();
                array_push($dd, $i);

                array_push($dd, $priceBookDetail->priceBook->name);
                array_push($dd, $priceBookDetail->product->category->category_name);
                array_push($dd, $priceBookDetail->product->short_code);
                array_push($dd, $priceBookDetail->product->product_name);
                array_push($dd, $priceBookDetail->effective_date);
                array_push($dd, $priceBookDetail->price);

                if ($priceBookDetail->ended_at != NULL) {
                    array_push($dd, '<span class="label label-danger" style="font-size: 100%">' . $priceBookDetail->ended_at . '</span>');
                } else {
                    array_push($dd, '<span class="label label-success" style="font-size: 100%">Active</span>');
                }

                $permissions = Permission::whereIn('name', ['price.standerd.add', 'admin'])->pluck('name');
                if (Sentinel::hasAnyAccess($permissions)) {
                    array_push($dd, '<a href="#" class="blue" onclick="view_detail(' . $priceBookDetail->id . ')" data-toggle="tooltip" data-placement="top" title="View Price Book"><i class="fa fa-eye"></i></a><input type="hidden" id="value_' . $priceBookDetail->id . '" name="value_' . $priceBookDetail->id . '" value="' . $priceBookDetail->name . '">');
                } else {
                    array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-eye"></i></a>');
                }

                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    /**
     * Show the MRp add screen to the user.
     *
     * @return Response
     */
    public function editCustomPriceBookView($id)
    {

        $details = PriceBook::with([
            'category',
            'details',
            'details.product.mrp',
            'details.getProductCategory'
        ])->where('id', '=', $id)->first();
        $sbu = Sbu::all()->pluck('name', 'id');
        $sbu->prepend('Select a SBU', '');
        $sale_party = PriceBookCategory::all()->pluck('name', 'id');
        $sale_party->prepend('Select a Price Book Category', '');
        $category = ProductCategory::all()->pluck('category_name', 'id')->prepend('All', 0);
        return view('priceBook::custom.edit')->with(['sbu' => $sbu, 'sale_party' => $sale_party, 'category' => $category, 'details' => $details]);
    }

    /**
     * Return insert status to view
     *
     * @return Response
     */
    public function jsonListUsersCustom(PriceBookRequest $request)
    {
        if ($request->get('book_category')) {
            $user_id = $request->get('user_id');
            if ($request->get('book_category') == 1) {
                $users = Employee::where('employee_type_id', 4)->get();
                $select_deta = PriceBookCustom::where('price_book_id', '=', $user_id)->where('ended_at', '=', null)->select('user_id')->get();
            } else if ($request->get('book_category') == 2) {
                $users = Outlet::where('outlet_sale_type', 3)->select('id', 'outlet_name', '4ever_location_id as loc_id')->get();
                $select_deta = PriceBookCustom::where('price_book_id', '=', $user_id)->where('ended_at', '=', null)->select('user_id')->get();
            } else if ($request->get('book_category') == 3) {
                $users = Outlet::where('outlet_sale_type', 1)->select('id', 'outlet_name', '4ever_location_id as loc_id')->get();
                $select_deta = PriceBookCustom::where('price_book_id', '=', $user_id)->where('ended_at', '=', null)->select('user_id')->get();
            } else if ($request->get('book_category') == 4) {
                $users = Outlet::where('outlet_sale_type', 2)->select('id', 'outlet_name', '4ever_location_id as loc_id')->get();
                $select_deta = PriceBookCustom::where('price_book_id', '=', $user_id)->where('ended_at', '=', null)->select('user_id')->get();
            }


            return Response::json(['users' => $users, 'selected' => $select_deta]);
        } else {
            return Response::json([]);
        }
    }


    /**
     * Show the MRp add screen to the user.
     *
     * @return Response
     */
    public function editCustomPriceBook(Request $request)
    {

        try {
            DB::transaction(function () use ($request) {

                if ($request->get('basic')) {
                    $price_book = PriceBook::find($request->get('price_book_id'));
                    $price_book->category = $request->get('basic')[0]['price_book_category'];
                    $price_book->name = $request->get('basic')[0]['price_book_name'];
                    $price_book->type = $request->get('basic')[0]['price_book_type'];
                    $price_book->timestamp = date("Y-m-d H:i:s");
                    $price_book->updated_at = date("Y-m-d H:i:s");
                    $price_book->save();

                    $data = PriceBookCustom::where('price_book_id', '=', $request->get('price_book_id'))->get();
                    foreach ($data as $val) {
                        if (in_array($val['user_id'], $request->get('users'))) {

                        } else {

                            $update = PriceBookCustom::find($val['id']);
                            $update->ended_at = date("Y-m-d H:i:s");
                            $update->save();
                        }
                    }
                    if (!$request->get('detail')) {
                        throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                    } else {

                        foreach ($request->get('detail') as $key => $value) {

                            if ($value['detail_id'] != 0) {
                                $PriceBookDetail = PriceBookDetail::find($value['detail_id']);
                                $PriceBookDetail->ended_at = date("Y-m-d H:i:s");
                                $PriceBookDetail->save();
                            }

                            $price_book_detail = PriceBookDetail::create([
                                'price_book_id' => $request->get('price_book_id'),
                                'category_id' => $value['category_id'],
                                'product_id' => $value['pro_id'],
                                'price' => $value['price'],
                                'effective_date' => $value['effective_date'],
                            ]);
                            if (!$price_book_detail) {
                                throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                            }
                        }
                    }
                    if (!empty($request->get('users'))) {
                        foreach ($request->get('users') as $value) {

                            $tmpd = PriceBookCustom::where('user_id', '=', $value)->get();

                            foreach ($tmpd as $old_price) {
                                $old_price->ended_at = date('Y-m-d H:i:s');
                                $old_price->save();
                            }


                            $price_book_user = PriceBookCustom::create([
                                'price_book_id' => $price_book->id,
                                'user_id' => $value,
                            ]);

                            if (!$price_book_user) {
                                throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                            }
                        }
                    }


                } else {
                    throw new TransactionException('No name', 101);
                }
            });
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json([0]);
            } else if ($e->getCode() == 101) {
                return Response::json([0]);
            }
        } catch (Exception $e) {
            return Response::json([0]);
        }

        return Response::json([1]);
    }

    /**
     * Show the MRp add screen to the user.
     *
     * @return Response
     */
    public function addMrpView()
    {
        return view('priceBook::mrp.add');
    }

    /**
     * Get the Product List Details for MRP.
     *
     * @return Response
     */
    public function jsonListMrpProductDetail()
    {
        // $mrp=Mrp::whereNull('ended_at')->get();

        // if(count($mrp)==0){

        // 	$product=Product::leftJoin('4ever_mrp',function($query){
        // 		$query->on('4ever_product.id','=','4ever_mrp.product_id');
        // 	})->leftJoin('4ever_product_category',function($query){
        // 		$query->on('4ever_product.product_category_id','=','4ever_product_category.id');
        // 	})->whereNull('4ever_mrp.ended_at')->select('4ever_product.id','4ever_product.short_code','4ever_product.product_name','4ever_product.description','4ever_product_category.category_name','mrp')->get();

        // }else{

        // 	return $product=Product::leftJoin('4ever_mrp',function($query){
        // 		$query->on('4ever_product.id','=','4ever_mrp.product_id');
        // 	})->leftJoin('4ever_product_category',function($query){
        // 		$query->on('4ever_product.product_category_id','=','4ever_product_category.id');
        // 	})->whereNull('4ever_mrp.ended_at')->select('4ever_product.id','4ever_product.short_code','4ever_product.product_name','4ever_product.description','4ever_product_category.category_name','mrp')->get();

        // }

        $product = Product::with(['category', 'mrp', 'sizes'])->where('status', 1)->get();

        return Response::json($product);
    }

    /**
     * Return insert status to view
     *
     * @return Response
     */
    public function addMrpBook(PriceBookRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {

                $dd = Mrp::all();

                foreach ($dd as $value) {
                    $value->ended_at = date('Y-m-d h:m:s');
                    $value->save();
                }

                foreach ($request->get('detail') as $value) {

                    if ($value['price'] != "") {
                        $mrp = Mrp::create([
                            'product_id' => $value['pro_id'],
                            'mrp' => $value['price'],
                        ]);

                        if (!$mrp) {
                            throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                        }
                    } else {
                        $mrp = Mrp::create([
                            'product_id' => $value['pro_id'],
                            'mrp' => 0.00,
                        ]);

                        if (!$mrp) {
                            throw new TransactionException('Something wrong.Record wasn\'t updated', 100);
                        }
                    }

                }

            });
            return Response::json([1]);
        } catch (TransactionException $e) {
            if ($e->getCode() == 100) {
                return Response::json([0]);
            }
        } catch (Exception $e) {
            return Response::json([0]);
        }

    }

    public function checkMrp(Request $request)
    {
        $data = [];
        $no_mrp = [];
        foreach ($request->get('detail') as $value) {

            $pro_det = Product::where('short_code', $value['Product_code'])->first();
            if ($pro_det) {
                $mp = Mrp::where('product_id', '=', $pro_det->id)->whereNull('ended_at')->first();

                if ($mp && $mp->mrp != "0.00") {
                    array_push($data, ['category_id' => $pro_det->product_category_id, 'product_id' => $pro_det->id, 'mrp' => $mp->mrp, 'price' => $value['Price'], 'price' => $value['Price']]);
                }
            } else {
                $no_mrp[] = $value['Product_code'];
            }
        }

        return Response::json(['data' => $data, 'no_mrp' => $no_mrp]);

    }

    /**
     * Show the MRp List screen to the user.
     *
     * @return Response
     */
    public function listMrpView()
    {
        return view('priceBook::mrp.list');
    }

    public function getExcel()
    {
        return view('priceBook::excel.add');
    }

    public function getTemplate()
    {
        $products = Product::where('status', 1)->get();
        if (sizeof($products) > 0) {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/pricebook.xls'), function ($excel) use ($products) {
                $tbl_column = 2;
                $index = 1;
                Log::info($products);

                foreach ($products as $detail) {
                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $detail->id);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $detail->short_code);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $detail->product_name);
                    $tbl_column++;
                    $index++;
                }

            })->setFileName($fileName)->store('xls', storage_path('xls/download'))->download();
        } else {
            return redirect('price/mrp/excel')->with(['error' => true,
                'error.message' => 'Same thing went wrong...!',
                'error.title' => 'Failed..!']);
        }
    }

    public function addExcel(ExcelRequest $request)
    {

        //store in server
        $file = $request->file('fileToUpload');
        if ($file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'xls') {
                return response()->json(array('type' => 1, 'msg' => 'Invalid file type...'));
            }

            $destinationPath = storage_path('xls/pricebook/');
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s');//
            $fileName = $date . '_sheet.' . $extension;
            $file->move($destinationPath, $fileName);
        }
        $rowCount = 0;

        Log::info($destinationPath . $fileName);
        //reade file and save in db
        try {
            Excel::filter('chunk')->load($destinationPath . $fileName)->chunk(1000, function ($results) {
                $now = new \DateTime('NOW');
                $date = $now->format('Y-m-d H:i:s');//
                foreach ($results as $row) {
                    DB::transaction(function () use ($row, $date) {
                        if ($row->mrp > 0) {
                            $mrp = Mrp::where('product_id', $row->no)->whereNull('ended_at')->update(['ended_at' => $date]);
                            Mrp::create([
                                'product_id' => $row->no,
                                'mrp' => $row->mrp
                            ]);
                        }
                    });
                }
            });

            return response()->json(array('type' => 0, 'msg' => 'done'));
        } catch (InvalidExcelException $e) {
            return response()->json(array('type' => 1, 'msg' => $e->getMessage()));
        }
    }

}
