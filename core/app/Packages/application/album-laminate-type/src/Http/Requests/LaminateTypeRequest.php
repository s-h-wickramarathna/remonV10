<?php
namespace Application\LaminateType\Http\Requests;

use App\Http\Requests\Request;

class LaminateTypeRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'laminate' => 'required|unique:ramon_laminate_type,laminate_type',
			];
		return $rules;
	}

}
