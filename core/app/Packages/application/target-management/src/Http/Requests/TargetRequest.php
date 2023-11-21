<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 3:09 PM
 */

namespace Application\TargetManage\Http\Requests;


use App\Http\Requests\Request;

class TargetRequest extends Request
{
    public function authorize(){
        return true;
    }

    public function rules(){

        $rules = [
            'rep'=>'required',
            'dateRange'=>'required',
            'value'=>'required|numeric'
        ];
        return $rules;
    }

}