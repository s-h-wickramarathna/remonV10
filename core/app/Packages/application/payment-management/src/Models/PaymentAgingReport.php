<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\PaymentManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentAgingReport extends Model
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
    protected $table = 'payment_aging_report';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

}