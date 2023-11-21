<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\SalesOrderManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDetail extends Model
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
    protected $table = '4ever_sales_order_detail';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * product of the sales order detail
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function product()
    {
        return $this->belongsTo('Application\Product\Models\Product','product_id','id');
    }

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function discount()
    {
        return $this->belongsTo('Application\Product\Models\Product','product_id','id');
    }

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function order()
    {
        return $this->belongsTo('Application\SalesOrderManage\Models\SalesOrder','sales_order_id','id');
    }

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function stock()
    {
        return $this->hasMany('App\Models\Stock','product_id','product_id');
    }

}