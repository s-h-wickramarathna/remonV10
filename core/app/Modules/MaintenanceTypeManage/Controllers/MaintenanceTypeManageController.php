<?php namespace App\Modules\MaintenanceTypeManage\Controllers;
/**
* CONTROLLER
* @author Author <tharindup@orelit.com> [tharindu lakshan]
* @version 1.0.0
* @copyright Copyright (c) 2017, OITS.Dev+ [THARIDNU LAKSHAN] [godsEYE program]
*
*/
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\MaintenanceTypeManage\Models\MaintenanceType;
use Illuminate\Http\Request;
use Session;

class MaintenanceTypeManageController extends Controller
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
            $maintenancetypemanage = MaintenanceType::where('name', 'LIKE', "%$keyword%")
				->orWhere('content', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $maintenancetypemanage = MaintenanceType::paginate($perPage);
        }

        return view('MaintenanceTypeManage::maintenance-type-manage.index')->with([ 'maintenancetypemanage'=>$maintenancetypemanage]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('MaintenanceTypeManage::maintenance-type-manage.create');
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
        
        MaintenanceType::create($requestData);

        return redirect('admin/maintenance/type/add')->with([
                    'success'           => true,
                    'success.message'   => 'Employee has been successfully added!.',
                    'success.title'     => 'Success...!'
                ]);
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
        $maintenancetypemanage = MaintenanceType::find($id);
        return view('MaintenanceTypeManage::maintenance-type-manage.show', compact('maintenancetypemanage'));
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
        $maintenancetypemanage = MaintenanceType::find($id);
        return view('MaintenanceTypeManage::maintenance-type-manage.edit', compact('maintenancetypemanage'));
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
        
        $maintenancetypemanage = MaintenanceType::find($id);
        $maintenancetypemanage->update($requestData);

         return redirect('admin/maintenance/type/list')->with([
                    'success'           => true,
                    'success.message'   => 'Type has been successfully updated!.',
                    'success.title'     => 'Success...!'
                ]);
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
        MaintenanceType::destroy($id);

         return redirect('admin/maintenance/type/list')->with([
                    'success'           => true,
                    'success.message'   => 'Type has been successfully deleted!.',
                    'success.title'     => 'Success...!'
                ]);

    }
}
