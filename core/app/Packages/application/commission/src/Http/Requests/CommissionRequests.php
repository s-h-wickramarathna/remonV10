<?php
namespace Application\CommissionManage\Http\Requests;

use App\Http\Requests\Request;

class CommissionRequests extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->id;
        $rules = [
            'productName' => 'required',
            'category' => 'required',
            'size' => 'required',
            'short_code' => 'unique:remon_product,short_code,' . $id
        ];
        return $rules;
    }

}
