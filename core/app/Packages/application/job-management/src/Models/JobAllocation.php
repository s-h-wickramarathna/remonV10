<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\JobManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobAllocation extends Model
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
    protected $table = 'job_allocation';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];




    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','employee_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function job()
    {
        return $this->belongsTo('Application\JobManage\Models\Job','job_id','id');
    }

}