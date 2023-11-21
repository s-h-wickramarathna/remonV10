<?php

namespace App\Modules\LocationTypeManage\Models;

use Illuminate\Database\Eloquent\Model;

class LocationTypeManages extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'location_type';

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

    protected $fillable = [
        'name', 
        'description',
        'status',
    ];

    
}
