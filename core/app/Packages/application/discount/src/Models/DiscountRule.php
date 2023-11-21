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
class DiscountRule extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_discount_rule';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name','discount_rule_type_id','status','timestamp','max'];

	/**
	 * get group each group outlet
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function type()
	{
		return $this->belongsTo('Application\Discount\Models\DiscountRuleType', 'id', 'discount_rule_type_id');
	}


}
