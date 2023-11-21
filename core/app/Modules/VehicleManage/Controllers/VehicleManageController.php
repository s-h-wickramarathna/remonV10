<?php namespace App\Modules\VehicleManage\Controllers;
/**
* CONTROLLER
* @author Author <chatura@orelit.com> [chatura sriya]
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exceptions\TransactionException;
use Illuminate\Database\QueryException;
use App\Modules\VehicleManage\Models\VehicleManages;
use Illuminate\Http\Request;
use PhpSpec\Exception\Exception;
use Session;
use Illuminate\Support\Facades\DB;
use Excel;
use App\Models\VehicleType;
use App\Models\VehicleDeviceHistory;
use App\Modules\DeviceManage\Models\DeviceManages;

class VehicleManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $plate_no = $request->get('plate_no');
        
        $chassis_no = $request->get('chassis_no');
        
        $status = $request->get('vehicle_status');

        $perPage = 15;

        $old=[];

        $vehiclemanage = VehicleManages::whereNull('deleted_at')->with(['types','assignedDevice.device']);

        if($plate_no!=null && $plate_no!=""){
            $vehiclemanage->where('plate_no', 'like', '%' .$plate_no.'%');
            $old['plate_no']=$plate_no;
        }else{
            $old['plate_no']='';
        }

        if($chassis_no!=null && $chassis_no!=""){
            $vehiclemanage->where('chassis_no', 'like', '%' .$chassis_no.'%');
            $old['chassis_no']=$chassis_no;
        }else{
            $old['chassis_no']='';
        }
       
        $vehiclemanage = $vehiclemanage->paginate($perPage);

        $usingOrDamageDevices=array_flatten(VehicleDeviceHistory::whereNull('deleted_at')->get()->pluck('device_id'));

        $device=DeviceManages::with(['types'])->where('status',1)->whereNotIn('id',$usingOrDamageDevices)->get();

        $deviceList=array();

        foreach ($device as $value) {
            $deviceList[$value->id]='CODE : '.$value->code."  /  MODEL : ".$value->types->name;
        }

        $typeList=VehicleType::all()->pluck('name','id')->prepend('------Select Vehicle Type------',0);

        return view('VehicleManage::vehicle-manage.index', compact('vehiclemanage','deviceList','typeList','old'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {   
        $typeList=VehicleType::all()->pluck('name','id')->prepend('------Select Vehicle Type------',0);
        return view('VehicleManage::vehicle-manage.create', compact('typeList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();

        try {
            
            DB::transaction(function () use ($requestData) {
                VehicleManages::create($requestData);
            });

            Session::flash('flash_message', 'Vehicle added!');

            return redirect('admin/vehicle/index');

        } catch (TransactionException $e) {
            Session::flash('flash_message', 'Vehicle Not Added!');
        } catch (Exception $e) {
            Session::flash('flash_message', 'Vehicle Not Added!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $vehiclemanage = VehicleManages::findOrFail($id);

        return view('VehicleManage::vehicle-manage.show', compact('vehiclemanage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

        $typeList=VehicleType::all()->pluck('name','id')->prepend('------Select Vehicle Type------',0);

        $vehiclemanage = VehicleManages::findOrFail($id);

        return view('VehicleManage::vehicle-manage.edit', compact('vehiclemanage','typeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();
        
        try {
            
            DB::transaction(function () use ($id,$requestData) {
                
                $vehiclemanage = VehicleManages::findOrFail($id);
                
                if($vehiclemanage){

                    $vehiclemanage->update($requestData);

                }else{
                    throw new TransactionException('Something wrong', 100);
                }
            });

            Session::flash('flash_message', 'VehicleManages updated!');

            return redirect('admin/vehicle/index');

        } catch (TransactionException $e) {
            Session::flash('flash_message', 'VehicleManages Not Updated!');
            return redirect('admin/vehicle/edit/'.$id);
        } catch (Exception $e) {
            Session::flash('flash_message', 'VehicleManages Not Updated!');
            return redirect('admin/vehicle/edit/'.$id);
        }     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        VehicleManages::destroy($id);

        Session::flash('flash_message', 'VehicleManages deleted!');

        return redirect('admin/vehicle/vehicle-manage');
    }

    /**
     * Un-Assigned attached device.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function unassign(Request $request)
    {
        if($request->ajax()){
            $id = $request->id;

            $vehicle = VehicleManages::find($id);
            if($vehicle){

                VehicleDeviceHistory::whereNull('deleted_at')->where('vehicle_id',$id)->delete();

                return response()->json(['status' => 'success']);
            }else{
                return response()->json(['status' => 'invalid_id']);
            }
        }else{
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Assign new device.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function assign(Request $request)
    {
        if($request->ajax()){
            $vehicle_id = $request->vid;
            $device_id = $request->did;

            $vehicle = VehicleManages::find($vehicle_id);
            $device = DeviceManages::find($device_id);

            if($vehicle && $device_id){

                $transaction=VehicleDeviceHistory::create([
                    'vehicle_id'=>$vehicle->id,
                    'device_id'=>$device->id,
                ]);

                if(!$transaction){
                    return response()->json(['status' => 'error']);
                }else{
                    return response()->json(['status' => 'success']);                    
                }
            }else{
                return response()->json(['status' => 'invalid_ids']);
            }
        }else{
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Deactivate Vehicle.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deactivate(Request $request)
    {
        if($request->ajax()){
            $id = $request->id;

            $vehicle = VehicleManages::find($id);
            if($vehicle){

                $using=VehicleDeviceHistory::where('device_id',$vehicle->id)->whereNull('deleted_at')->first();

                if($using){
                    return response()->json(['status' => 'using']);                    
                }else{
                    $vehicle->status=0;
                    $vehicle->save();
                }

                return response()->json(['status' => 'success']);
            }else{
                return response()->json(['status' => 'invalid_id']);
            }
        }else{
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Activate Vehicle.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activate(Request $request)
    {
        if($request->ajax()){
            $id = $request->id;

            $vehicle = VehicleManages::find($id);
            if($vehicle){

                $vehicle->status=1;
                $vehicle->save();

                return response()->json(['status' => 'success']);
            }else{
                return response()->json(['status' => 'invalid_id']);
            }
        }else{
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Upload Vehicle.
     *
     * @param  file
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload(Request $request)
    {
        
        $type=$request->type;

        if($type!="" && $type>0){
            if($request->hasFile('inputficons1')){
                $path = $request->file('inputficons1')->getRealPath();

                $data = Excel::load($path, function($reader) {})->get();

                $dataset=[];

                foreach ($data as $value) {
                    $dataset[]=[
                        'type'=>$type,
                        'plate_no'=>$value->plate_no,
                        'chassis_no'=>$value->chassis_no
                    ];
                }
                
                try {
            
                    DB::transaction(function () use ($dataset) {

                        $result=VehicleManages::insert($dataset);

                        if(!$result){
                            throw new TransactionException('Something wrong', 100);
                        }

                    });

                    return response()->json(['status' => 'success','msg'=>'Upload completed']);

                } catch (TransactionException $e) {
                    return response()->json(['status' => 'error' ,'msg' => 'Transaction error. Try Again']);
                } catch (Exception $e) {
                    return response()->json(['status' => 'error','msg' => 'Transaction error. Try Again']);
                }catch (QueryException $e){
                    return response()->json(['status' => 'error','msg' => 'Data Already Exist in system. Remove them before upload']);
                }

            }else{
                return response()->json(['status' => 'error' ,'msg' => 'No file to upload. Please select file']);
            }            
        }else{
            return response()->json(['status' => 'error' ,'msg' => 'Device type not selected. Please select valid device type']);
        }

    }
}
