<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\JobManage\Models;


use Baum\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobMasterData extends Node
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
    protected $table = 'job_master_data';

    protected $guarded = ['id', 'parent', 'lft', 'rgt', 'depth'];

    public $fillable = ['name'];

    // 'parent_id' column name
    protected $parentColumn = 'parent';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rgt';

    // 'depth' column name
    protected $depthColumn = 'depth';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Parent Employee
     * @return object parent data
     */
    public function super()
    {
        return $this->belongsTo($this,'parent','id');
    }


}