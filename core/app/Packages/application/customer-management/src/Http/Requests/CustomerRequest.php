<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 3:09 PM
 */

namespace Application\CustomerManage\Http\Requests;


use App\Http\Requests\Request;

class CustomerRequest extends Request
{
    public function authorize(){
        return true;
    }

    public function rules(){
        $id = $this->id;
        $rules = [
            'customer_fName'       =>  'required',
            'nic' => 'unique:remon_customer,nic,'. $id,
            'customer_mobile'  =>  'required|unique:remon_customer,mobile,'. $id,
            'customer_credit_limit' => 'required',
            'customer_credit_period' => 'required',
            'marketeer' => 'required',
            'area' => 'required',

        ];
        return $rules;
    }

}