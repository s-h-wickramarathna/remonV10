<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 3:09 PM
 */

namespace Application\Configuration\Http\Requests;


use App\Http\Requests\Request;

class ConfigurationRequest extends Request
{
    public function authorize(){
        return true;
    }

    public function rules(){
        $id = $this->id;
        $rules = [
            'fName' => 'required',
            'lName' => 'required',
            'email' => 'required|email|unique:dsi_employee,email,'.$id
        ];
        return $rules;
    }

}