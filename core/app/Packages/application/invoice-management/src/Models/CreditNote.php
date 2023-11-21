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

class CreditNote extends Model
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
    protected $table = 'credit_note';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function create_user()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','create_by','id');
    }

    public function invoice()
    {
        return $this->belongsTo('Application\InvoiceManage\Models\Invoice','invoice_id','id');
    }

}