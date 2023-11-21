<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\PaymentManage\Models;


use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banks';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

}