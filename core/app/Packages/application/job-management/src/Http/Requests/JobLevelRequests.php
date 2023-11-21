<?php
namespace Application\JobManage\Http\Requests;

use App\Http\Requests\Request;

class JobLevelRequests extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'job_id' => 'required',
        ];
        return $rules;
    }

}
