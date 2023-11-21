<?php
namespace Application\AlbumCover\Http\Requests;

use App\Http\Requests\Request;

class AlbumCoverRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'cover' => 'required|unique:ramon_cover,cover',
			];
		return $rules;
	}

}
