<?php

namespace Core\PermissionGroups\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Core\Permissions\Models\Permission;

class PermissionGroupsController extends Controller{
	
	public function addView(){

		$permissions = Permission::select('id','name')->get(['id','name']);
		//$permissions->pull('admin');
		return view('permissionGroups::add')->with(['permissionArr' => $permissions]);
	}

	public function addGroup(Request $request){
		$this->validate($request,[
			'groupName'  => 'required|string'
		]);	
	}
}
