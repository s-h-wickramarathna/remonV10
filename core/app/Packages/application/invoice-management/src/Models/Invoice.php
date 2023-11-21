<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\InvoiceManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
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
    protected $table = 'invoice';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function details()
    {
        return $this->hasMany('Application\InvoiceManage\Models\InvoiceDetail','invoice_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function location()
    {
        return $this->belongsTo('Application\LocationManage\Models\Location','location_id','id')->where('location_type_id','=',6);
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','rep_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function create_by()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','created_by','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function discounts()
    {
        return $this->belongsTo('Application\InvoiceManage\Models\InvoiceDiscount','id','invoice_id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function getTotalDiscountAttribute()
    {
        return $this->discounts->sum('discount');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function getTotalDiscountPrecentageAttribute()
    {
        return number_format((($this->discounts->sum('discount') / $this->attributes['total'])*100),2);
    }


    public function customer()
    {
       return $this->belongsTo('Application\CustomerManage\Models\Customer','location_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function discount()
    {
        return $this->belongsTo('Application\InvoiceManage\Models\InvoiceDiscount','id','invoice_id');
    }

    /**
     * invoices of the Sales order
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo [collection]
     */
    public function salesOrder()
    {
        return $this->belongsTo('Application\SalesOrderManage\Models\SalesOrder','order_id','id');
    }

    public function recipt(){
        return $this->hasMany('Application\PaymentManage\Models\ReciptDetail','invoice_id','id');
    }

}