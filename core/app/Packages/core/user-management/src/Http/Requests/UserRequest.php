<?php
namespace Core\UserManage\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'employee' 					=> 'required|unique:user,employee_id,'.$id,
			'uName' 					=> 'required|unique:user,username,'.$id,
			'role' 						=> 'required'
		];
		return $rules;
	}

}
