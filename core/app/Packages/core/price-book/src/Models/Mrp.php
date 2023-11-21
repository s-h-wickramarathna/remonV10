<?php
namespace Core\PriceBook\Models;

use Illuminate\Database\Eloquent\Model;

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
class Mrp extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mrp';


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['product_id','mrp','ended_at'];

	/**
	 * The relation between price book and price book details.
	 *
	 * @var array
	 */
	public function product()
    {
        return $this->hasMany('Application\Product\Models\Product', 'product_id','id');
    }    

}
