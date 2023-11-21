<?php
namespace App\Classes;

/**
 *
 * Dynamic Menu Generation
 *
 * @author Yasith Samarawickrama <yazith11@gmail.com>
 * @version 1.0.0
 * @copyright Copyright (c) 2015, Yasith Samarawickrama
 *
 */

use Application\CustomerManage\Models\Customer;
use Application\InvoiceManage\Models\Invoice;
use Application\LocationManage\Models\EmployeeLocationDetails;
use Application\LocationManage\Models\Location;
use Application\OutletListManagement\Models\Outlet;
use Core\MenuManage\Models\Menu;
use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\Log;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Application\EmployeeManage\Models\Employee;
use Illuminate\Support\Facades\DB;

class Common
{

    static function getDistributorsAuthWise($user)
    {
        $distributors = [];
        if ($user->id == 1) {
            //SUPER ADMIN
            $distributors = Employee::where('employee_type_id', 4)->get();
        } else {
            if ($user->roles[0]->id == 2) {
                //DISTRIBUTOR
                $distributors = Employee::where('id', $user->employee_id)->get();
            } elseif (($user->roles[0]->id > 3)) {
                //ASM RSM
                $distributors = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', 4)->get();
            } elseif (($user->roles[0]->id == 3)) {
                //ADMIN
                $distributors = Employee::where('employee_type_id', 4)->get();
            } else {
                $distributors = [];
            }
        }

        return $distributors;
    }

    //Common::getRepsAuthWise(Sentinel::getUser()) to get the array
    //Common::getRepsAuthWise(Sentinel::getUser())->pluck('id') for whereIn funtions
    static function getRepsAuthWise($user)
    {
        $reps = [];
        if ($user->id == 1) {
            //SUPER ADMIN
            $reps = Employee::where('employee_type_id', 5)->get();
        } else {
            if ($user->roles[0]->id == 2) {
                //DISTRIBUTOR
                $reps = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', 5)->get();
            } elseif (($user->roles[0]->id > 3)) {
                //ASM RSM
                $reps = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', 5)->get();
            } elseif (($user->roles[0]->id == 3)) {
                //ADMIN
                $reps = Employee::where('employee_type_id', 5)->get();
            } else {
                $reps = [];
            }
        }

        return $reps;
    }

    static function getAuthWise($user,$type)
    {
        $reps = [];
        if ($user->id == 1) {
            //SUPER ADMIN
            $reps = Employee::where('employee_type_id', $type)->get();
        } else {
            if ($user->roles[0]->id == 2) {
                //DISTRIBUTOR
                $reps = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', $type)->get();
            } elseif (($user->roles[0]->id > 3)) {
                //ASM RSM
                $reps = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', $type)->get();
            } elseif (($user->roles[0]->id == 3)) {
                //ADMIN
                $reps = Employee::where('employee_type_id', $type)->get();
            } else {
                $reps = [];
            }
        }

        return $reps;
    }

    static function getOutletsAuthWise($user)
    {
        $outlets = [];
        if ($user == null || $user->id == 1) {
            //SUPER ADMIN
            $outlets = Outlet::select('4ever_outlet.*')->join('4ever_location as l', 'l.id', '=', '4ever_location_id')
                ->where('l.location_type_id', 6)->get();
        } else {
            if ($user->roles[0]->id == 2 || $user->roles[0]->id > 3) {
                //DISTRIBUTOR & ASM & RSM
                $distributor = static::getDistributorsIds($user);
                $outlets = Outlet::join('4ever_location as l', 'l.id', '=', '4ever_location_id')
                    ->whereIn('l.parent', EmployeeLocationDetails::
                    select(
                        'location_id'
                    )
                        ->whereIn
                        ('employee_id', $distributor)
                        ->get())
                    ->get();
            } elseif (($user->roles[0]->id == 3)) {
                //ADMIN
                $outlets = Outlet::select('4ever_outlet.*')->join('4ever_location as l', 'l.id', '=', '4ever_location_id')
                    ->where('l.location_type_id', 6)->get();
            } else {
                $outlets = [];
            }
        }
        return $outlets;
    }

    static function getDistributorsIds($user)
    {
        $distributors = [];
        if ($user->id == 1) {
            //SUPER ADMIN
            $distributors = Employee::where('employee_type_id', 4)->get()->pluck('id');
        } else {
            if ($user->roles[0]->id == 2) {
                //DISTRIBUTOR
                $distributors = Employee::where('id', $user->employee_id)->get()->pluck('id');
            } elseif (($user->roles[0]->id > 3)) {
                //ASM RSM
                $distributors = Employee::find($user->employee_id)->descendantsAndSelf()->where('employee_type_id', 4)->get()->pluck('id');
            } elseif (($user->roles[0]->id == 3)) {
                //ADMIN
                $distributors = Employee::where('employee_type_id', 4)->get()->pluck('id');
            } else {
                $distributors = [];
            }
        }

        return $distributors;
    }

    static function getLocationsAuthWise($user, $size, $skip)
    {
        $outlets = [];
        if ($user->id == 1) {
            //SUPER ADMIN
            $outlets = Location::take($size)->skip($skip)->get();
        } else {
            if ($user->roles[0]->id == 2 || $user->roles[0]->id > 3) {
                //DISTRIBUTOR & ASM & RSM
                $regons = static::getDistributorRegion($user);
                foreach ($regons as $regon) {
                    return Location::whereIn('id', Location::find($regon->id)->getDescendantsAndSelf()->pluck('id'))
                        ->take($size)->skip($skip)->get();
                }
            } elseif (($user->roles[0]->id == 3)) {
                //ADMIN
                $outlets = Location::take($size)->skip($skip)->get();
                Log::info($skip);
            } else {
                $outlets = [];
            }
        }
        return $outlets;
    }

