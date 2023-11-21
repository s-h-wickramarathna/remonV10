<?php 

namespace Application\Product\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Yasith Samarawickrama <yazith11@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class Product_Range extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_range';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product(){
		return $this->belongsTo('Application\Product\Models\Product','range_id');
	}

}
