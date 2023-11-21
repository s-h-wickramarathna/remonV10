<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/10/2015
 * Time: 10:53 AM
 */

namespace Application\EmployeeManage\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTypeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->id;
        $rules = [
            'type' => 'required|unique:employee_type,type,'.$id.',id,deleted_at,NULL',
            'parent' => 'required'
        ];
        return $rules;
    }

}