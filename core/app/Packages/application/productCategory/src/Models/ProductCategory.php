<?php 

namespace Application\ProductCategory\Models;
use Illuminate\Database\Eloquent\Model;


class ProductCategory extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'product_category';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['category_name', 'active_status'];

	public function products(){
        return $this->hasMany('Application\Product\Models\Product','product_category_id','id');
    }

 //    public function products()
	// {
	// 	return $this->belongsTo($this,'parent','id');
	// }

}
