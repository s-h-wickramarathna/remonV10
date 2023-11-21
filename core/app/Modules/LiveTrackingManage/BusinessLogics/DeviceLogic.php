<?php namespace App\Modules\LiveTrackingManage\BusinessLogics;


/**
* Business Logics 
* Define all the busines logics in here
* @author Author <tharindup@orelit.com> [tharindu lakshan]
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/
use App\Modules\DeviceManage\Models\DeviceManages;
use Application\EmployeeManage\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use App\Modules\VehicleManage\Models\VehicleManages;
use App\Modules\locationManage\Models\LocationManages;

class DeviceLogic extends Model {

	


	public function getDevices()
	{
		return Employee::with('tracking_rep')->where('employee_type_id',2)->get();
	}

	public function getLocations()
	{
		return LocationManages::with(['type'])->get();
	}

	public function getDeviceVehicle($imei)
	{
		return Employee::with('tracking_rep')->where('employee_type_id',2)->where('id',$imei)->get();
	}


	public function getDevice($imei)
	{
		return Employee::with('tracking_rep')->where('employee_type_id',2)->where('id',$imei)->first();
	}

}
