<?php
namespace Core\UserManage\Http\Controllers;

use App\Http\Controllers\Controller;
use Application\EmployeeManage\Models\Employee;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Core\Permissions\Models\Permission;
use Core\UserManage\Http\Requests\UserRequest;
use Core\UserManage\Models\RoleUsers;
use Core\UserManage\Models\User;
use Core\UserRoles\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UserController extends Controller {

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
		$admin = array('admin'=>true,"index"=>true);
		$user = array("index"=>true);
	}

	/**
	 * Show the user add screen to the admin.
	 *
	 * @return Response
	 */
	public function addView()
	{
		$parentList = Employee::select('employee.*')->Leftjoin('user as u','u.employee_id','=','employee.id')->whereNull('username')->get();
        $roles      = UserRole::all();
		return view('userManage::add')->with(['empList'=>$parentList,'roles'=>$roles]);
	}

	/**
	 * Add new user data to database
	 *
	 * @return Redirect to user add
	 */
	public function add(UserRequest $request)
	{
        $role = $request->get('role');
//		if(isset($type)){
//			$permissions = $request->get('permissions');
//			$permissionArr = array();
//			$permission = explode(',',$permissions);
//			for($i=0;$i<count($permission);$i++){
//				$index = $permission[$i];
//				$permissionArr[$index]=true;
//			}
//		}else{
//			$permissionArr = array('admin'=>true,"index"=>true);
//		}

		$user = Sentinel::register([
			'username'      => $request->get('uName'),
            'employee_id'   => $request->get('employee'),
			'password'      => $request->get('password'),
			'permissions'   => ['index'=>true],
			'last_login'    => date("Y-m-d H:i:s")
		]);

		$emp = Employee::where('employee_type_id',2)->where('id',$request->get('employee'))->first();
		if($emp){
            $user->token = md5($user->id);
            $user->save();
        }

        $roleusers = RoleUsers::create([
            'user_id'=>$user->id,
            'role_id'=>$role
        ]);

		$acUser = Activation::create($user);
		Activation::complete($user, $acUser->code);
		return redirect()->back()->with([ 'success' => true,
			'success.message'=> 'User added successfully!',
			'success.title' => 'Well Done!' ]);;
			
	}

	/**
	 * Show the user add screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		return view( 'userManage::list' );
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		//if($request->ajax()){
			$users = User::with('employee','customer')->where('id','!=',1)->get();
			$jsonList = array();
			$i=1;
			foreach ($users as $key => $user) {
				$dd = array();
				array_push($dd, $i);
                if(sizeof($user->employee) > 0){
                    array_push($dd, $user->employee[0]->full_name);

                }else{
                    array_push($dd, $user->customer->f_name.' '.$user->customer->l_name);
                }

                array_push($dd, $user->username);
                if(sizeof($user->employee) > 0) {
                    array_push($dd, $user->employee[0]->email);
                }else{
                    array_push($dd, $user->customer->email);
                }

                //$json = array_keys($user->permissions);
                if(sizeof($user->employee) > 0) {
                    array_push($dd, 'Employee');
                }else{
                    array_push($dd, 'Customer');
                }


				array_push($dd, $user->last_login);

                if(sizeof($user->employee) > 0) {
                    if ($user->employee[0]->parent != 0) {
                        if ($user->status == 1) {
                            array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Deactivate"><input class="user-activate" type="checkbox" checked value="' . $user->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                        } else {
                            array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Activate"><input class="user-activate" type="checkbox" value="' . $user->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                        }
                    } else {
                        array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Can\'t Change"><input class="#" type="checkbox" disabled checked value="' . $user->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                    }
                }else{
                    array_push($dd, '<label class="switch switch-sm" data-toggle="tooltip" data-placement="top" title="Can\'t Change"><input class="#" type="checkbox" disabled checked value="' . $user->id . '"><span style="position:inherit;"><i class="handle" style="position:inherit;"></i></span></label>');
                }

				$permissions = Permission::whereIn('name',['user.edit','admin'])->where('status','=',1)->pluck('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="edit/'.$user->id.'" class="disabled" onclick="" data-toggle="tooltip" data-placement="top" title="Edit User" style="background: #3F51B5;padding: 5px;border-radius: 2px;"><i class="fa fa-pencil"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled" ><i class="fa fa-pencil"></i></a>');
				}

				array_push($jsonList, $dd);
				$i++;
			}
			return Response::json(array('data'=>$jsonList));
		/*}else{
			return Response::json(array('data'=>[]));
		}*/
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

			Log::info($id);

			$user = User::find($id);

			Log::info($user);
			if($user){
				$user->status = $status;
				$user->save();
				$user = Sentinel::findById($id);
				if ($status != 0 && $user) {
					$acUser = Activation::create($user);
					Activation::complete($user, $acUser->code);
				} else {
					Activation::remove($user);
				}
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
		if($id == 1){
			return response()->view("errors.restricted");
		}
		$user = User::with('role')->find($id);
		$parentList = Employee::where('status', 1)->where('employee_type_id','!=', 5)->where('parent','!=', 0)->orderBy('first_name')->get()->pluck('full_name', 'id');
		$roles      = UserRole::all();
		$login_user = Sentinel::getUser();
		//return $login_user;
		return view('userManage::edit')->with(['empList'=>$parentList,'roles'=>$roles,'userC'=>$user, 'login_user' =>$login_user]);
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(Request $request)
	{
		$user = Sentinel::findUserById($request->id);
		$credentials = [];
		if($user){
			$credentials['employee_id'] = $request->emp;
			$credentials['username'] = $request->uName;
			if (strlen($request->get('oPassword')) > 0) { // FOR OTHER USERS
				if (strlen($request->get('cPassword')) > 0) {
					if ($request->get('password') == $request->get('cPassword')) {
						//return md5($request->get('oPassword'));
						if (Sentinel::validateCredentials($user, ['username' => $request->uName, 'password' => $request->get('oPassword')])) {
							$credentials['password'] = $request->get('cPassword');
						} else {
							return redirect()->back()->with(['warning' => true,
								'warning.message' => 'Old Password mismatch..!',
								'warning.title' => 'Warning..!'])->withInput();
						}
					} else {
						return redirect()->back()->with(['warning' => true,
							'warning.message' => 'New Password mismatch..!',
							'warning.title' => 'Warning..!'])->withInput();
					}

				}
			}else { // FOR ADMIN
				if ($request->get('password') == $request->get('cPassword')) {
					$credentials['password'] = $request->get('cPassword');
				} else {
					return redirect()->back()->with(['warning' => true,
						'warning.message' => 'New Password mismatch..!',
						'warning.title' => 'Warning..!'])->withInput();
				}
			}
			Sentinel::update($user, $credentials);
			/*$role = RoleUsers::where('user_id',$user->id)->first();
			$role->role_id = $request->get('role');
			$role->save();*/
            RoleUsers::where('user_id',$user->id)->delete();
            $role = Sentinel::findRoleById($request->get('role'));
            $role->users()->attach($user);

			return redirect( 'user/list' )->with([ 'success' => true,
				'success.message'=> 'User updated successfully!',
				'success.title' => 'Success!' ]);
		}
		return redirect( 'user/list' )->with([ 'error' => true,
			'error.message'=> 'User update failed!',
			'error.title' => 'Error!' ]);
	}

	public function changeLogin(){
		$user = Sentinel::getUser();
		//$user = User::with('role')->find($login_user->);
		$parentList = Employee::where('status', 1)->where('employee_type_id','!=', 5)->where('parent','!=', 0)->orderBy('first_name')->get()->pluck('full_name', 'id');
		$roles      = UserRole::all();
		//$login_user = Sentinel::getUser();
		//return $login_user;
		return view('userManage::edit')->with(['empList'=>$parentList,'roles'=>$roles,'userC'=>$user, 'login_user' =>$user]);
	}

	public function change(Request $request)
	{

		$user_obj = User::where('employee_id',$request->emp)->first();
		$user = Sentinel::findUserById($user_obj->id);
		$credentials = [];
		if($user){
			$credentials['username'] = $request->uName;
			if (strlen($request->get('oPassword')) > 0) { // FOR OTHER USERS
				if (strlen($request->get('cPassword')) > 0) {
					if ($request->get('password') == $request->get('cPassword')) {
						//return md5($request->get('oPassword'));
						if (Sentinel::validateCredentials($user, ['username' => $request->uName, 'password' => $request->get('oPassword')])) {
							$credentials['password'] = $request->get('cPassword');
						} else {
							return redirect()->back()->with(['warning' => true,
								'warning.message' => 'Old Password mismatch..!',
								'warning.title' => 'Warning..!'])->withInput();
						}
					} else {
						return redirect()->back()->with(['warning' => true,
							'warning.message' => 'New Password mismatch..!',
							'warning.title' => 'Warning..!'])->withInput();
					}

				}
			}
			Sentinel::update($user, $credentials);

			$user = Sentinel::getUser();
			//$user = User::with('role')->find($login_user->);
			$parentList = Employee::where('status', 1)->where('employee_type_id','!=', 5)->where('parent','!=', 0)->orderBy('first_name')->get()->pluck('full_name', 'id');
			$roles      = UserRole::all();

			return redirect( 'user/change' )->with([ 'success' => true,
				'success.message'=> 'User updated successfully!',
				'success.title' => 'Success!','empList'=>$parentList,'roles'=>$roles,'userC'=>$user, 'login_user' =>$user ]);
		}

		$user = Sentinel::getUser();
		//$user = User::with('role')->find($login_user->);
		$parentList = Employee::where('status', 1)->where('employee_type_id','!=', 5)->where('parent','!=', 0)->orderBy('first_name')->get()->pluck('full_name', 'id');
		$roles      = UserRole::all();

		return redirect( 'user/change' )->with([ 'error' => true,
			'error.message'=> 'User update failed!',
			'error.title' => 'Error!','empList'=>$parentList,'roles'=>$roles,'userC'=>$user, 'login_user' =>$user ]);
	}
}
