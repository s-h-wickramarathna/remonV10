<?php namespace App\Modules\LiveTrackingManage\Controllers;


/**
* CONTROLLER
* @author Author <info.tharindumac@gmail.com>
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\LiveTrackingManage\BusinessLogics\DeviceLogic;
use Illuminate\Http\Request;

class LiveTrackingManageController extends Controller {

	private $device_logics;

	function __construct()
	{
		$this->device_logics = new DeviceLogic();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function liveWithDevice($imei){
		$device_id=-1;
		if(!isset($imei)){
			$device_id=-1;
		}else{
			$device_id = $imei;			
		}	
		// dd('ok');	
		return view("LiveTrackingManage::live_tracking")->with(['device_id'=>$device_id]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function liveWithoutDevice(){
		$device_id=-1;
		// dd($device_id);
		return view("LiveTrackingManage::live_tracking")->with(['device_id'=>$device_id]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	//JSON 
	public function get_devices()
	{
		return $devices = $this->device_logics->getDevices();
	}

	//JSON 
	public function get_vehicle(Request $request)
	{	
		$imei = $request->imei;
		if($imei == -1){
			return $devices = $this->device_logics->getDevices();
		}
		return $this->device_logics->getDeviceVehicle($imei);
	}


	//JSON 
	public function get_locations()
	{	
		return $locations = [];//$this->device_logics->getLocations();
	}

}
