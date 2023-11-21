<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\EmployeeManage\Models;


use Baum\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Node
{

    /**
     * table row delete
     */use SoftDeletes;
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employee';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'parent', 'lft', 'rgt', 'depth'];

    public $fillable = ['first_name', 'last_name', 'email','employee_type_id','mobile','address','cheque_name','land','business_name','code','credit_limit'];

    // 'parent_id' column name
    protected $parentColumn = 'parent';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rgt';

    // 'depth' column name
    protected $depthColumn = 'depth';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * get employee type each employee
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function type()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\EmployeeType','employee_type_id','id');
    }

    /**
     * Parent Employee
     * @return object parent employee
     */
    public function parentEmployee()
    {
        return $this->belongsTo($this,'parent','id');
    }

    /**
     * get full name
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * @param $query
     * @return mixed query
     */
    public function scopeOfType($query, $type)
    {
        return $query->where4ever_employee_type_id($type);
    }

    /**
     * relate to outlet tbl
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rep()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Rep','id','employee_id');
    }

    /**
     * relate to outlet tbl
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vehicle()
    {
        return $this->hasMany('Application\VehicleManage\Models\Vehicle','steward','id');
    }

    /**
     * relate to outlet tbl
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dis_warehouses()
    {
        return $this->hasMany('App\Models\Warehouse','distributor_id','id')->where('warehouse_type_id',1);
    }

    public function syncTime(){
        return $this->hasMany('Application\WebService\Models\SyncTime','user_id','id')->orderBy('id','DESC')->limit(1);
    }


    public function tracking()
    {

        return $this->hasMany('App\Models\Tracking','rep_id','id')->orderBy('id', 'DESC');
    }

    public function tracking_rep()
    {

        return $this->hasMany('App\Models\Tracking','rep_id','id')->orderBy('id', 'DESC')->limit(1);
    }

    public function user()
    {

        return $this->hasOne('Core\UserManage\Models\User','employee_id','id')->orderBy('id', 'DESC');
    }
}