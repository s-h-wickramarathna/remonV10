<?php namespace App\Modules\PlaybackManage\Controllers;
/**
* CONTROLLER
* @author Author <tharindup@orelit.com> [tharindu lakshan]
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\LiveTrackingManage\BusinessLogics\DeviceLogic;

use App\Modules\PlaybackManage\BusinessLogics\PlaybackLogic;

class PlaybackManageController extends Controller {

	
	private $device_logics;
	private $playback_logics;

	function __construct()
	{
		$this->device_logics   = new DeviceLogic();
		$this->playback_logics = new PlaybackLogic();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function liveWithDevice($imei){
		$device_id;
		if(!isset($imei)){
			$device_id=-1;
		}else{
			$device_id = $imei;			
		}
		
		$vehicle = $this->device_logics->getDevice($device_id);

		if (!$vehicle) {
			return response()->view('errors.404');
		}

		//execute connection zuse server
		//this should be freaking secure dont lose zuse


		return view("PlaybackManage::playback_tracking")->with([
			'device_id'=>$device_id,
			'vehicle'=>$vehicle
		]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function liveWithoutDevice(){
		$device_id=-1;
		return view("PlaybackManage::playback_tracking")->with(['device_id'=>$device_id]);
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getPlaybackData(Request $request)
	{	
		$device_id = $request->device_id;
		$from      = $request->from;
		$to        = $request->to;
		return $positions = $this->playback_logics->getPlaybackDataFromZuse($device_id,$from,$to);

	}

}
