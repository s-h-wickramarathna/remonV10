<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as CartalystUser;

class User extends CartalystUser{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'dsi_user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['permissions', 'last_login', 'username', 'password'];

	/**
	 * Login column names
	 *
	 * @var array
	 */
	protected $loginNames = ['username'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];

	/**
	 * get employee each user
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function employee()
	{
		return $this->hasMany('Application\EmployeeManage\Models\Employee','id','employee_id');
	}




}
