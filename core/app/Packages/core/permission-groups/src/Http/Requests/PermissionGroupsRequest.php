<?php

namespace Core\PermissionGroups\Http\Requests;

use App\Http\Requests\Request;

use Application\EmployeeManage\Models\Employee;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class PermissionGroupsRequest extends Request{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        $user = Sentinel::getUser();
        $userType =  Employee::find($user->id)->type;

        if(type == "1"){
            return true;
        }else{
            return false;
        }s  
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'groupName' => 'required|string'
        ];
    }
}
