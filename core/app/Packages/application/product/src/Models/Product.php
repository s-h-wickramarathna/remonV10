<?php 

namespace Application\Product\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     chanimal <cahndimal@craftbyorane.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class Product extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'remon_product';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $guarded = ['id'];


	/**
	 * get product category each product
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function category()
	{
		return $this->belongsTo('Application\ProductCategory\Models\ProductCategory','product_category_id','id');
	}

    public function sizes()
    {
        return $this->belongsTo('Application\AlbumSize\Models\AlbumSize','size_id','id');
    }

	/**
	 * get product brand each product
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	/*public function brand()
	{
		return $this->belongsTo('Application\Product\Models\Product_Brand','brand_id','id');
	}*/

	/**
	 * get product range each product
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
/*	public function range()
	{
		return $this->belongsTo('Application\Product\Models\Product_Range','range_id','id');
	}*/

	/**
	 * get product for invoice
	 * @return string
	 */
	public function getProductAttribute()
	{
		return $this->short_code . "-" . $this->product_name. " " . $this->description. " " . $this->pack_size;
	}

	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	/*public function price_book(){
		return $this->hasOne('Core\PriceBook\Models\PriceBookDetail');
	}*/

	/*public function price_book_details(){
		return $this->belongsTo('Core\PriceBook\Models\PriceBookDetail','id','product_id')->where('ended_at','=',null);
	}*/
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	/*public function stock(){
		return $this->hasMany('Application\Grn\Models\Stock','product_id','id');

	}*/

	public function mrp(){
		return $this->belongsTo('Core\PriceBook\Models\Mrp','id','product_id')->where('ended_at','=',null);
	}

//	public function mrpprices(){
//		return $this->hasMany('Core\PriceBook\Models\Mrp','product_id','id');
//	}

	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
/*	public function price(){
		return $this->belongsTo('Core\PriceBook\Models\PriceBook','id','product_id');
	}

    public function custom_price_book(){
        return $this->hasMany('Core\PriceBook\Models\PriceBookDetail','product_id','id')->where('4ever_price_book_detail.ended_at','=',null);
    }

    public function stranded_price_book(){
        return $this->hasMany('Core\PriceBook\Models\PriceBookDetail','product_id','id')->where('4ever_price_book_detail.ended_at','=',null);
    }*/





}
