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

class ReciptDetail extends Model
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
    protected $table = 'recipt_detail';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

     public function invoice()
    {
       return $this->belongsTo('Application\InvoiceManage\Models\Invoice','invoice_id','id');
    }

    public function bill()
    {
        return $this->belongsTo('Application\PaymentManage\Models\Recipt','recipt_id','id');
    }

}