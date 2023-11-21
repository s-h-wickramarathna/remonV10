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
class DiscountGroup extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_discount_group';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name','rule_id','rule_type','status'];

    /**
     * get group each group outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ruledetail()
    {
        return $this->hasMany('Application\Discount\Models\DiscountRuleDetail', 'discount_rule_id', 'rule_id');
    }

	public function rule()
	{
		return $this->hasOne('Application\Discount\Models\DiscountRule', 'id', 'rule_id');
	}

    /**
     * get group each group outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupdetail()
    {
        return $this->hasMany('Application\Discount\Models\DiscountGroupDetails', 'group_id', 'id');
    }



}
