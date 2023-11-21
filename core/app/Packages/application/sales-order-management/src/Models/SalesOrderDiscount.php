<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\SalesOrderManage\Models;


use Illuminate\Database\Eloquent\Model;

class SalesOrderDiscount extends Model
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = '4ever_sales_order_discount';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];



}