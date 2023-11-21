<?php 

namespace Application\PaperType\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     chanimal <cahndimal@craftbyorane.com>
 * @copyright  Copyright (c) 2015, cahndimal
 * @version    v1.0.0
 */
class AlbumPaperType extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ramon_album_paper_type';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $guarded = ['id'];





}
