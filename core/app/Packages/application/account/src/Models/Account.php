<?php 

namespace Application\AccountManage\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model Class
 *
 *

 */
class Account extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bank_account';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $guarded = ['id'];

    public function banks()
    {
        return $this->belongsTo('Application\PaymentManage\Models\Banks','bank','id');
    }



}
