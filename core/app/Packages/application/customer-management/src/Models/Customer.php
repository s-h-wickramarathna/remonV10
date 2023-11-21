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

class Customer extends Model
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
    protected $table = 'remon_customer';

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


    /**
     * relate to outlet tbl
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function marketeer()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','marketeer_id','id');
    }

    public function user()
    {
        return $this->belongsTo('Core\UserManage\Models\User','user_id','id');
    }

    public function area_obj(){
        return $this->hasMany('Application\CustomerManage\Models\Area','area','id');
    }



}