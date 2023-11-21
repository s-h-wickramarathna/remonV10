<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 3:09 PM
 */

namespace Application\OutletListManagement\Http\Requests;


use App\Http\Requests\Request;

class OutletListManagementRequest extends Request
{
    public function authorize(){
        return true;
    }

    public function rules(){
        $id = $this->id;
        $rules = [
        ];
        return $rules;
    }

}