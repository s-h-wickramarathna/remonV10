<?php

namespace App\Modules\locationManage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\LocationTypeManage\Models\LocationTypeManages;


class LocationManages extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use SoftDeletes;

    protected $table = 'location';

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
    public $timestamp = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 
        'location_type', 
        'address', 
        'remark', 
        'radius', 
        'latitude', 
        'longitude', 
        'icon_path', 
        'location_img_path', 
        'status'
    ];


    public function type()
    {
        return $this->belongsTo('App\Modules\LocationTypeManage\Models\LocationTypeManages','location_type');
    }

    
}
