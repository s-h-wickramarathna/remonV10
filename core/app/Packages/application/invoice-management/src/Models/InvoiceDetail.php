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

class InvoiceDetail extends Model
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
    protected $table = 'invoice_detail';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * product details of invoice product
     * 
     */
    public function productDetail()
    {
        return $this->belongsTo('Application\Product\Models\Product','product_id','id');
    }

    /**
     * for print
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('Application\Product\Models\Product','product_id','id');
    }

    /**
     * for print
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function editor_()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','editor','id');
    }

    /**
     * Price 0f product
     * 
     */
    public function priceProduct()
    {
        return $this->belongsTo('Core\PriceBook\Models\PriceBookDetail','price_book_detail_id','id');
    }

}