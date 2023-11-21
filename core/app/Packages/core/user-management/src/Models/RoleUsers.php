<?php
namespace Core\UserManage\Models;

use Application\EmployeeManage\Models\Employee;
use Cartalyst\Sentinel\Permissions\PermissibleInterface;
use Cartalyst\Sentinel\Permissions\PermissibleTrait;
use Cartalyst\Sentinel\Persistences\PersistableInterface;
use Cartalyst\Sentinel\Roles\RoleableInterface;
use Cartalyst\Sentinel\Roles\RoleInterface;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Database\Eloquent\Model;


/**
 * User Roels Model Class
 *
 *
 * @category   Models
 * @package    Model
 * @author     Yasith Samarawickrama <yazith11@gmail.com>
 * @copyright  Copyright (c) 2015, Yasith Samarawickrama
 * @version    v1.0.0
 */
class RoleUsers extends Model {



	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'role_users';

	// guard attributes from mass-assignment
	protected $guarded = array('id');

}
