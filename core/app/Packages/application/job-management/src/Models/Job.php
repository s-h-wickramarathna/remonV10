<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:40 PM
 */

namespace Application\JobManage\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
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
    protected $table = 'job';

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function details()
    {
        return $this->hasMany('Application\InvoiceManage\Models\InvoiceDetail','invoice_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function location()
    {
        return $this->belongsTo('Application\LocationManage\Models\Location','location_id','id')->where('location_type_id','=',6);
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function employee()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','create_by','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function invoice()
    {
        return $this->belongsTo('Application\InvoiceManage\Models\Invoice','job_no','job_no');
    }


    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function customer()
    {
        return $this->belongsTo('Application\CustomerManage\Models\Customer','customer_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function create_by()
    {
        return $this->belongsTo('Application\EmployeeManage\Models\Employee','created_by','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function lamination()
    {
        return $this->hasMany('Application\JobManage\Models\JobLamination','job_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function paper()
    {
        return $this->hasMany('Application\JobManage\Models\JobPaperType','job_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function album_type()
    {
        return $this->hasMany('Application\JobManage\Models\JobAlbum','job_id','id');
    }

    public function album_size()
    {
        return $this->belongsTo('Application\AlbumSize\Models\AlbumSize','size','id');
    }

    public function album_cover()
    {
        return $this->hasMany('Application\JobManage\Models\JobCover','job_id','id');
    }

    public function album_box()
    {
        return $this->hasMany('Application\JobManage\Models\JobBox','job_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function confirmation()
    {
        return $this->hasMany('Application\JobManage\Models\JobLevelConfig','job_id','id');
    }

    public function link()
    {
        return $this->hasMany('Application\JobManage\Models\JobLevelConfig','job_link','status');
    }



}