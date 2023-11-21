<?php

namespace App\Modules\LocationTypeManage\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\LocationTypeManage\Models\LocationTypeManages;
use App\Modules\LocationTypeManage\Request\locationTypeRequest;
use Illuminate\Http\Request;
use Session;
use Validator;
use Illuminate\Support\Facades\DB;

class LocationTypeManageController extends Controller
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

        if (!empty($keyword)) {
            $locations = LocationTypeManages::where('name', 'LIKE', "%$keyword%")
				->orWhere('description', 'LIKE', "%$keyword%")
                ->orderBy('id', 'DESC')
				->paginate($perPage);
        } else {
            $locations = LocationTypeManages::orderBy('id', 'DESC')
                            ->paginate($perPage);
        }

        return view('LocationTypeManage::location-type-manage.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('LocationTypeManage::location-type-manage.create');
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
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect()
                    ->route('admin.location.type.create')
                    ->with([
                        'errors' => $validator->messages()
                    ])
                    ->withInput();
        }
        else
        {
            try 
            {
                DB::beginTransaction();

                $requestData = $request->all();
                $location = LocationTypeManages::create($requestData);

                if(sizeof($location) > 0)
                {
                    DB::commit();
                    return redirect()
                            ->route('admin.location.type.create')
                            ->with([
                                'success.title'   => 'Done!.',
                                'success.message' => 'Location Type has been successfully added!.'
                            ]);
                }
                else
                {   
                    DB::rollback();
                    return redirect()
                            ->route('admin.location.type.create')
                            ->with([
                                'error.title'   => 'Error!.',
                                'error.message' => 'Something went wrong!.'
                            ]);
                }
            } 
            catch(\Exception $e)
            {
                DB::rollback();
                return redirect()
                        ->route('admin.location.type.create')
                        ->with([
                            'error.title'    => 'Error!.',
                            'error.message' => $e->getMessage()
                        ]);   
            }
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
        $type = LocationTypeManages::findOrFail($id);

        return view('LocationTypeManage::location-type-manage.show', compact('type'));
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
        $locationtypemanage = LocationTypeManages::find($id);

        return view('LocationTypeManage::location-type-manage.edit', compact('locationtypemanage'));
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

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        
        if($validator->fails())
        {
            return redirect()->route('admin.location.type.edit', $id)
                        ->with([
                            'errors' => $validator->messages()
                        ])
                        ->withInput();
        }
        else
        {
            try {
                DB::beginTransaction();

                $locationtypemanage = LocationTypeManages::findOrFail($id);
                $locationtypemanage->update($requestData);

                if(sizeof($locationtypemanage) > 0)
                {
                    DB::commit();
                    return redirect()->route('admin.location.type.index')
                            ->with([
                                'success.title'   => 'Done!.',
                                'success.message' => 'Location type has been successfully updated!.'
                            ]);
                }
                else
                {
                    DB::rollback();
                    return redirect()->route('admin.location.type.index')
                            ->with([
                                'error.title'   => 'Error!.',
                                'error.message' => 'Something went wrong!.'
                            ]);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->route('admin.location.type.index')
                            ->with([
                                'error.title'   => 'Error!.',
                                'error.message' => $e->getMessage()
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
    public function destroy($id, Request $request)
    {
        $type_status = $request->input('type_status');

        if($type_status == 1)
        {
            $txt = 'enabled';
        }
        else if($type_status == 0)
        {
            $txt = 'disabled';
        }

        try{
            if(!empty($id))
            {
                DB::beginTransaction();
                $deleted = LocationTypeManages::where('id', $id)
                            ->update([
                                'status' => $type_status,
                                'updated_at' => date('Y-m-d h:i:s') 
                           ]); 

                if(sizeof($deleted) > 0)
                {
                    DB::commit();
                    return redirect()->route('admin.location.type.index')
                                ->with([
                                    'success.title'   => 'Done!.',
                                    'success.message' => 'Location Type has been successfully '.$txt.'!.'
                                ]);
                }   
                else
                {
                    DB::rollback();
                    return redirect()->route('admin.location.type.index')
                                ->with([
                                    'success.title'   => 'Error!.',
                                    'success.message' => 'Something went wrong!.'
                                ]);
                }
            }
            else
            {
                DB::rollback();
                return redirect()->route('admin.location.type.index')
                                ->with([
                                    'success.title'   => 'Error!.',
                                    'success.message' => 'ID cannot be empty!.'
                                ]);
            }
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.location.type.index')
                                ->with([
                                    'success.title'   => 'Error!.',
                                    'success.message' => $e->getMessage()
                                ]);
        }
    }
}
