<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\JobManage\Models;


use Illuminate\Database\Eloquent\Model;

class JobBox extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'job_box';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function box_obj()
    {
        return $this->belongsTo('Application\AlbumBox\Models\AlbumBox','box_id','id');
    }



}