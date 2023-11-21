<?php
namespace Application\AlbumBox\Http\Requests;

use App\Http\Requests\Request;

abstract class AlbumBoxRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'box' => 'required|unique:ramon_box,box',
			];
		return $rules;
	}

}
