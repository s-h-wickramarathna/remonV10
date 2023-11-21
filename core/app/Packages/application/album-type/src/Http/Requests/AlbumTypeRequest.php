<?php
namespace Application\AlbumType\Http\Requests;

use App\Http\Requests\Request;

class AlbumTypeRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'type' => 'required|unique:ramon_album_type,type',
			];
		return $rules;
	}

}
