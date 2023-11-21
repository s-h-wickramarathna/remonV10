<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\Configuration\Models;


use Baum\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuration extends Model
{

    /**
     * table row delete
     */
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wp_batch';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'mrp','status'];


}