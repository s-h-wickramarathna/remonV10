<?php
namespace Core\MenuManage\Models;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Menu Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Yasith Samarawickrama <yazith11@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class Menu extends Node{

	use SoftDeletes;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menu';

	// 'parent_id' column name
	protected $parentColumn = 'parent';

	// 'lft' column name
	protected $leftColumn = 'lft';

	// 'rgt' column name
	protected $rightColumn = 'rgt';

	// 'depth' column name
	protected $depthColumn = 'depth';

	// guard attributes from mass-assignment
	protected $guarded = array('id', 'parent', 'lft', 'rgt', 'depth');

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['label', 'link', 'icon', 'permissions'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * Parent menu
	 * @return object parent menu
	 */
	public function parentMenu()
	{
		return $this->belongsTo($this,'parent','id');
	}

}
