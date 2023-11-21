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

class Recipt extends Model
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
    protected $table = 'recipt';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    

    public function types()
    {
       return $this->belongsTo('Application\PaymentManage\Models\PaymentType','type','id');
    }

    public function user()
    {
       return $this->belongsTo('Core\UserManage\Models\User','user_id','id');
    }

    public function details()
    {
       return $this->hasMany('Application\PaymentManage\Models\ReciptDetail','recipt_id');
    }

    public function outlet()
    {
       return $this->belongsTo('Application\CustomerManage\Models\Customer','location_id','id');
    }

    public function cheque()
    {
       return $this->belongsTo('Application\PaymentManage\Models\ChequePayment','id','recipt_id');
    }

    public function cash()
    {
       return $this->belongsTo('Application\PaymentManage\Models\CashPayment','id','recipt_id');
    }

    public function employee()
    {
       return $this->belongsTo('Application\EmployeeManage\Models\Employee','user_id','id');
    }

    public function overpaidtranceaction()
    {
        return $this->belongsTo('Application\PaymentManage\Models\OverpaidTransaction','id','recipt_id');
    }

    public function customer()
    {
        return $this->belongsTo('Application\CustomerManage\Models\Customer','location_id','id');
    }

    public function account()
    {
        return $this->belongsTo('Application\AccountManage\Models\Account','account_id','id');
    }
    /**
     * for web service
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipt()
    {
        return $this->hasOne($this,'id','id');
    }


    /**
     * for web service
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiptdetails()
    {
        return $this->hasMany('Application\PaymentManage\Models\ReciptDetail','recipt_id');
    }
}