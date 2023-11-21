<?php
/**
 * Created by PhpStorm.
 * User: Chathuranga Bandara
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\EmployeeManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rep extends Model
{

    /**
     * table row delete
     */
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rep';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * get employee type each employee
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee()
    {
        return $this->hasMany('Application\EmployeeManage\Models\Employee', 'id', 'employee_id');
    }

    

}