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

class SalesOrder extends Model
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
    protected $table = '4ever_sales_order';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function details()
    {
        return $this->hasMany('Application\SalesOrderManage\Models\SalesOrderDetail','sales_order_id','id');
    }

    /**
     * discount of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function discount()
    {
        return $this->hasMany('Application\SalesOrderManage\Models\SalesOrderDiscount','sales_order_id','id');
    }

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function location()
    {
        return $this->belongsTo('Application\LocationManage\Models\Location','location_id','id');
    }

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','rep_id','id');
    }

    /**
     * detail of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function brand()
    {
        return $this->belongsTo('Application\Product\Models\Product_Brand','brand_id','id');
    }

    /**
     * discount of the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function discounts()
    {
        return $this->hasMany('Application\SalesOrderManage\Models\SalesOrderDiscount','sales_order_id','id');
    }
}