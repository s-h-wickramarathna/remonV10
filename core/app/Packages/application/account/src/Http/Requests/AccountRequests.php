<?php
namespace Application\AccountManage\Http\Requests;

use App\Http\Requests\Request;

abstract class AccountRequests extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'bank' => 'required',
            'account_no' => 'required'
        ];
        return $rules;
    }

}
