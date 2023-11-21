<?php

namespace App\Modules\MaintenanceTypeManage\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'maintenance_type';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'remark'];

    
}
