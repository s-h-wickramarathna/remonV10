<?php 

namespace Application\CommissionManage\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model Class
 *
 *

 */
class MarketeerCommission extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'marketeer_commission';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $guarded = ['id'];



    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','employee_id','id');
    }




}
