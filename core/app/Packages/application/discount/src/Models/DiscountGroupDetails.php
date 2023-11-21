<?php namespace Application\Discount\Models;

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
class DiscountGroupDetails extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_discount_group_detail';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['group_id', 'product_id','status'];

}
