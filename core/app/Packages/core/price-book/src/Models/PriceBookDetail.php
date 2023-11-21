<?php
namespace Core\PriceBook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PriceBookDetail Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Sriya <csriyarathne@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class PriceBookDetail extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '4ever_price_book_detail';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['price_book_id','category_id','product_id','price','effective_date','ended_at'];


	public function priceBook()
    {
        return $this->belongsTo('Core\PriceBook\Models\PriceBook', 'price_book_id','id');
    }


	/**
	 * The relation between price book detail and product.
	 *
	 * @var array
	 */
	public function product()
    {
        return $this->belongsTo('Application\Product\Models\Product', 'product_id','id');
    }

	public function getProductCategory(){
		return $this->belongsTo('Application\ProductCategory\Models\ProductCategory','category_id','id');
	}

    public function custom(){
        return $this->belongsTo('Core\PriceBook\Models\PriceBookCustom','price_book_id','price_book_id');
    }

}
