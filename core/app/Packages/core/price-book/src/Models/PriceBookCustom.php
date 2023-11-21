<?php
namespace Core\PriceBook\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * PriceBook Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Sriya <csriyarathne@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class PriceBookCustom extends Model{

	use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_price_book_custom';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['price_book_id','user_id','user_category_id','ended_at'];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

	/**
	 * The relation between price book and price book details.
	 *
	 * @var array
	 */
	public function details()
    {
        return $this->belongsTo('Core\PriceBook\Models\PriceBook', 'price_book_id','id');
    }

	/**
	 * The relation between price book and price book details.
	 *
	 * @var array
	 */
	public function users($category)
    {    	
    	if($category==1){
    		return $this->belongsTo('Application\EmployeeManage\Models\Employee', 'user_id','id')->select('CONCAT(first_name," ",last_name) as name','id');
    	}else{
			return $this->belongsTo('Application\OutletListManagement\Models\Outlet', 'user_id','4ever_location_id')->select('outlet_name as name','id');
    	}
    }
	
}
