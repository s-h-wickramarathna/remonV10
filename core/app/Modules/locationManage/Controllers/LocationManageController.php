<?php

namespace App\Modules\locationManage\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\locationManage\Models\LocationManages;
use Illuminate\Http\Request;
use Session;
use App\Modules\LocationTypeManage\Models\LocationTypeManages as LocationTypes;
use Validator;
use Illuminate\Support\Facades\DB;

class LocationManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if(!empty($keyword))
        {
            $locations = LocationManages::select('location.*', 'lt.name as location_type')
                ->join('location_type as lt', 'location_type', '=', 'lt.id')
                ->where(function($query) use ($keyword){
                    $query->where('location.name', 'LIKE', "%$keyword%")
                        ->orWhere('location.address', 'LIKE', "%$keyword%")
                        ->orWhere('location.remark', 'LIKE', "%$keyword%");
                })
                ->orderBy('location.id', 'DESC')
				->paginate($perPage);
        }
        else
        {
            $locations = LocationManages::select('location.*', 'lt.name as location_type')
                ->join('location_type as lt', 'location_type', '=', 'lt.id')
                ->orderBy('location.id', 'DESC')
                ->paginate($perPage);
        }
        
        return view('locationManage::location-manage.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $types = LocationTypes::where('status', 1)->get();
        return view('locationManage::location-manage.create')
                ->with(['types' => $types, 'status' => false]);
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
        $name         = $request->input('name');
        $locationType = $request->input('locationType');
        $address      = $request->input('address');
        $remark       = $request->input('remark');
        $radius       = $request->input('radius');
        $latitude     = $request->input('latitude');
        $longitude    = $request->input('longitude');
        $status       = $request->input('status')?:1;
        $img_icon     = $request->file('img_icon');
        $img_location = $request->file('img_location');
        
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'locationType' => 'required',
            'address'      => 'required',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
            'img_icon'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_location' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if($validator->fails())
        {
            return redirect()
                    ->route('admin.location.create')
                    ->with(['errors' => $validator->messages()])
                    ->withInput();
        }
        else
        {
            DB::beginTransaction();
            $hasAnyError = false;

            $location                    = new LocationManages;
            $location->name              = $name;
            $location->location_type     = $locationType;
            $location->address           = $address;
            $location->remark            = $remark;
            $location->radius            = $radius;
            $location->latitude          = $latitude;
            $location->longitude         = $longitude;
            $location->icon_path         = '';
            $location->location_img_path = '';
            $location->status            = $status;
            $location->created_at        = date('Y-m-d h:i:s');
            $location->save();

            $location_id  = $location->id;

            if(isset($location_id) && !empty($location_id))
            {
                $db_icon_path = '';
                $db_img_path  = '';
                $move_icon    = '';
                $move_img     = '';

                if(isset($img_icon) && filesize($img_icon) > 0)
                {
                    $path1          = 'assets/location/'.$location_id.'/icon';
                    $file_name1     = time()."-".$img_icon->getClientOriginalName();
                    $db_icon_path  = $path1."/".$file_name1;
                    $move_icon     = $img_icon->move($path1, $file_name1);
                }
                
                if(isset($img_location) && filesize($img_location) > 0)
                {
                    $path2          = 'assets/location/'.$location_id.'/images';
                    $file_name2     = time()."-".$img_location->getClientOriginalName();
                    $db_img_path   = $path2."/".$file_name2;
                    $move_img      = $img_location->move($path2, $file_name2);
                }
                

                if(sizeof($move_icon) == 0 || sizeof($move_img) == 0)
                {
                    $hasAnyError = true;
                }
                else
                {
                    $updated = LocationManages::find($location_id);
                    $updated->icon_path = $db_icon_path;
                    $updated->location_img_path = $db_img_path;
                    $updated->save();

                    if(count($updated) > 0)
                    {
                        DB::commit();
                        return redirect()
                            ->route('admin.location.create')
                            ->with([
                                'success.title' => 'Done!.',
                                'success.message' => 'Location has been successfully added!.'
                            ]);
                    }
                    else
                    {
                        DB::rollback();
                        return redirect()
                            ->route('admin.location.create')
                            ->withInput()
                            ->with([
                                'error.title' => 'Error!.',
                                'error.message' => 'Something went wrong!.'
                            ]);
                    }
                }
            }
            else
            {
                DB::rollback();
                return "something went wrong!.";
            }
        }

        /*LocationManages::insert([

        ]);

        Session::flash('flash_message', 'LocationManages added!');

        return redirect('admin/location/location-manage');*/
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
        if(!empty($id))
        {
            $location = LocationManages::select('location.*', 'lt.name as location_type')
                    ->join('location_type as lt', 'location_type', '=', 'lt.id')
                    ->find($id);

            return view('locationManage::location-manage.show', compact('location'));
        }
        else
        {
            return redirect()
                    ->route('admin.location.index')
                    ->with([
                        'error.title' => 'Error!.',
                        'error.message' => 'Something went wrong!.'
                    ]);
        }
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
        if(!empty($id))
        {
            $location = LocationManages::find($id);
            $types    = LocationTypes::where('status', 1)->get();
            $status   = true;

            return view('locationManage::location-manage.edit', compact('location', 'types', 'status')); 
        }
        else
        {
            return  redirect()
                        ->route('admin.location.index')
                        ->with([
                            'error.title' => 'Error!.',
                            'error.message' => 'Something went wrong!.'
                        ]);
        }
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
        $name         = $request->input('name');
        $locationType = $request->input('locationType');
        $address      = $request->input('address');
        $remark       = $request->input('remark');
        $radius       = $request->input('radius');
        $latitude     = $request->input('latitude');
        $longitude    = $request->input('longitude');
        $status       = $request->input('status');
        $img_icon     = $request->file('img_icon');
        $img_location = $request->file('img_location');
        
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'locationType' => 'required',
            'address'      => 'required',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
            'img_icon'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_location' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if($validator->fails())
        {
            return redirect()
                    ->route('admin.location.edit', $id)
                    ->with([
                        'errors' => $validator->messages()
                    ]);
        }
        else
        {   
            DB::beginTransaction();
            $db_icon_path = '';
            $db_img_path  = '';

            $location                = LocationManages::find($id);
            $location->name          = $name;
            $location->location_type = $locationType;
            $location->address       = $address;
            $location->remark        = $remark;
            $location->radius        = $radius;
            $location->latitude      = $latitude;
            $location->longitude     = $longitude;
            $location->status        = $status;
            $location->updated_at    = date('Y-m-d h:i:s');

            if(isset($img_icon) && filesize($img_icon) > 0)
            {
                $path1          = 'assets/location/'.$id.'/icon';
                $file_name1     = time()."-".$img_icon->getClientOriginalName();
                $db_icon_path  = $path1."/".$file_name1;
                $move_icon     = $img_icon->move($path1, $file_name1);
            }
            else
            {
                $objLocation = LocationManages::select('icon_path')
                                ->where('id', $id)
                                ->where('icon_path', '!=','')
                                ->first();

                if(count($objLocation) > 0)
                {
                    $db_icon_path = $objLocation->icon_path;
                }
            }

            if(isset($img_location) && filesize($img_location) > 0)
            {
                $path          = 'assets/location/'.$id.'/images';
                $file_name     = time()."-".$img_location->getClientOriginalName();
                $db_img_path   = $path."/".$file_name;
                $move_img      = $img_location->move($path, $file_name);
            }
            else
            {
                $objLocation2 = LocationManages::select('location_img_path')
                                ->where('id', $id)
                                ->where('location_img_path', '!=','')
                                ->first();

                if(count($objLocation2) > 0)
                {
                    $db_img_path = $objLocation2->location_img_path;
                }
            }

            $location->icon_path         = $db_icon_path;
            $location->location_img_path = $db_img_path;
            $location->save();

            if(count($location) > 0)
            {
                DB::commit();
                return redirect()
                        ->route('admin.location.index')
                        ->with([
                            'success.title'   => 'Done!.',
                            'success.message' => 'Location has been successfully updated!.'
                        ]);
            }
            else
            {
                DB::rollback();
                return redirect()
                        ->route('admin.location.index')
                        ->with([
                            'error.title'   => 'Error!.',
                            'error.message' => 'the location couldn\'t be updated!.'
                        ]);
            }
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
        DB::beginTransaction();

        if(!empty($id))
        {
            $deleted = LocationManages::find($id)->delete();

            if($deleted)
            {
                DB::commit();
                return redirect()
                    ->route('admin.location.index')
                    ->with([
                        'success.title'   => 'Done!.',
                        'success.message' => 'Location has been successfully deleted!.'
                    ]);       
            }
            else
            {
                DB::rollback();
                return redirect()
                        ->route('admin.location.index')
                        ->with([
                            'error.title'   => 'Error!.',
                            'error.message' => 'Something went wrong!.'
                        ]);
            }
        }
        else
        {
            DB::rollback();
            return redirect()
                    ->route('admin.location.index')
                    ->with([
                        'error.title'   => 'Error!.',
                        'error.message' => 'Something went wrong!.'
                    ]);
        }
    }
}
