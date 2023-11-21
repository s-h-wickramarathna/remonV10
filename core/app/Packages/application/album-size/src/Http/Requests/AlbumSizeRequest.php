<?php
namespace Application\AlbumSize\Http\Requests;

use App\Http\Requests\Request;

class AlbumSizeRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'size' => 'required',
			];
		return $rules;
	}

}
