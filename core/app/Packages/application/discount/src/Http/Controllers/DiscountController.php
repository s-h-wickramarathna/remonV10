<?php

namespace Application\Discount\Http\Controllers;

use Application\Discount\Models\DiscountGroup;
use Application\Discount\Models\DiscountGroupDetails;
use Application\Discount\Models\DiscountOutletGroup;
use Application\Discount\Models\DiscountRule;
use Application\Discount\Models\DiscountRuleDetail;
use Application\Discount\Models\DiscountRuleType;
use Application\Product\Models\Product;
use Application\ProductCategory\Models\ProductCategory;
use Application\Discount\Http\Requests\DiscountRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Application\LocationManage\Models\LocationType;
use Application\LocationManage\Models\Location;

use Application\LocationManage\Models\Outlet;
use App\Exceptions\TransactionException;


class DiscountController extends Controller
{

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
    public function addGroupView()
    {
        $product_list = DB::select(
            "SELECT
                p.id, CONCAT(p.short_code, ' - ', p.product_name) AS name
            FROM
                4ever_product p
            WHERE
                p.status = 1"
        );

        $product_list = json_decode(json_encode(array_pluck($product_list, 'name', 'id')), true);

        $product = DB::select(
            "SELECT
                id, CONCAT(short_code, ' - ', product_name) AS name
            FROM
                4ever_product
            WHERE
                status = 1"
        );
        $product = json_decode(json_encode(array_pluck($product, 'name', 'id')), true);

        $productCategory = ProductCategory::where('status', 1)->get()->pluck('category_name', 'id');
        $productCategory->prepend('All Category', 0);
        $rule = DiscountRule::where('status', 1)->get()->pluck('name', 'id');
        $rule->prepend('Select Rule', 0);
        return view('discountViews::discount.add')->with(['rule' => $rule, 'product' => $product, 'product_list' => $product_list, 'product_category' => $productCategory]);
    }

