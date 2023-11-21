<?php
namespace Core\PriceBook\Http\Requests;

use App\Http\Requests\Request;

class PriceBookRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$rules = [];
		return $rules;
	}

}
