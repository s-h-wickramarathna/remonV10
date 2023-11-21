<?php
namespace Core\UserRoles\Http\Controllers;

use Core\Permissions\Models\Permission;
use Core\UserRoles\Models\UserRole;

use App\Http\Controllers\Controller;
use Core\UserRoles\Http\Requests\UserRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| User Role Controller
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
	 * Show the user role add screen to the user.
	 *
	 * @return Response
	 */
	public function addView()
	{
		$permissions = Permission::all()->pluck( 'name' , 'name' );
		$permissions->pull( 'admin' );
		$permissions->pull( 'index' );
		return view( 'userRoles::role.add' )->with([ 'permissionArr' => $permissions ]);
	}

	/**
	 * Add new role data to database
	 *
	 * @return Redirect to role add
	 */
	public function add(UserRoleRequest $request)
	{
		$user = Sentinel::getUser();

		$permissions = explode(',', $request->get('permissions'));

		$permission_exists = Permission::whereIn('name', $permissions) //Get current exists permissions list
								->get(['name'])
								->pluck('name')
								->toArray(); 

		$permission_notexists = array_diff($permissions, $permission_exists); //Get non exists permissions

		$to_be_inserted = array(); //Non exists permission needs to be instered

		if (count($permission_notexists) > 0) {
			foreach ($permission_notexists as $key => $permission) {
				$arra = [
					'name'       => $permission,
					'created_by' => $user->id,
					'status'     => 1,
					'created_at' => Carbon::now()->toDateTimeString(),
					'updated_at' => Carbon::now()->toDateTimeString()
				];

				array_push($to_be_inserted, $arra);
			}
		}

		$status = false;

		DB::transaction(function () use ($to_be_inserted, $request, $permissions, $user, &$status) {
			
			if (count($to_be_inserted) > 0) {
				$inserted = Permission::insert($to_be_inserted); //Insert non exists permissions to the database
			}


			//$permissions[] = "admin";

			Sentinel::getRoleRepository()->setModel('Core\UserRoles\Models\UserRole');

			$role = Sentinel::getRoleRepository()->createModel()->create([
			    'name' => $request->get('name'),
			    'slug' => str_slug($request->get('name')),
			    'created_by' => $user->id,
			]);

			if (isset($inserted) && $inserted == 1) {
				if ($role) {
					$perm = array();
					foreach ($permissions as $key => $value) {
						$perm[$value] = true;
					}

					$role->permissions = json_encode($perm);
				}
			} elseif (!isset($inserted)) {
				if ($role) {
					$perm = array();
					foreach ($permissions as $key => $value) {
						$perm[$value] = true;
					}

					$role->permissions = json_encode($perm);
				}
			}

			$role->save();
			$status = true;
		});

		if($status){
			return redirect( 'user/role/add' )->with([ 'success' => true,
				'success.message'=> 'Role added successfully!',
				'success.title' => 'Well Done!' ]);
		}
	}

	/**
	 * Show the roles list screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		return view( 'userRoles::role.list' );
	}

	/**
	 * Return roles json list to view
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		if($request->ajax()){
			$data = UserRole::all();
			$user = Sentinel::getUser();
			$jsonList = array();
			$i=1;
			foreach ($data as $key => $role) {
				$dd = array();
				array_push($dd, $i);

				array_push($dd, $role->name);
				array_push($dd, $role->slug);

				$json = array_keys(json_decode($role->permissions,true));

				array_push($dd, str_replace(',', ', ', json_encode($json)));

				$permissions = Permission::whereIn('name',['user.role.edit','admin'])->where('status','=',1)->pluck('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('user/role/edit/'.$role->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit Role"><i class="fa fa-pencil"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
				}

				$permissions = Permission::whereIn('name',['user.role.delete','admin'])->where('status','=',1)->pluck('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="red role-delete" data-id="'.$role->id.'" data-toggle="tooltip" data-placement="top" title="Delete Role"><i class="fa fa-trash-o"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled"><i class="fa fa-trash-o"></i></a>');
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
	 * Delete a User Role
	 * @param  Request $request role id
	 * @return Json           	json object with status of success or failure
	 */
	public function delete(Request $request)
	{
		if($request->ajax()){
			$id = $request->input('id');

			$role = UserRole::find($id);
			if($role){
				$role->delete();
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
		$permissions = Permission::all()->pluck( 'name' , 'name' );
		$permissions->pull( 'admin' );
		$permissions->pull( 'index' );

		$role = UserRole::find($id);

		if($role){
			return view('userRoles::role.edit')->with([
				'permissionArr' => $permissions,
				'role'          => $role
			]);
		}else{
			return view('errors.404');
		}
	}

	/**
	 * edit role data to database
	 *
	 * @return Redirect to roles list
	 */
	public function edit(UserRoleRequest $request, $id)
	{
		$user = Sentinel::getUser();

		$permissions = explode(',', $request->get('permissions'));

		$permission_exists = Permission::whereIn('name', $permissions) //Get current exists permissions list
								->get(['name'])
								->pluck('name')
								->toArray(); 

		$permission_notexists = array_diff($permissions, $permission_exists); //Get non exists permissions

		$to_be_inserted = array(); //Non exists permission needs to be instered

		if (count($permission_notexists) > 0) {
			foreach ($permission_notexists as $key => $permission) {
				$arra = [
					'name'       => $permission,
					'created_by' => $user->id,
					'status'     => 1,
					'created_at' => Carbon::now()->toDateTimeString(),
					'updated_at' => Carbon::now()->toDateTimeString()
				];

				array_push($to_be_inserted, $arra);
			}
		}

		$status = false;

		DB::transaction(function () use ($to_be_inserted, $request, $permissions, $user, $id, &$status) {
			
			if (count($to_be_inserted) > 0) {
				$inserted = Permission::insert($to_be_inserted); //Insert non exists permissions to the database
			}


			//$permissions[] = "admin";

			Sentinel::getRoleRepository()->setModel('Core\UserRoles\Models\UserRole');

			$role = Sentinel::findRoleById($id);
			$role->name = $request->get('name');
			$role->slug = str_slug($request->get('name'));
			$role->created_by = $user->id;

			/*$role = Sentinel::getRoleRepository()->createModel()->create([
			    'name' => $request->get('name'),
			    'slug' => str_slug($request->get('name')),
			    'created_by' => $user->id,
			]);*/

			if (isset($inserted) && $inserted == 1) {
				if ($role) {
					$perm = array();
					foreach ($permissions as $key => $value) {
						$perm[$value] = true;
					}

					$role->permissions = json_encode($perm);
				}
			} elseif (!isset($inserted)) {
				if ($role) {
					$perm = array();
					foreach ($permissions as $key => $value) {
						$perm[$value] = true;
					}

					$role->permissions = json_encode($perm);
				}
			}

			$role->save();
			$status = true;
		});

		if($status){
			return redirect( 'user/role/list' )->with([ 'success' => true,
				'success.message'=> 'Role updated successfully!',
				'success.title' => 'Well Done!' ]);
		}
	}
}
