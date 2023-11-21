<?php namespace App\Modules\DeviceManage\Controllers;

/**
* Business Logics 
* Define all the busines logics in here
* @author Author <chatura@orelit.com> [chatura sriyarathna]
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\VehicleDeviceHistory;
use App\Models\DeviceType;

use App\Modules\DeviceManage\Models\DeviceManages;

use App\Exceptions\TransactionException;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use Session;
use Illuminate\Support\Facades\DB;
use Excel;


class DeviceManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        
        $type = $request->get('type');
        
        $code = $request->get('code');

        $imei_no = $request->get('imei_no');
        
        $mobile_no = $request->get('mobile_no');

        $perPage = 15;

        $old=[];

        $devicemanage = DeviceManages::whereNull('deleted_at')->with(['types']);

        if($type!=null && $type!=""){
            $devicemanage->where('type', 'like', '%' .$type.'%');
            $old['type']=$type;
        }else{
            $old['type']='';
        }

        if($code!=null && $code!=""){
            $devicemanage->where('code', 'like', '%' .$code.'%');
            $old['code']=$code;
        }else{
            $old['code']='';
        }

        if($imei_no!=null && $imei_no!=""){
            $devicemanage->where('imei_no', 'like', '%' .$imei_no.'%');
            $old['imei_no']=$imei_no;
        }else{
            $old['imei_no']='';
        }

        if($mobile_no!=null && $mobile_no!=""){
            $devicemanage->where('mobile_no', 'like', '%' .$mobile_no.'%');
            $old['mobile_no']=$mobile_no;
        }else{
            $old['mobile_no']='';
        }

        $devicemanage = $devicemanage->paginate($perPage);

        $typeList=DeviceType::all()->pluck('name','id')->prepend('------Select Device Type------',0);

        return view('DeviceManage::device-manage.index', compact('devicemanage','typeList','old'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $typeList=DeviceType::all()->pluck('name','id')->prepend('------Select Device Type------',0);
        return view('DeviceManage::device-manage.create', compact('typeList'));
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
                DeviceManages::create($requestData);
            });

            Session::flash('flash_message', 'Device added!');

            return redirect('admin/device/index');

        } catch (TransactionException $e) {
            Session::flash('flash_message', 'Device Not Added!');
        } catch (Exception $e) {
            Session::flash('flash_message', 'Device Not Added!');
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
        $devicemanage = DeviceManages::findOrFail($id);

        return view('DeviceManage::device-manage.show', compact('devicemanage'));
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
        $typeList=DeviceType::all()->pluck('name','id')->prepend('------Select Device Type------',0);

        $devicemanage = DeviceManages::findOrFail($id);

        return view('DeviceManage::device-manage.edit', compact('devicemanage','typeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id,Request $request)
    {
        
        $requestData = $request->all();
        
        try {
            
            DB::transaction(function () use ($id,$requestData) {
                
                $devicemanage = DeviceManages::findOrFail($id);
                
                if($devicemanage){

                    $devicemanage->update($requestData);

                }else{
                    throw new TransactionException('Something wrong', 100);
                }
            });

            Session::flash('flash_message', 'DeviceManages updated!');

            return redirect('admin/device/index');

        } catch (TransactionException $e) {
            Session::flash('flash_message', 'DeviceManages Not Updated!');
            return redirect('admin/device/edit/'.$id);
        } catch (Exception $e) {
            Session::flash('flash_message', 'DeviceManages Not Updated!');
            return redirect('admin/device/edit/'.$id);
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
        DeviceManages::destroy($id);

        Session::flash('flash_message', 'DeviceManages deleted!');

        return redirect('device/device-manage');
    }

    /**
     * Deactivate device.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deactivate(Request $request)
    {
        if($request->ajax()){
            $id = $request->id;

            $device = DeviceManages::find($id);
            if($device){

                $using=VehicleDeviceHistory::where('device_id',$device->id)->whereNull('deleted_at')->first();

                if($using){
                    return response()->json(['status' => 'using']);                    
                }else{
                    $device->status=0;
                    $device->save();
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
     * Activate device.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activate(Request $request)
    {
        if($request->ajax()){
            $id = $request->id;

            $device = DeviceManages::find($id);
            if($device){

                $device->status=1;
                $device->save();

                return response()->json(['status' => 'success']);
            }else{
                return response()->json(['status' => 'invalid_id']);
            }
        }else{
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Upload device.
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
                        'code'=>$value->code,
                        'imei_no'=>$value->imei_no,
                        'mobile_no'=>$value->mobile_no
                    ];
                }
                
                try {
            
                    DB::transaction(function () use ($dataset) {

                        $result=DeviceManages::insert($dataset);

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

                // DeviceManages::where('code',$value->code)->orWhere('imei_no',$value->imei_no)->orWhere('mobile_no','')->get();

            }else{
                return response()->json(['status' => 'error' ,'msg' => 'No file to upload. Please select file']);
            }            
        }else{
            return response()->json(['status' => 'error' ,'msg' => 'Device type not selected. Please select valid device type']);
        }

    }
}
