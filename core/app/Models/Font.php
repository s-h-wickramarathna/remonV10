<?php namespace App\Models;

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
class Font extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fonts-list';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['type', 'icon'];

	public function getIconNameAttribute()
    {
        return $this->attributes['type'].' '.$this->attributes['icon'];
    }

    public function getIconListAttribute()
    {
    	if($this->attributes['unicode']){
        	return $this->attributes['unicode']." ".$this->attributes['icon'];
    	}else{
    		return $this->icon;
    	}
    }

}
