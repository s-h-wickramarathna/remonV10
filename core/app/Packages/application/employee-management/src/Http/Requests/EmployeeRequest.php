<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 3:09 PM
 */

namespace Application\EmployeeManage\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class EmployeeRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        $id = $this->id;
        $rules = [
            'fName' => 'required|unique:employee,first_name,'.$id.',id,last_name,'.$this->lName,
            'lName' => 'required|unique:employee,last_name,'.$id.',id,first_name,'.$this->fName,
            'email' => 'email|unique:employee,email,'.$id,
            'land' => 'min:10|max:10',
            'mobile'=> 'min:10|max:10'
        ];
        return $rules;
    }

}