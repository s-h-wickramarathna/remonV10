<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\CustomerManage\Models;


use Application\EmployeeManage\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
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
    protected $table = 'city';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];



    /**
     * get vehicle type each vehicle
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */


    public function customer()
    {
        return $this->belongsTo('Application\CustomerManage\Models\Customer','area','id');
    }

}