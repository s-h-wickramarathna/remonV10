<?php
namespace Application\Discount\Http\Requests;

use App\Http\Requests\Request;

class DiscountRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->id;
        $rules = [
            'group_name'    => 'required|unique:4ever_discount_group,name,NULL,id,status,1'
        ];
        return $rules;
    }

}