    static function  getRepOutlets($emp)
    {
        return Location::whereIn('parent',
            EmployeeLocationDetails::join('4ever_location as l', 'l.id', '=', 'location_id')
                ->select(
                    'l.id'
                )
                ->whereIn
                ('employee_id', (Employee::select('parent')
                    ->where('id', $emp)
                    ->first()))
                ->get())
            ->get()->pluck('id');
    }

    static function  getOutletInvoices($empId)
    {
        $outletList = static::getRepOutlets($empId);
        return Invoice::whereIn('location_id', $outletList)->pluck('id');
    }

    static function getRepOutletsWithInvoicesSum($empId){

        return DB::select('SELECT
                fo.outlet_name,
                fo.outlet_address,
                fl.id AS location_id,
                IFNULL(tmp_inv.amount, 0) AS invoice_amount
            FROM
                4ever_outlet fo
            INNER JOIN 4ever_location fl ON fl.id = fo.4ever_location_id
            LEFT JOIN (
                SELECT
                    IFNULL(
                        (
                            SUM(tmp.total) - SUM(tmp.discount)
                        ),
                        0
                    ) AS amount,
                    tmp.location_id
                FROM
                    (
                        SELECT
                            SUM(fid.qty * fid.unit_price) AS total, SUM(fid.id) as sss
                            IFNULL(
                                (
                                    SELECT
                                        SUM(fidd.discount)
                                    FROM
                                        4ever_invoice_discount fidd
                                    WHERE
                                        fidd.invoice_id = fi.id
                                ),
                                0
                            ) AS discount,
                            fi.location_id AS location_id
                        FROM
                            4ever_invoice_detail fid
                        INNER JOIN 4ever_invoice fi ON fi.id = fid.invoice_id
                        GROUP BY
                            fi.id
                    ) tmp
                GROUP BY
                    tmp.location_id
            ) tmp_inv ON tmp_inv.location_id = fl.id
            WHERE
                fl.deleted_at IS NULL
            AND fl.parent IN (
                SELECT
                    pl.id
                FROM
                    4ever_location pl
                INNER JOIN 4ever_employee_location_detail fmld ON pl.id = fmld.location_id
                WHERE
                    fmld.employee_id = (
                        SELECT
                            emp.parent
                        FROM
                            4ever_employee emp
                        WHERE
                            emp.id = '.$empId.'
                    )
            )');
    }

    static function getDistributorRegion($user)
    {
        $distributor = static::getDistributorsIds($user);
        return Location::whereIn('id', Location::whereIn('id',
            Location::whereIn('id',
                Location::
                whereIn('id', EmployeeLocationDetails::
                select(
                    'location_id'
                )
                    ->whereIn
                    ('employee_id', $distributor)
                    ->get())
                    ->get()->pluck('parent'))
                ->get()->pluck('parent'))
            ->get()->pluck('parent'))->get();
    }

    static function getRouteAuthWise($user){
        $routes = [];
        //return $user->roles[0];
        if ($user->id == 1) {
            //SUPER ADMIN
            $routes = Location::where('location_type_id',5)->get();
        } else {
            if ($user->roles[0]->id == 2 || $user->roles[0]->id == 4 || $user->roles[0]->id == 5) {
                //DISTRIBUTOR & ASM & RSM
                $outlets = static::getOutletsAuthWise($user);
                foreach ($outlets as $outlet) {
                    $route = Location::where('id',$outlet->parent)->first();
                    if (in_array($route, $routes)) {
                        continue;
                    }
                    $routes [] = $route;
                }
            } elseif ($user->roles[0]->id == 3 || $user->roles[0]->id == 6 || $user->roles[0]->id == 8) {
                //ADMIN
                $routes = Location::where('location_type_id',5)->get();
            } else {
                $routes = [];
            }
        }
        return $routes;
    }

    static function getOutletsEmployeeWise($userId)
    {
        if($userId > 0) {
            $outlets = Outlet::join('4ever_location as l', 'l.id', '=', '4ever_location_id')
                ->whereIn('l.parent', EmployeeLocationDetails::
                where
                ('employee_id', $userId)
                    ->pluck('location_id'))
                ->get();
        }else{
            $outlets = Outlet::select('4ever_outlet.*')->join('4ever_location as l', 'l.id', '=', '4ever_location_id')
                ->where('l.location_type_id', 6)->get();
        }
        return $outlets;
    }

    static function  getOutlets($emp)
    {
        return Location::whereIn('parent',
            EmployeeLocationDetails::join('4ever_location as l', 'l.id', '=', 'location_id')
                ->select(
                    'l.id'
                )
                ->whereIn
                ('employee_id', (Employee::select('parent')
                    ->where('id', $emp)
                    ->first()))
                ->get())
            ->get()->pluck('id');
    }

    static function  getInvoices($empId)
    {
        $outletList = static::getRepOutlets($empId);
        return Invoice::whereIn('location_id', $outletList)->get();
    }

    static function getDistributorOfOutlet($outletLocationId){
        return Employee::whereIn('id', EmployeeLocationDetails::select(
            'employee_id'
        )
            ->whereIn
            ('location_id', (Location::select('parent')->where('id',$outletLocationId)->first()))
            ->get())->first();
    }

    static function getCustomer($marketeerId){
        return Customer::where('marketeer_id',$marketeerId)->get();
    }


}
