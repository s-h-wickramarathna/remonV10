<?php
namespace Core\PriceBook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PriceBookType Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Sriya <csriyarathne@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class PriceBookType extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_price_book_type';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name'];

}
