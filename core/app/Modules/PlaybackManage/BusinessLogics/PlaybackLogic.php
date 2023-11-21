<?php namespace App\Modules\PlaybackManage\BusinessLogics;


/**
* Business Logics 
* Define all the busines logics in here
* @author Author <author@gmail.com>
* @version x.x.x
* @copyright Copyright (c) 2017, OITS.Dev+
*
*/
use App\Models\Tracking;
use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DateTime;
use Illuminate\Support\Facades\DB;

class PlaybackLogic {

	
	public function getPlaybackDataFromZuse($imei,$from,$to)
	{
		return Tracking::where('rep_id',$imei)->where("date",'>=', $from)->where("date",'<=', $to)->paginate(500);
	}
}
