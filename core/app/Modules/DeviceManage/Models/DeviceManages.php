<?php

namespace App\Modules\DeviceManage\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceManages extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'device';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'code', 'imei_no', 'mobile_no', 'status'];

    public function types()
    {
        return $this->belongsTo('App\Models\DeviceType','type','id');
    }

    public function assignedDevice()
    {
        return $this->hasOne('App\Models\VehicleDeviceHistory','device_id','id')->whereNull('deleted_at');
    }
    
}
