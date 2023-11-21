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

class AdminAuth extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_auth';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function customer()
    {
        return $this->belongsTo('Application\CustomerManage\Models\Customer','customer_id','id');
    }
}