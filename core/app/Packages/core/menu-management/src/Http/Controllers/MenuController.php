<?php
namespace Core\MenuManage\Http\Controllers;

use Core\MenuManage\Models\Menu;
use Core\Permissions\Models\Permission;
use App\Models\Font;
use App\Http\Controllers\Controller;
use Core\MenuManage\Http\Requests\MenuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class MenuController extends Controller {

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
		$menus       = Menu::all()->pluck( 'label' , 'id' );
		$permissions = Permission::all()->pluck( 'name' , 'name' );
		$permissions->pull( 'admin' );
		$permissions->pull( 'index' );
		$fonts = Font::where('type','=','fa')->get()->pluck('icon_list','icon_name');
		$fonts->prepend('No Icon','');
		return view( 'menuManage::menu.add' )->with([ 'menus' => $menus,
			'permissionArr' => $permissions,
			'fonts' => $fonts ]);
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function add(MenuRequest $request)
	{
		$permissions   = $request->get( 'permissions' );
		$permissions[] = "admin";

		$parent    = Menu::find($request->get('parent_menu' ));
		$sortAfter = Menu::find($request->get('menu_order' ));

		$menu = Menu::create([
			'label'			=> $request->get( 'label' ),
			'link'			=> $request->get( 'menu_url' ),
			'icon'			=> $request->get( 'menu_icon' ),
			'permissions'	=> json_encode( $permissions )
		]);

		$menu->makeChildOf($parent);
		$menu->moveToRightOf($sortAfter);

        Menu::rebuild();

		return redirect( 'menu/add' )->with([ 'success' => true,
			'success.message'=> 'Menu added successfully!',
			'success.title' => 'Well Done!' ]);
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		return view( 'menuManage::menu.list' );
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		if($request->ajax()){
			$data = Menu::with(['parentMenu'])->first()->getDescendants();
			$user = Sentinel::getUser();
			$jsonList = array();
			$i=1;
			foreach ($data as $key => $menu) {
				$dd = array();
				array_push($dd, $i);
				$label = '';
				for($j=1; $j < $menu->depth; $j++) {
					$label .= '--';
				}

				$label .= $menu->label;
				array_push($dd, $label);
				array_push($dd, $menu->link);

				if($menu->icon != ""){
					array_push($dd, "<i class=\"".$menu->icon."\" style=\"font-size:16px;\"></i>");
				}else{
					array_push($dd, "-");
				}

				if($menu->parent != 0){
					array_push($dd, $menu->parentMenu->label);
				}else{
					array_push($dd, "Root Menu");
				}

				array_push($dd, $menu->permissions);

				if($menu->status == 1){
					array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="menu-activate" type="checkbox" checked value="'.$menu->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
				}else{
					array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="menu-activate" type="checkbox" value="'.$menu->id.'"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
				}

				$permissions = Permission::whereIn('name',['menu.edit','admin'])->where('status','=',1)->pluck('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('menu/edit/'.$menu->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit Menu" style="background: #3F51B5;padding: 5px;border-radius: 2px;"><i class="fa fa-pencil"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled" style="background: #3F51B5;padding: 5px;border-radius: 2px;"><i class="fa fa-pencil"></i></a>');
				}

				$permissions = Permission::whereIn('name',['menu.delete','admin'])->where('status','=',1)->pluck('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="red menu-delete" data-id="'.$menu->id.'" data-toggle="tooltip" data-placement="top" title="Delete Menu" style="background: #CC1A6C;padding: 5px;border-radius: 2px;"><i class="fa fa-trash-o"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled" style="background: #CC1A6C;padding: 5px;border-radius: 2px;"><i class="fa fa-trash-o"></i></a>');
				}

				array_push($jsonList, $dd);
				$i++;
			}
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

			$menu = Menu::find($id);
			if($menu){
				$menu->status = $status;
				$menu->save();
				return response()->json(['status' => 'success']);
			}else{
				return response()->json(['status' => 'invalid_id']);
			}
		}else{
			return response()->json(['status' => 'not_ajax']);
		}
	}

	/**
	 * Delete a Menu
	 * @param  Request $request menu id
	 * @return Json           	json object with status of success or failure
	 */
	public function delete(Request $request)
	{
		if($request->ajax()){
			$id = $request->input('id');

			$menu = Menu::find($id);
			if($menu){
				$menu->delete();
				Menu::rebuild();
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
		$menus = Menu::all()->pluck( 'label' , 'id' );
		$permissions = Permission::all()->pluck( 'name' , 'name' );
		$permissions->pull( 'admin' );
		$permissions->pull( 'index' );

		$fonts = Font::where('type','=','fa')->get()->pluck('icon_list','icon_name');
		$fonts->prepend('No Icon','');

		$curMenu = Menu::find($id);

		//return $curMenu->permissions;
		//return json_decode($curMenu->permissions,true);

		$menuSort = $curMenu->getSiblingsAndSelf()->pluck('label','id');

		if($curMenu){
			return view( 'menuManage::menu.edit' )->with([ 'menus' => $menus,
				'permissionArr' => $permissions,
				'curMenu' => $curMenu,
				'menuSort' => $menuSort,
				'fonts' => $fonts ]);
		}else{
			return view( 'errors.404' )->with([ 'menus' => $menus,
				'permissionArr' => $permissions ]);
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(MenuRequest $request, $id)
	{
		$permissions = $request->get( 'permissions' );
		$permissions[] = "admin";

		$parent = Menu::find( $request->get( 'parent_menu' ) );
		$sortAfter = Menu::find( $request->get( 'menu_order' ) );

		$menu = Menu::find($id);

		$menu->label		= $request->get( 'label' );
		$menu->link			= $request->get( 'menu_url' );
		$menu->icon			= $request->get( 'menu_icon' );
		$menu->permissions	= json_encode( $permissions );

		$menu->save();

		$menu->makeChildOf($parent);

		if($menu->id != $sortAfter->id)
			$menu->moveToRightOf($sortAfter);

        Menu::rebuild();

		return redirect( 'menu/list' )->with([ 'success' => true,
			'success.message'=> 'Menu updated successfully!',
			'success.title' => 'Good Job!' ]);
	}
}
