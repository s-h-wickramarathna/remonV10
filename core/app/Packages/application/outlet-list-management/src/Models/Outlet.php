<?php 

namespace Application\OutletListManagement\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Tharindu Lakshan <tharindup@craftbyorange> (a craft from mac)
 * @copyright  Copyright (c) 2015, Tharindu Lakshan
 * @version    v1.0.0
 */
class Outlet extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'dimo_outlet';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [
		'outlet_name,
		credit_limit,
		credit_period,
		outlet_address,
		outlet_tel,
		outlet_email,
		outlet_fax,
		image,
		active_status,
		short_code,
		owner,
		discount_active,
		sequece'];

	/**
	 * get product range each product
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function locations()
	{
		return $this->belongsTo('Application\LocationManage\Models\Location','location_id','id');
	}


}
