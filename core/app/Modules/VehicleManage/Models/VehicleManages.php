<?php

namespace App\Modules\VehicleManage\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleManages extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vehicle_manages';

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
    protected $fillable = ['type', 'plate_no', 'chassis_no', 'status'];

    public function types()
    {
        return $this->belongsTo('App\Models\VehicleType','type','id');
    }

    public function assignedDevice()
    {
        return $this->hasOne('App\Models\VehicleDeviceHistory','vehicle_id','id')->whereNull('deleted_at');
    }

}
