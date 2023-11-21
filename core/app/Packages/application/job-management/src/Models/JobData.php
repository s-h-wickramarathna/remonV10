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

class JobData extends Model
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
    protected $table = 'job_data';

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
    public function master()
    {
        return $this->belongsTo('Application\JobManage\Models\JobMasterData','job_master_data_id','id');
    }


}