    /**
     * Add new menu data to database
     *
     * @return Redirect to menu add
     */
    public function addGroup(DiscountRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $ruleId = $request->rule;
                $groupName = $request->group_name;
                $ruleType = $request->rule_type;
                $discount_rule_type = $request->dis_rule_type;

                $dataDiscountGroup = DiscountGroup::create([
                    'name' => $groupName,
                    'rule_id' => $ruleId,
                    'status' => 1
                ]);
//                if ($discount_rule_type != 'invoice total' || $discount_rule_type != 'invoice total') {
                if ($dataDiscountGroup) {

                    $last_id = $dataDiscountGroup['id'];

                    foreach ($request->get('pr_name_1') as $key => $detail) {
                        $discountGroupDetails = DiscountGroupDetails::create([
                            'group_id' => $last_id,
                            'product_id' => $detail
                        ]);
                        if (!$discountGroupDetails) {
                            throw new TransactionException('Something wrong.details didn\'t insert');
                        }
                    }
                } else {
                    throw new TransactionException('Something wrong.Header didn\'t insert');
                }
//                }
            });
        } catch (TransactionException $e) {
            return redirect()->back()->with(['error' => true,
                'error.message' => 'Discount Group didn not insert!',
                'error.title' => 'Check !']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => true,
                'error.message' => 'Something wrong.',
                'error.title' => 'Check !']);
        }


        return redirect('discount/group/add ')->with(['success' => true,
            'success.message' => 'Discount Group Added Successfully!',
            'success.title' => 'Well Done!']);
    }

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function listView()
    {
        return view('discountGroupViews::discountGroup.list');
    }

    /**
     * Show the menu add screen to the user.
     *
     * @return Response
     */
    public function jsonList(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select("SELECT
										dg.id,
										dg.name as disgroup,
										dr.name as rule,
										dg.status

									FROM
										wp_discount_group dg
											INNER JOIN
									   wp_discount_rule dr ON dg.rule_id = dr.id
									   group by dg.id
									   order by dg.id

									   ");
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $group) {
                $dd = array();
                array_push($dd, $group->id);
                array_push($dd, $group->disgroup);
                array_push($dd, $group->rule);

                if ($group->status == 1) {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="' . $group->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                } else {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"></span></label>');
                }
                array_push($dd, '<a class="blue btn-grnDetail" data-group="' . $group->id . '"  data-toggle="tooltip" data-placement="top" title="Details" style="text-align:center;text-align:center;background: #FFA85D;padding: 5px;border-radius: 2px;"><i  class="fa fa-eye"></i></a>');
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
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

            $product = discountGroup::find($id);
            if ($product) {
                $product->status = $status;
                $product->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    public function jsonListDetail(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select(
                "SELECT
						CONCAT(p.product_name, short_code) AS product
					FROM
						wp_discount_group dg
							INNER JOIN
						wp_discount_group_detail gd ON dg.id = gd.group_id
							INNER JOIN
						wp_product p ON p.id = gd.product_id
					WHERE
						dg.id = '$request->group_id'
					GROUP BY gd.id
					ORDER BY gd.id
								");
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $Detail) {
                $dd = array();
                array_push($dd, $Detail->product);
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function getProducts(Request $request)
    {
        $catId = $request->catId;
        if ($catId != 0) {
//            $product = Product::where('product_category_id', $catId)->get();
            $product = DB::select(
                "SELECT
                    p.id, CONCAT(p.short_code, ' - ', p.product_name) AS name
                FROM
                    4ever_product p
                WHERE
                    p.status = 1
                       AND product_category_id = $catId"
            );
        } else {
//            $product = Product::all();
            $product = DB::select(
                "SELECT
                    p.id, CONCAT(p.short_code, ' - ', p.product_name) AS name
                FROM
                    4ever_product p
                WHERE
                    p.status = 1"
            );
        }
        return Response::json(array('data' => $product));
    }

    public function addRuleView()
    {
        return view('discountViews::discount.addRule');
    }

    public function addRule(Request $request)
    {
        $this->validate($request, [
            'rule_name' => 'required|unique:4ever_discount_rule,name'
        ]);
        
        try {
            DB::transaction(function () use ($request) {
                $rule_name = $request->rule_name;
                $max = $request->max;
                $full_raw_count = $request->main_raw_count;
                $rule_type = $request->rule_type;

                $discount_rule = DiscountRule::create([
                    'name' => $rule_name,
                    'max' => $max,
                    'discount_rule_type_id' => $rule_type
                ]);
                if ($discount_rule) {
                    $last_id = $discount_rule['id'];

                    for ($i = 1; $i <= $full_raw_count; $i++) {
                        $in_qty = $request['in_qty_' . $i];
                        $out_qty = $request['out_qty_' . $i];
                        if ((isset($in_qty) && isset($out_qty)) && ((!empty($in_qty)) && (!empty($out_qty)))) {
                            $discountRuleDetail = DiscountRuleDetail::create([
                                'discount_rule_id' => $last_id,
                                'value' => $in_qty,
                                'discount' => $out_qty
                            ]);
                            if (!$discountRuleDetail) {
                                throw new TransactionException('Something wrong.Details didn\'t insert');
                            }
                        }
                    }
                } else {
                    throw new TransactionException('Something wrong.Header didn\'t insert');
                }
            });
        } catch (TransactionException $e) {
            return redirect()->back()->with(['error' => true,
                'error.message' => 'Discount Rule didn not insert!',
                'error.title' => 'Check it again!']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => true,
                'error.message' => 'Something wrong.',
                'error.title' => 'Check it again!']);
        }
        return redirect('discount/rule/add ')->with(['success' => true,
            'success.message' => 'Discount Rule Added Successfully!',
            'success.title' => 'Well Done!']);
    }

    public function addGroupOutletView()
    {
        $locations = LocationType::with(['locations'])->where('id', '!=', 1)->get();
        $locationArray = [];
        foreach ($locations as $location) {
            $locationArray[$location->type] = $location->locations()->pluck('name', 'id')->prepend('All ' . $location->type, '0');
        }

        $mainLocationdata = DB::select(
            "SELECT
                id, name
            FROM
                4ever_location lo
            WHERE
                lo.location_type_id = 6 AND status = 1"
        );

        $mainLocationdata = json_decode(json_encode(array_pluck($mainLocationdata, 'name', 'id')), true);

        $discountGroups = DB::select(
            "SELECT
                id, name
            FROM
                4ever_discount_group
            WHERE
                status = 1"
        );

        $discountGroups = json_decode(json_encode(array_pluck($discountGroups, 'name', 'id')), true);

        return view('discountViews::discount.addGroupOutlet')->with(['location_category' => $locationArray, 'mainLocation' => $mainLocationdata, 'discountGroups' => [0 => 'Select a Discount Group'] + $discountGroups]);
    }

    public function getLocation(Request $request)
    {
        $locType = $request->locType;
        $location = $request->location;
        $prevLocationType = $request->prevLocationType;
        $prevLocationId = $request->prevLocationId;
        $group = $request->group;

        $dGroup = DiscountGroup::with(['rule','groupdetail'])->find($group);

        $inGroupDetail = $dGroup->groupdetail->pluck('product_id');
        if ($location > 0) {
            $locOutlets = Location::where('location_type_id', '=', function ($query) use ($locType) {
                $query->select('id')->from('4ever_location_type')->where('type', '=', $locType)->first();
            })->where('id', '=', $location)->first()->leaves()->where('location_type_id', '=', function ($query) {
                $query->select('id')->from('4ever_location_type')->where('type', 'outlet');
            })->whereNotIn('id', function ($query) use ($inGroupDetail, $dGroup) {
                $query->select('outlet_id')->from('4ever_discount_group_outlet')->whereIn('discount_group_id', function ($query2) use ($inGroupDetail) {
                    $query2->select('group_id')->from('4ever_discount_group_detail')->whereIn('product_id', $inGroupDetail);
                })->whereIn('discount_group_id', function($query2) use ($inGroupDetail, $dGroup){
                    $query2->select('id')->from('4ever_discount_group')->whereIn('rule_id', function($query3) use ($inGroupDetail,$dGroup ){
                        $query3->select('id')->from('4ever_discount_rule')->where('discount_rule_type_id','=',$dGroup->rule->discount_rule_type_id);
                    });
                });
            })->get(['id', 'name']);
        } else {
            $locOutlets1 = Location::where('location_type_id', '=', function ($query) use ($locType) {
                $query->select('id')->from('4ever_location_type')->where('type', '=', $locType)->first();
            })->get();

            $locOutlets = '';

            foreach ($locOutlets1 as $loc) {
                $loc = $loc->leaves()->where('location_type_id', '=', function ($query) {
                    $query->select('id')->from('4ever_location_type')->where('type', 'outlet');
                })->whereNotIn('id', function ($query) use ($inGroupDetail, $dGroup) {
                    $query->select('outlet_id')->from('4ever_discount_group_outlet')->whereIn('discount_group_id', function ($query2) use ($inGroupDetail) {
                        $query2->select('group_id')->from('4ever_discount_group_detail')->whereIn('product_id', $inGroupDetail);
                    })->whereIn('discount_group_id', function($query2) use ($inGroupDetail, $dGroup){
                        $query2->select('id')->from('4ever_discount_group')->whereIn('rule_id', function($query3) use ($inGroupDetail,$dGroup ){
                            $query3->select('id')->from('4ever_discount_rule')->where('discount_rule_type_id','=',$dGroup->rule->discount_rule_type_id);
                        });
                    });
                })->get(['id', 'name']);

                if ($locOutlets != '') {
                    $locOutlets = array_merge($loc->toArray(), $locOutlets);
                    //$locOutlets->merge($loc);
                } else {
                    $locOutlets = $loc->toArray();
                }
            }
        }


        $lastType = DB::select(
            "SELECT
                `type`
            FROM
                4ever_location_type
            ORDER BY parent DESC
            LIMIT 1"
        );

        $lastType = $lastType[0]->type;
        if ($locType != $lastType) {
            if ($location != 0) {
                $sqlLoc = DB::select(
                    "SELECT
                    id, name
                FROM
                    4ever_location
                WHERE
                    parent = $location AND status = 1"
                );
            } else {
                $sqlLoc = DB::select(
                    "SELECT
                    id, name
                FROM
                    4ever_location
                WHERE
                    parent = (SELECT
                            id
                        FROM
                            4ever_location_type
                        WHERE
                            `type` = '$locType')
                        AND status = 1"
                );
            }
        } else {
            $sqlLoc = [];
        }
        return Response::json(array('data' => $sqlLoc, 'dataOutlet' => $locOutlets));
    }

    public function addDiscountGroupOutlet(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $group = $request->free_issue_group;

                foreach ($request->get('pr_name_1') as $key => $detail) {
                    $discountOutletGroup = DiscountOutletGroup::create([
                        'discount_group_id' => $group,
                        'outlet_id' => $detail
                    ]);
                    if (!$discountOutletGroup) {
                        throw new TransactionException('Something wrong.details didn\'t insert');
                    }
                }
            });
        } catch (TransactionException $e) {
            return redirect()->back()->with(['error' => true,
                'error.message' => 'Discount Group didn not assign!',
                'error.title' => 'Check it again!']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => true,
                'error.message' => 'Something wrong.',
                'error.title' => 'Check it again!']);
        }
        return redirect('discount/outletAssign/add ')->with(['success' => true,
            'success.message' => 'Discount Group Assigned Successfully!',
            'success.title' => 'Well Done!']);
    }

    public function listViewDiscountGroup()
    {
        return view('discountViews::discount.list');
    }

    public function jsonListGroupView(Request $request)
    {
        if ($request->ajax()) {
            $data1 = "";
            if ($request->status != 0) {
                if ($request->status == 1) {
                    $data1 = " WHERE dg.status = 1";
                } else {
                    $data1 = " WHERE dg.status = 0";
                }
            }

            $data = DB::select(
                "SELECT
                    dg.id,
                    dg.name AS group_name,
                    dr.name AS rule_name,
                    dr.id AS rule_id,
                    dg.status
                FROM
                    4ever_discount_group dg
                        INNER JOIN
                    4ever_discount_rule dr ON dg.rule_id = dr.id
                    $data1
                GROUP BY dg.id"
            );
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $group) {
                $dd = array();
                array_push($dd, $group->group_name);
                array_push($dd, $group->rule_name);
                array_push($dd, '<a class="blue btn-groupDetail" data-group="' . $group->id . '"  data-toggle="tooltip" data-placement="top" title="View Group Detail" style="text-align:center;text-align:center;background: #FFA85D;padding: 5px;border-radius: 2px;"><i  class="fa fa-eye"></i></a>');
                array_push($dd, '<a class="blue btn-ruleDetail" data-rule="' . $group->rule_id . '"  data-toggle="tooltip" data-placement="top" title="View Rule Detail" style="text-align:center;text-align:center;background: #FFA85D;padding: 5px;border-radius: 2px;"><i  class="fa fa-eye"></i></a>');

                if ($group->status == 1) {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" checked value="' . $group->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label> <span class="visible-print hide">Activated</span>');
                } else {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="' . $group->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>  <span class="visible-print hide">Dectivated</span>');
                }
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function jsonListGroupDetailView(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select(
                "SELECT
                    CONCAT(p.short_code, ' - ', p.product_name) AS product
                FROM
                    4ever_discount_group_detail dgd
                        INNER JOIN
                    4ever_product p ON p.id = dgd.product_id
                WHERE
                    group_id = $request->group_id AND dgd.status = 1"
            );
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $groupDetail) {
                $dd = array();
                array_push($dd, $groupDetail->product);
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function jsonListRuleDetailView(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select(
                "SELECT
                    value, discount
                FROM
                    4ever_discount_rule_detail
                WHERE
                    status = 1 AND discount_rule_id = $request->rule_id"
            );
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $ruleDetail) {
                $dd = array();
                array_push($dd, $ruleDetail->value);
                array_push($dd, $ruleDetail->discount);
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function statusGroup(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $group = DiscountGroup::find($id);
            if ($group) {
                $group->status = $status;
                $group->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    public function listViewDiscountRule()
    {
        return view('discountViews::discount.listRule');
    }

    public function jsonListRuleView(Request $request)
    {
        if ($request->ajax()) {
            $data1 = "";
            if ($request->status != 0) {
                if ($request->status == 1) {
                    $data1 = " WHERE dr.status = 1";
                } else {
                    $data1 = " WHERE dr.status = 0";
                }
            }

            $data = DB::select(
                "SELECT
                    dr.id,
                    drt.name AS discount_rule_type,
                    dr.name AS rule,
                    dr.max,
                    dr.status
                FROM
                    4ever_discount_rule dr
                        INNER JOIN
                    4ever_discount_rule_type drt ON dr.discount_rule_type_id = drt.id$data1"
            );
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $rule) {
                $dd = array();
                array_push($dd, $rule->rule);
                array_push($dd, $rule->discount_rule_type);
                array_push($dd, $rule->max);
                array_push($dd, '<a class="blue btn-ruleDetail" data-rule="' . $rule->id . '"  data-toggle="tooltip" data-placement="top" title="View Rule Detail" style="text-align:center;text-align:center;background: #FFA85D;padding: 5px;border-radius: 2px;"><i  class="fa fa-eye"></i></a>');

                if ($rule->status == 1) {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" checked value="' . $rule->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label> <span class="visible-print hide">Activated</span>');
                } else {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="' . $rule->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>  <span class="visible-print hide">Dectivated</span>');
                }
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function statusRule(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $rule = DiscountRule::find($id);
            if ($rule) {
                $rule->status = $status;
                $rule->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    public function listGroupOutletView()
    {
        $locations = LocationType::with(['locations'])->where('id', '!=', 1)->get();
        $locationArray = [];
        foreach ($locations as $location) {
            $locationArray[$location->type] = $location->locations()->pluck('name', 'id')->prepend('All ' . $location->type, '0');
        }

        return view('discountViews::discount.listGroupOutlet')->with(['location_category' => $locationArray]);
    }

    public function listGroupOutlet(Request $request)
    {
        if ($request->ajax()) {
            $region = $request->region;
            $area = $request->area;
            $territoty = $request->territory;
            $route = $request->route;

            if ($region != 0 && $area == 0) {
                $data = Location::where('4ever_location.id', '=', $region)->first()->leaves()->where('location_type_id', '=', function ($query) {
                    $query->select('4ever_location_type.id')->from('4ever_location_type')->where('type', '=', 'outlet')->first();
                })->join('4ever_discount_group_outlet', '4ever_location.id', '=', '4ever_discount_group_outlet.outlet_id')->groupBy('4ever_location.id')->get(['4ever_location.id', 'name']);
            } elseif ($region != 0 && $area != 0 && $territoty == 0) {
                $data = Location::where('4ever_location.id', '=', $area)->first()->leaves()->where('location_type_id', '=', function ($query) {
                    $query->select('4ever_location_type.id')->from('4ever_location_type')->where('type', '=', 'outlet')->first();
                })->join('4ever_discount_group_outlet', '4ever_location.id', '=', '4ever_discount_group_outlet.outlet_id')->groupBy('4ever_location.id')->get(['4ever_location.id', 'name']);
            } elseif ($region != 0 && $area != 0 && $territoty != 0 && $route == 0) {
                $data = Location::where('4ever_location.id', '=', $territoty)->first()->leaves()->where('location_type_id', '=', function ($query) {
                    $query->select('4ever_location_type.id')->from('4ever_location_type')->where('type', '=', 'outlet')->first();
                })->join('4ever_discount_group_outlet', '4ever_location.id', '=', '4ever_discount_group_outlet.outlet_id')->groupBy('4ever_location.id')->get(['4ever_location.id', 'name']);
            } elseif ($region != 0 && $area != 0 && $territoty != 0 && $route != 0) {
                $data = Location::where('4ever_location.id', '=', $route)->first()->leaves()->where('location_type_id', '=', function ($query) {
                    $query->select('4ever_location_type.id')->from('4ever_location_type')->where('type', '=', 'outlet')->first();
                })->join('4ever_discount_group_outlet', '4ever_location.id', '=', '4ever_discount_group_outlet.outlet_id')->groupBy('4ever_location.id')->get(['4ever_location.id', 'name']);
            } elseif ($region == 0 && $area == 0 && $territoty == 0 && $route == 0) {
                $data = Location::where('location_type_id', '=', function ($query) {
                    $query->select('4ever_location_type.id')->from('4ever_location_type')->where('type', '=', 'outlet')->first();
                })->join('4ever_discount_group_outlet', '4ever_location.id', '=', '4ever_discount_group_outlet.outlet_id')->groupBy('4ever_location.id')->get(['4ever_location.id', 'name']);
            }

            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $outlet) {
                $dd = array();
                array_push($dd, $outlet->id);
                array_push($dd, $outlet->name);
                array_push($dd, '<a class="blue btn-groupDetail" data-outlet="' . $outlet->id . '"  data-toggle="tooltip" data-placement="top" title="View Rule Detail" style="text-align:center;text-align:center;background: #FFA85D;padding: 5px;border-radius: 2px;"><i  class="fa fa-eye"></i></a>');

                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function jsonListOutletGroupDetailView(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select(
                "SELECT
                    dgo.id, dg.name, dgo.status
                FROM
                    4ever_discount_group_outlet dgo
                        INNER JOIN
                    4ever_discount_group dg ON dg.id = dgo.discount_group_id
                WHERE
                    outlet_id = $request->outlet_id"
            );
            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $groupDetail) {
                $dd = array();
                array_push($dd, $groupDetail->name);
                if ($groupDetail->status == 1) {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" checked value="' . $groupDetail->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label> <span class="visible-print hide">Activated</span>');
                } else {
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="' . $groupDetail->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>  <span class="visible-print hide">Dectivated</span>');
                }
                array_push($jsonList, $dd);
                $i++;
            }
            return Response::json(array('data' => $jsonList));
        } else {
            return Response::json(array('data' => []));
        }
    }

    public function statusGroupOutlet(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $rule = DiscountOutletGroup::find($id);
            if ($rule) {
                $rule->status = $status;
                $rule->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    public function getRuleType(Request $request)
    {
        $rule = $request->rule;
        $ruleType = DB::select(
            "SELECT
                drt.name AS rule_type
            FROM
                4ever_discount_rule dr
                    INNER JOIN
                4ever_discount_rule_type drt ON drt.id = dr.discount_rule_type_id
            WHERE
                dr.id = $rule"
        );
        return Response::json(array('data' => $ruleType));
    }
}
