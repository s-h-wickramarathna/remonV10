<?php
namespace Application\EmployeeManage\Models;

/**
 * Permission Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Chathura Chandimal <chandimal@craftbyorange.com>
 *
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeType extends Model
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
    protected $table = 'employee_type';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are assignable.
     *
     * @var array
     */
    public $fillable = ['type', 'parent'];

    /**
     * Parent Employee Type
     * @return object parent
     */
    public function parentType()
    {
        return $this->belongsTo($this,'parent','id');
    }

    /**
     * Parent Employee Type
     * @return object parent
     */
    public function getParent($id)
    {
        return $this::find($id);
    }

}