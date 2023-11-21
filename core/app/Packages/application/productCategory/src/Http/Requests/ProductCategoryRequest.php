<?php
namespace Application\ProductCategory\Http\Requests;

use App\Http\Requests\Request;

class ProductCategoryRequest extends Request {

	public function authorize(){
		return true;
	}

	public function rules(){
		$rules = [
			'productCategory' => 'required|regex:/^[^-0-9*\s*][\w\s\*\-\(\)\&\$\#\@\!\^\=\+\%\/\'\"\\\]*$/|unique:product_category,category_name'
			];
		return $rules;
	}

}
