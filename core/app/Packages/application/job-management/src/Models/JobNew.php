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

class JobNew extends Model
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
    protected $table = 'job_new';

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
    public function customer()
    {
        return $this->belongsTo('Application\CustomerManage\Models\Customer','customer_id','id');
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
    public function confirmation()
    {
        return $this->hasMany('Application\JobManage\Models\JobLevelConfig','job_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function data()
    {
        return $this->hasMany('Application\JobManage\Models\JobData','job_new_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function printer()
    {
        return $this->hasMany('Application\JobManage\Models\JobPrinter','job_id','id');
    }

    /**
     * invoices of the outlet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany [collection]
     */
    public function job_album()
    {
        return $this->hasMany('Application\JobManage\Models\JobAlbum','job_id','id');
    }



}