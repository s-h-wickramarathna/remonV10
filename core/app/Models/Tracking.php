<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Config;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_gps';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gps_info';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','rep_id','id');
    }
}