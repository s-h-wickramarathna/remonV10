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
class PriceBook extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'price_book';


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['type','category','name'];


    public $userId = '';

	/**
	 * The relation between price book and price book details.
	 *
	 * @var array
	 */
	public function details()
    {
        return $this->hasMany('Core\PriceBook\Models\PriceBookDetail', 'price_book_id','id')->whereNull('ended_at');
    }

    /**
	 * The relation between price book and price book Category.
	 *
	 * @var array
	 */
	public function category()
    {
        return $this->belongsTo('App\Models\PriceBookCategory', 'category','id');
    }

    /**
     * The relation between price book and price book Category.
     *
     * @var array
     */
    public function type()
    {
        return $this->belongsTo('Core\PriceBook\Models\PriceBookType', 'type','id');
    }

    /**
     * For the web service
     * The relation between price book and price book detail.
     * @var array
     * @return selectted colomn
     */
    public function  price_book_detail_list(){
        return $this->hasMany('Core\PriceBook\Models\PriceBookDetail', 'price_book_id','id');
    }

    /**
     * For the web service
     * The relation between price book and price book Category.
     *
     * @var array
     */
    public function price_book_category_list()
    {
        return $this->belongsTo('App\Models\PriceBookCategory', 'category','id');
    }

    /**
     * For the web service
     * The relation between price book and price book type.
     *
     * @var array
     */
    public function price_book_type_list()
    {
        return $this->belongsTo('Core\PriceBook\Models\PriceBookType', 'type','id');
    }

    /**
     * For web service
     * The relation between price book and custom price book.
     *
     * @var array
     */
    public function price_book_custom_list()
    {
        return $this->hasMany('Core\PriceBook\Models\PriceBookCustom', 'price_book_id','id');
    }

    public function setUserID($userID){
        $this->userId = $userID;
    }

}
