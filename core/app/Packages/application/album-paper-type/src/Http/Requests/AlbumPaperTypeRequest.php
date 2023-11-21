<?php
namespace Application\PaperType\Http\Requests;

use App\Http\Requests\Request;

class AlbumPaperTypeRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$id = $this->id;
		$rules = [
			'type' => 'required|unique:ramon_album_paper_type,type',
			];
		return $rules;
	}

}
