<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\TargetManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketeerTarget extends Model
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
    protected $table = 'marketeer_target';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public $fillable = ['employee_id','from','to','value'];

    /**
     * get rep each target
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','employee_id','id');
    }

}