<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/16/2015
 * Time: 1:44 PM
 */

namespace Application\JobManage\Http\Controllers;


use App\Classes\NestJob;
use App\Classes\PdfTemplate;
use App\Http\Controllers\Controller;
use App\Models\Font;
use Application\AlbumBox\Models\AlbumBox;
use Application\AlbumCover\Models\AlbumCover;
use Application\AlbumType\Models\AlbumType;
use Application\CustomerManage\Models\Customer;
use Application\EmployeeManage\Models\EmployeeType;
use Application\EmployeeManage\Models\Rep;
use Application\InvoiceManage\Models\Invoice;
use Application\JobManage\Http\Requests\JobLevelRequests;
use Application\JobManage\Models\Job;
use Application\JobManage\Models\JobAlbum;
use Application\JobManage\Models\JobAllocation;
use Application\JobManage\Models\JobBox;
use Application\JobManage\Models\JobCover;
use Application\JobManage\Models\JobData;
use Application\JobManage\Models\JobLamination;
use Application\JobManage\Models\JobLevelConfig;
use Application\JobManage\Models\JobMasterData;
use Application\JobManage\Models\JobNew;
use Application\JobManage\Models\JobPaperType;
use Application\JobManage\Models\JobPrinter;
use Application\LaminateType\Models\LaminateType;
use Application\PaperType\Models\AlbumPaperType;
use Application\TargetManage\Http\Requests\TargetRequest;
use Application\TargetManage\Models\MarketeerTarget;
use Chumper\Zipper\Facades\Zipper;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Core\MenuManage\Models\Menu;
use Illuminate\Http\Request;
use Application\EmployeeManage\Models\Employee;
use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Excel;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Response;

use App\Classes\Common;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;
use Illuminate\Support\Facades\DB;

use function foo\func;


class JobController extends Controller
{


    public function addView()
    {
        $album_type = AlbumType::where('active_status', 1)->get();
        $lamination_type = LaminateType::where('active_status', 1)->get();
        $album_cover = AlbumCover::where('active_status', 1)->get();
        $box = AlbumBox::where('active_status', 1)->get();
        $paper_type = AlbumPaperType::where('active_status', 1)->get();
        $customers = Customer::all();
        $last_job = Job::orderBy('id', 'DESC')->limit(1)->get();
        $job_no = sizeof($last_job) > 0 ? $this->generateNo($last_job[0]->job_no) : $this->generateNo(0);
        return view('jobManage::add')->with(['album_types' => $album_type, 'lamination_types' => $lamination_type, 'album_covers' => $album_cover, 'boxes' => $box, 'customers' => $customers, 'job_no' => $job_no, 'paper_types' => $paper_type]);
    }

    public function add(Request $request)
    {
        $is_exist = Job::where('job_no', $request->get('job_no'))->get();
        if (sizeof($is_exist) > 0 || !$request->get('job_no')) {
            return redirect()->back()->withInput()->with([
                'error' => true,
                'error.message' => 'Job No already exist..',
                'error.title' => 'Failed...!'
            ]);
        }

        if ($request->get('customer_name') == 0) {
            return redirect()->back()->withInput()->with([
                'error' => true,
                'error.message' => 'Please select the customer..',
                'error.title' => 'Failed...!'
            ]);
        }

        $album = '';
        $paper = array();
        $create_by = Sentinel::getUser();

        $job = Job::create([
            'customer_id' => $request->get('customer_name'),
            'album' => $album,
            //           'type' => $request->get('radio_type'), // 1
            'count' => $request->get('page_count'),
            'extra_page' => $request->get('extra_pages'),
            'size' => $request->get('size'),
            // 'cover' => $request->get('radio_cover'), // 2
            // 'box' => $request->get('radio_box'), // 3
            'discount' => $request->get('discount'),
            'couple_name' => $request->get('couple_name'),
            'job_no' => $request->get('job_no'),
            'remark' => nl2br($request->get('remark')),
            'due_date' => $request->get('due_date'),
            'create_by' => $create_by->employee_id,
            'status' => 0
        ]);

        $id = $job->id;

        if ($request->radio_type) {
            foreach ($request->radio_type as $type) {
                $album_type = JobAlbum::create([
                    'job_id' => $id,
                    'album_type' => $type
                ]);
            }
        }

        if ($request->radio_cover) {
            foreach ($request->radio_cover as $type) {
                $album_type = JobCover::create([
                    'job_id' => $id,
                    'cover_id' => $type
                ]);
            }
        }

        if ($request->radio_box) {
            foreach ($request->radio_box as $type) {
                $album_type = JobBox::create([
                    'job_id' => $id,
                    'box_id' => $type
                ]);
            }
        }

        if ($request->radio_lamination_type) {
            foreach ($request->radio_lamination_type as $type) {
                $lamiantion = JobLamination::create([
                    'job_id' => $id,
                    'lamination_id' => $type
                ]);
            }
        }

        if ($request->radio_paper_type) {
            foreach ($request->radio_paper_type as $type) {
                JobPaperType::create([
                    'job_id' => $id,
                    'paper_type' => $type
                ]);
            }
        }

        if ($job) {

            $file = $request->file('attachment');
            if (is_file($file)) {
                $extension = $file->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension;
                $thumbnailImage = Image::make($file);
                $originalImage = Image::make($file);
                $thumbnailPath = 'storage/images/job/';
                $thumbnailImage->resize(775, 645);
                $thumbnailImage->save($thumbnailPath . $name);
                $originalImage->save($thumbnailPath . 'original/' . $name);


                $job->img_url = $thumbnailPath . $name;
                $job->original_img = $thumbnailPath . 'original/' . $name;
                $job->save();
            }

            return redirect('job/level/' . $job->id);
        }
        return redirect()->back()->with([
            'error' => true,
            'error.message' => 'Job add failed..',
            'error.title' => 'Failed...!'
        ]);
    }


    /**
     * Show the menu list screen to the user.
     *
     * @return Response
     */
    public function listView()
    {
        $log_user = Sentinel::getUser();
        $customerList = Customer::all();
        if ($log_user->username != 'super.admin') {
            // dd('first_if');
            if ($log_user->hasAnyAccess(['job.add'])) {
                // dd('permission');
                if ($log_user->roles[0]->name == 'marketer') {
                    dd('second_if');
                    $marketeerList = Employee::where('id', $log_user->employee_id)->get();
                    $invoiceList = JobNew::select('job_new.*', DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = job_new.status) as confirm_type '), DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1)) as section '))
                        ->with('customer.marketeer', 'employee', 'invoice')
                        ->whereRaw('(customer_id IN (SELECT id FROM remon_customer WHERE remon_customer.marketeer_id = ' . $log_user->employee_id . ') OR  create_by = ' . $log_user->employee_id . ')')
                        ->whereNull('ended_at')
                        ->groupBy('job_new.id')
                        ->orderBy('updated_at', 'DESC')
                        ->paginate(12);
                } else {
                    //   dd('third_if');
                    $marketeerList = Employee::where('employee_type_id', 2)->get();
                    // dd($marketeerList);

                    // $invoiceList = JobNew::select('job_new.*', 
                    // DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = job_new.status limit) as confirm_type '), 
                    // DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1)) as section '))
                    //     ->with(['customer.marketeer', 'employee', 'invoice'])
                    //     ->groupBy('job_new.id')
                    //     ->orderBy('updated_at', 'DESC')
                    //     ->whereNull('ended_at')
                    //     ->paginate(12);

                        $invoiceList = JobNew::select('job_new.*')
                        ->addSelect(DB::raw('jt1.type AS confirm_type'))
                        ->addSelect(DB::raw('jt2.type AS section'))
                        ->with(['customer.marketeer', 'employee', 'invoice'])
                        ->leftJoin('job_level_config AS jt1', function ($join) {
                            $join->on('job_new.id', '=', 'jt1.job_id')
                                ->where('jt1.job_link', '=', DB::raw('job_new.status'));
                        })
                        ->leftJoin('job_level_config AS jt2', function ($join) {
                            $join->on('job_new.id', '=', 'jt2.job_id')
                                ->where('jt2.job_link', '=', DB::raw('job_new.status + 1'));
                        })
                        ->groupBy('job_new.id')
                        ->orderBy('updated_at', 'DESC')
                        ->whereNull('ended_at')
                        ->paginate(12);
                    
                    // dd($invoiceList);
                }
            } elseif (trim($log_user->roles[0]->name) == 'customer') {
                dd('forth_if');
                $customerList = Customer::where('user_id', $log_user->id)->get();
                $marketeerList = [];
                $customer = Customer::where('user_id', $log_user->id)->first();
                $invoiceList = JobNew::selectRaw('job_new.*', DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = job_new.status) as confirm_type '), '(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1)) as section ')
                    ->with('customer.marketeer', 'employee', 'invoice')
                    //->whereRaw('(select jc.type from job_level_config as jc where jc.job_id = job.id and jc.job_link =  (IFNULL(job_level_config.job_link,0) + 1)) = "' . $marketeerList[0]->type->type . '"')
                    //->where('job_level_config.job_link','=','job.status')
                    //->where('job_level_config.type', $marketeerList[0]->type->type)
                    ->where('customer_id', $customer->id)
                    ->groupBy('job_new.id')
                    ->orderBy('job_new.updated_at', 'DESC')
                    ->paginate(12);
            } else {
                // dd('sixth_if');
                $marketeerList = Employee::with('type')->where('id', $log_user->employee_id)->get();
                // dd($marketeerList);

                // unused query :-
                // $invoiceList = JobNew::select('job_new.*', DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = job_new.status) as confirm_type '), DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1)) as section '))
                //     ->with(['customer.marketeer', 'employee', 'invoice'])
                //     ->groupBy('job_new.id')
                //     ->orderBy('updated_at', 'DESC')
                //     ->whereNull('ended_at')
                //     ->paginate(12);

                    $invoiceList = JobNew::select('job_new.*')
                        ->addSelect(DB::raw('jt1.type AS confirm_type'))
                        ->addSelect(DB::raw('jt2.type AS section'))
                        ->with(['customer.marketeer', 'employee', 'invoice'])
                        ->leftJoin('job_level_config AS jt1', function ($join) {
                            $join->on('job_new.id', '=', 'jt1.job_id')
                                ->where('jt1.job_link', '=', DB::raw('job_new.status'));
                        })
                        ->leftJoin('job_level_config AS jt2', function ($join) {
                            $join->on('job_new.id', '=', 'jt2.job_id')
                                ->where('jt2.job_link', '=', DB::raw('job_new.status + 1'));
                        })
                        ->groupBy('job_new.id')
                        ->orderBy('updated_at', 'DESC')
                        ->whereNull('ended_at')
                        ->paginate(12);
            }
            // dd('seventh_if');
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $invoiceList = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation')->orderBy('updated_at', 'DESC')->paginate(12);
            //$invoiceList = Invoice::with('employee')->orderBy('created_at', 'DESC')->paginate(20);
        }
        //return $invoiceList;

        if (sizeof($log_user->roles) > 0) {
            if ($log_user->roles[0]->slug == 'laminating' || $log_user->roles[0]->slug == 'binding') {


                return view('jobManage::list')->with(['orders' => $invoiceList, 'invoice_no' => '', 'marketeerList' => $marketeerList, 'marketeer' => '', 'from' => '', 'to' => '', 'status' => 0, 'customerList' => $customerList, 'customer' => '']);
            }
        }
        return view('jobManage::new-list')->with(['orders' => $invoiceList, 'invoice_no' => '', 'marketeerList' => $marketeerList, 'marketeer' => '', 'from' => '', 'to' => '', 'status' => 0, 'customerList' => $customerList, 'customer' => '']);
        // return view('jobManage::new-list')->with(['orders' => '$invoiceList', 'invoice_no' => '', 'marketeerList' => '$marketeerList', 'marketeer' => '', 'from' => '', 'to' => '', 'status' => 0, 'customerList' => ['name'], 'customer' => '']);
        
    }

    public function jobReport(Request $request)
    {
        $log_user = Sentinel::getUser();
        $customerList = Customer::all();
        if ($log_user->username != 'super.admin') {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $invoiceList = JobNew::select('job_new.*', DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = job_new.status LIMIT 1) as confirm_type '), DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1) LIMIT 1) as section '))
                ->with('customer.marketeer', 'employee', 'invoice', 'confirmation');
            if ($request->get('from')) {
                $invoiceList = $invoiceList->whereRaw('DATE(due_date) = "' . $request->get('from') . '"');
            } else {
                $invoiceList = $invoiceList->whereRaw('DATE(due_date) = "' . date('Y-m-d') . '"');
            }

            if (strlen($request->get('invoice_no')) > 0) {
                $invoiceList = $invoiceList->where('job_no', $request->get('invoice_no'));
            }

            if ($request->get('customer') > 0) {
                $invoiceList = $invoiceList->where('customer_id', $request->get('customer'));
            }

            if ($request->get('marketeer')) {
                $invoiceList = $invoiceList->whereRaw('(customer_id IN (SELECT id FROM remon_customer WHERE remon_customer.marketeer_id = ' . $request->get('marketeer') . ') OR  create_by = ' . $request->get('marketeer') . ')');
            }

            $invoiceList = $invoiceList->groupBy('job_new.id')
                ->orderBy('created_at', 'DESC')
                ->paginate(12);
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $invoiceList = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation')->orderBy('created_at', 'DESC')->paginate(12);
            //$invoiceList = Invoice::with('employee')->orderBy('created_at', 'DESC')->paginate(20);
        }

        return view('jobManage::job-report', ['orders' => $invoiceList->appends($request->except('page'))])->with(['orders' => $invoiceList, 'marketeerList' => $marketeerList, 'invoice_no' => $request->get('invoice_no'), 'marketeer' => $request->get('marketeer'), 'from' => $request->get('from'), 'status' => $request->get('status'), 'customerList' => $customerList, 'customer' => $request->get('customer')]);
    }

    /**
     * Show the menu list screen to the user.
     *
     * @return Response
     */
    public function reportView()
    {
        $customerList = Customer::all();
        $marketeerList = Employee::where('employee_type_id', 2)->get();
        $invoiceList = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation')->orderBy('created_at', 'DESC')->paginate(12);

        return view('jobManage::report')->with(['orders' => $invoiceList, 'invoice_no' => '', 'marketeerList' => $marketeerList, 'marketeer' => '', 'from' => '', 'to' => '', 'status' => 0, 'customerList' => $customerList, 'customer' => '']);
    }


    public function search(Request $request)
    {

        $log_user = Sentinel::getUser();
        $customerList = Customer::all();

        if ($log_user->username != 'super.admin') {
            if ($log_user->hasAnyAccess(['job.add'])) {
                if (trim($log_user->roles[0]->name) == 'marketer') {
                    $marketeerList = Employee::where('id', $log_user->employee_id)->get();
                    $orders = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation')->whereNull('ended_at')->where('status', (($marketeerList[0]->type->job_link) - 1));
                } else {
                    $marketeerList = Employee::where('employee_type_id', 2)->get();
                    $orders = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation');
                }
            } else {
                $marketeerList = Employee::with('type')->where('id', $log_user->employee_id)->get();
                $orders = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation')->where('status', (($marketeerList[0]->type->job_link) - 1))->whereNull('ended_at');
            }
        } else {
            $marketeerList = Employee::where('employee_type_id', 2)->get();
            $orders = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation');
            //$invoiceList = Invoice::with('employee')->orderBy('created_at', 'DESC')->paginate(20);
        }

        if (strlen($request->get('invoice_no')) > 0) {
            $orders = $orders->where('job_no', $request->get('invoice_no'));
        }

        if ($request->get('customer') > 0) {
            $orders = $orders->where('customer_id', $request->get('customer'));
        }
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereBetween('created_at', [$from, $to]);
        }

        //return $request->get('marketeer');

        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('(customer_id IN (SELECT id FROM remon_customer WHERE remon_customer.marketeer_id = ' . $request->get('marketeer') . ') OR  create_by = ' . $request->get('marketeer') . ')');
        }

        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->paginate(12);

        if ($log_user->roles[0]->slug == 'laminating' || $log_user->roles[0]->slug == 'binding') {

            return view('jobManage::list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'invoice_no' => $request->get('invoice_no'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'status' => $request->get('status'), 'customerList' => $customerList, 'customer' => $request->get('customer')]);
        }
        return view('jobManage::new-list', ['orders' => $orders->appends($request->except('page'))])->with(['orders' => $orders, 'marketeerList' => $marketeerList, 'marketeer' => $request->get('marketeer'), 'invoice_no' => $request->get('invoice_no'), 'from' => $request->get('from'), 'to' => $request->get('to'), 'status' => $request->get('status'), 'customerList' => $customerList, 'customer' => $request->get('customer')]);
    }

    public function download(Request $request)
    {

        $orders = JobNew::with('customer.marketeer', 'employee', 'invoice', 'confirmation');

        if (strlen($request->get('invoice_no')) > 0) {
            $orders = $orders->where('job_no', $request->get('invoice_no'));
            $no = $request->get('invoice_no');
        } else {
            $no = 'All';
        }

        $cus_all = '';
        if ($request->get('customer') > 0) {
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
            $orders = $orders->where('customer_id', $request->get('customer'));
        } else {
            $customer = 'All';
        }

        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            } else {
                $to = date('Y-m-d');
            }
            $orders = $orders->whereBetween('created_at', [$from, $to]);
        } else {
            $from = 'All';
        }


        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('(customer_id IN (SELECT id FROM remon_customer WHERE remon_customer.marketeer_id = ' . $request->get('marketeer') . ') OR  create_by = ' . $request->get('marketeer') . ')');
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->get();

        //return $orders;

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];

        if ($orders) {
            $page1 = view('jobManage::print.job-aging')->with(['orders' => $orders, 'page_header' => $header]);
        } else {
            return response()->view("errors.404");
        }

        $pdf = new PdfTemplate('P', 'mm', 'A4');
        $pdf->SetMargins(28.35, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page1);
        $pdf->output("recipt.pdf", 'I');

        return redirect()->back();
    }

    public function jobReportPrint(Request $request)
    {


        $orders = JobNew::select('job_new.*', DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = job_new.status LIMIT 1) as confirm_type '), DB::raw('(SELECT type FROM `job_level_config` WHERE job_id = job_new.id AND job_link = (job_new.status + 1) LIMIT 1) as section '))
            ->with('customer.marketeer', 'employee', 'invoice', 'confirmation');

        if (strlen($request->get('invoice_no')) > 0) {
            $orders = $orders->where('job_no', $request->get('invoice_no'));
            $no = $request->get('invoice_no');
        } else {
            $no = 'All';
        }

        $cus_all = '';
        if ($request->get('customer') > 0) {
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
            $orders = $orders->where('customer_id', $request->get('customer'));
        } else {
            $customer = 'All';
        }

        if ($request->get('from')) {
            $from = $request->get('from');
            $orders = $orders->whereRaw('DATE(due_date) = "' . $request->get('from') . '"');
        } else {
            $from = 'All';
            $orders = $orders->whereRaw('DATE(due_date) = "' . date('Y-m-d') . '"');
        }


        if ($request->get('marketeer')) {
            $orders = $orders->whereRaw('(customer_id IN (SELECT id FROM remon_customer WHERE remon_customer.marketeer_id = ' . $request->get('marketeer') . ') OR  create_by = ' . $request->get('marketeer') . ')');
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        $orders = $orders->orderBy('created_at', 'DESC');
        $orders = $orders->get();

        //return $orders;

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'aging_date' => date('Y-m-d'), 'cus_all' => $cus_all, 'no' => $no];
        if ($request->get('action') == 1) {



            if ($orders) {
                $page1 = view('jobManage::print.pending-report')->with(['orders' => $orders, 'page_header' => $header]);
            } else {
                return response()->view("errors.404");
            }

            $pdf = new PdfTemplate('P', 'mm', 'A4');
            $pdf->SetMargins(28.35, 10);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetAutoPageBreak(TRUE, 20);
            $pdf->AddPage();
            $pdf->writeHtml($page1);
            $pdf->output("recipt.pdf", 'I');
        } else {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s'); //
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/job_aging.xls'), function ($excel) use ($orders, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('D' . 2, $header['from']);

                foreach ($orders as $order) {

                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $order->job_no);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $order->customer->f_name . ' ' . $order->customer->l_name);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $order->customer->marketeer->first_name . ' ' . $order->customer->marketeer->last_name);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $order->created_at);
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $order->due_date);
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, $order->section);
                    $tbl_column++;
                    $index++;
                }
            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        }

        return redirect()->back();
    }

    /**
     * Show the edit target to the user.
     * @param type id
     * @return Response
     */
    public function editView($id)
    {
        $target = MarketeerTarget::find($id);
        $repList = Employee::where('employee_type_id', $id)->get()->pluck('full_name', 'id');
        return view('targetManage::edit')->with(['repList' => $repList, 'target' => $target]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    /*public function edit(Request $request, $id)
    {
        $date = $request->get('dateRange');
        $newdate = date_create($date);
        $startdate = date_format($newdate, "Y-m-01");
        $finishdate = date_format($newdate, "Y-m-31");
        $target = MarketeerTarget::find($id);
        $target->employee_id = $request->get('rep');
        $target->value = $request->get('value');
        $target->from = $startdate;
        $target->to = $finishdate;
        $target->save();
        return redirect()->back()->with(['success' => true,
            'success.message' => 'Target updated successfully!',
            'success.title' => 'Success...!']);
    }*/

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function processing($id)
    {
        $invoice = Invoice::find($id);
        $invoice->status = 2;
        $invoice->save();
        return redirect()->back()->with([
            'success' => true,
            'success.message' => 'Invoice ' . $invoice->manual_id . ' is processing!',
            'success.title' => 'Success...!'
        ]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function confirm($id, $user_id = 0)
    {
        // return $id;
        $job = JobNew::find($id);

        $user = Sentinel::getUser();
        $emp_id = $user->employee_id;

        if ($job->user_status == 0) {

            $siblings = Employee::where('employee_type_id', function ($query) use ($emp_id) {
                $query->select('employee_type_id')->from('employee')->where('id', $emp_id);
            })->get();

            foreach ($siblings as $emp) {
                $job_ = JobAllocation::where('job_id', $job->id)->where('employee_id', $emp->id)->get();
                if (sizeof($job_) > 0) {
                    return redirect()->back()->with([
                        'info' => true,
                        'info.message' => 'Job was already allocated',
                        'info.title' => 'Information...!'
                    ]);
                }
            }

            JobAllocation::create([
                'employee_id' => $user->employee_id,
                'job_id' => $job->id,
                'start_time' => date('Y-m-d H:i:s')
            ]);

            $job->allocate_user = $user->employee_id;
            $level = DB::select('SELECT * FROM `job_level_config` WHERE `type` = "Colour" AND `job_id` = ' . $job->id);
            if (sizeof($level) > 0 && ($job->status + 1) ==  $level[0]->job_link) {
                $job->user_status = 1;
            } else {
                $job->increment('status');
            }
            $job->save();

            return redirect()->back()->with([
                'success' => true,
                'success.message' => 'Job ' . $job->job_no . ' is allocated!',
                'success.title' => 'Success...!'
            ]);
        } else {

            if ($emp_id != $job->allocate_user) {
                return redirect()->back()->with([
                    'info' => true,
                    'info.message' => 'Job was already allocated',
                    'info.title' => 'Information...!'
                ]);
            }

            $emp_type = JobLevelConfig::where('job_id', $id)->orderBy('job_link', 'DESC')->first();

            $job_ = JobAllocation::where('job_id', $id)->where('employee_id', $user->employee_id)->first();

            if (($job->status + 1) == $emp_type->job_link) {
                $job->ended_at = date('Y-m-d H:i:s');
                $job->save();
            }

            if ($job->status < $emp_type->job_link) {
                $job->increment('status');
            }

            if ($job) {
                $job->allocate_user = -1;
                $job->user_status = 0;
                $job->save();

                // $job_->end_time = date('Y-m-d H:i:s');
                // $job_->save();
            }
        }

        return redirect()->back()->with([
            'success' => true,
            'success.message' => 'Job ' . $job->job_no . ' is confirm!',
            'success.title' => 'Success...!'
        ]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function done($id)
    {
        $invoice = Invoice::find($id);
        $invoice->status = 3;
        $invoice->save();
        return redirect()->back()->with([
            'success' => true,
            'success.message' => 'Invoice ' . $invoice->manual_id . ' is done!',
            'success.title' => 'Success...!'
        ]);
    }

    /**
     * Add new type data to database
     *
     * @return Redirect to menu edit
     */
    public function delivered($id)
    {
        $invoice = Invoice::find($id);
        $invoice->status = 4;
        $invoice->save();
        return redirect()->back()->with([
            'success' => true,
            'success.message' => 'Invoice ' . $invoice->manual_id . ' is delivered!',
            'success.title' => 'Success...!'
        ]);
    }

    /**
     * Delete a Vehicle
     * @param  Request $request id
     * @return Json            json object with status of success or failure
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $target = RepTarget::find($id);
            if ($target) {
                $target->delete();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    private function generateNo($count)
    {
        $count += 1;
        return str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getData($id)
    {

        $customer = Customer::with('marketeer')->find($id);

        /* $marketeer_outstanding = DB::select("SELECT SUM(tt.invoice_due) as outstanding,COUNT(tt.invoice_due) FROM (SELECT
                                 (
                                     IFNULL(
                                         (
                                             SELECT
                                                 sum(
                                                     (ind.qty * ind.unit_price) - ifnull(ind.discount, 0)
                                                 )
                                             FROM
                                                 invoice_detail AS ind
                                             WHERE
                                                 ind.invoice_id = invoice.id
                                             GROUP BY
                                                 invoice.id
                                         ),
                                         0
                                     ) - ifnull(
                                         invoice_discount.discount,
                                         0
                                     )
                                 ) AS total,

                             IF (
                                 recipt_detail.id IS NULL,
                                 (
                                     IFNULL(
                                         (
                                             SELECT
                                                 sum(
                                                     (ind.qty * ind.unit_price) - ifnull(ind.discount, 0)
                                                 )
                                             FROM
                                                 invoice_detail AS ind
                                             WHERE
                                                 ind.invoice_id = invoice.id
                                             GROUP BY
                                                 invoice.id
                                         ),
                                         0
                                     ) - ifnull(
                                         invoice_discount.discount,
                                         0
                                     )
                                 ),
                                 (
                                     SELECT
                                         recipt_detail.invoice_due
                                     FROM
                                         recipt_detail
                                     WHERE
                                         recipt_detail.invoice_id = invoice.id
                                     ORDER BY
                                         recipt_detail.id DESC
                                     LIMIT 1
                                 )
                             ) AS invoice_due,
                              `recipt_detail`.`id`
                             FROM
                                 `invoice`
                             LEFT JOIN `recipt_detail` ON `recipt_detail`.`invoice_id` = `invoice`.`id`
                             LEFT JOIN `invoice_discount` ON `invoice_discount`.`invoice_id` = `invoice`.`id`
                             WHERE
                                 `invoice`.`deleted_at` IS NULL
                             AND rep_id = " . $customer->marketeer_id .
             " GROUP BY
                                 `invoice`.`id`
                             HAVING
                                 (
                                     recipt_detail.id IS NULL
                                     OR invoice_due > 0
                                 )
                             AND total > 0)as tt");*/


        $credit_limit = $customer->marketeer->credit_limit; // - $marketeer_outstanding[0]->outstanding;

        return array('customer' => $customer, 'outstanding' => $credit_limit);
    }

    public function toPrint($id)
    {
        $job = JobNew::with('customer.marketeer', 'employee')->find($id);
        $job_data = JobMasterData::where('depth', 0)->get();
        $job_album = JobAlbum::where('job_id', $id)->get();
        $page = view('jobManage::print.job')->with(['job' => $job, 'job_data' => $job_data, 'job_album' => $job_album]);
        $page_2 = view('jobManage::print.cover')->with(['job' => $job, 'job_data' => $job_data, 'job_album' => $job_album]);
        $pdf = new PdfTemplate('P', 'mm', 'A4');
        $pdf->SetMargins(28.35, 10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();
        $pdf->writeHtml($page);

        $pdf->writeHtml($page_2);
        $pdf->output("recipt.pdf", 'I');
    }

    public function levelView(Request $request, $id)
    {
        $added_level = JobLevelConfig::where('job_id', $id)->where('job_link', '>', 0)->orderBy('job_link')->get()->pluck('type');
        if (sizeof($added_level) == 0) {
            $added_level = ['Colour', 'Planning', 'Printing', 'Binding', 'Laminating', 'Cover', 'QA'];
        }
        $not_added = EmployeeType::whereNotIn('type', $added_level)->get()->pluck('type');
        $jobs = JobNew::get();
        return view('jobManage::new-level')->with(['not_added' => $not_added, 'added_levels' => $added_level, 'jobs' => $jobs, 'job_id' => $id]);
    }

    public function editLevel(JobLevelRequests $request)
    {

        //    return  $request->all();
        $added_level = JobLevelConfig::where('job_id', $request->job_id)->where('job_link', '>', 0)->orderBy('job_link')->get()->pluck('type');
        if (sizeof($added_level) == 0) {
            $added_level = ['Colour', 'Planning', 'Printing', 'Binding', 'Laminating', 'Cover', 'QA'];
        }
        $not_added = EmployeeType::whereNotIn('type', $added_level)->get()->pluck('type');

        /** pending jobs filter*/

        /*$jobs = Job::whereNull('ended_at')->where('status', '>', 0)->get();
        if (sizeof($jobs) > 0) {
            return redirect()->back()->with(['warning' => true,
                'warning.message' => 'All jobs must be finish...!',
                'warning.title' => "Can not change!"])->with(['emp_types' => $emp_types, 'added_levels' => $added_levels]);
        }*/

        /** pending jobs filter*/

        $jobs = Job::get();

        $levels = $request->level_order;

        DB::select('UPDATE `job_level_config` SET job_link = 0 where job_id = ' . $request->job_id);
        $index = 1;
        if (sizeof(explode(';', $levels)) == 1) {
            $data = array(
                array(
                    'job_id' => $request->job_id,
                    'type' => 'Colour',
                    'job_link' => 1,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),
                array(
                    'job_id' => $request->job_id,
                    'type' => 'Planning',
                    'job_link' => 2,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),
                array(
                    'job_id' => $request->job_id,
                    'type' => 'Printing',
                    'job_link' => 3,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),
                array(
                    'job_id' => $request->job_id,
                    'type' => 'Binding',
                    'job_link' => 4,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),
                array(
                    'job_id' => $request->job_id,
                    'type' => 'Laminating',
                    'job_link' => 5,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),
                array(
                    'job_id' => $request->job_id,
                    'type' => 'Cover',
                    'job_link' => 6,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),
                array(
                    'job_id' => $request->job_id,
                    'type' => 'QA',
                    'job_link' => 7,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                    'status' => 1
                ),

                //...
            );
            JobLevelConfig::insert($data);
        } else {
            foreach (explode(';', $levels) as $level) {

                if (strlen($level) > 1) {

                    $added_level = JobLevelConfig::where('job_id', $request->job_id)->where('type', $level)->first();
                    // Log::info($added_level);
                    if ($added_level) {
                        $added_level->job_link = $index;
                        $added_level->save();
                    } else {
                        // Log::info($level);
                        $res = JobLevelConfig::create([
                            'job_id' => $request->job_id,
                            'type' => $level,
                            'job_link' => $index,
                            'status' => 1
                        ]);
                        // Log::info($res);
                    }
                    ++$index;
                }
            }
        }

        return redirect('job/print/' . $request->job_id);
    }

    public function editJob(Request $request, $id)
    {
        $job = JobNew::with(['customer', 'data.master.super', 'printer', 'job_album'])->find($id);
        $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
        $customers = Customer::all();
        $last_job = JobNew::orderBy('id', 'DESC')->limit(1)->get();
        $job_data = JobMasterData::get()->toHierarchy();
        $job_no = sizeof($last_job) > 0 ? $this->generateNo($last_job[0]->job_no) : $this->generateNo(0);
        $album_1 = JobData::where('job_new_id', $job->id)->where('album_no', 1)->get()->pluck('job_master_data_id')->toArray();
        $album_2 = JobData::where('job_new_id', $job->id)->where('album_no', 2)->get()->pluck('job_master_data_id')->toArray();
        $album_3 = JobData::where('job_new_id', $job->id)->where('album_no', 3)->get()->pluck('job_master_data_id')->toArray();
        $album_4 = JobData::where('job_new_id', $job->id)->where('album_no', 4)->get()->pluck('job_master_data_id')->toArray();
        $album_5 = JobData::where('job_new_id', $job->id)->where('album_no', 5)->get()->pluck('job_master_data_id')->toArray();
        return view('jobManage::new-edit')->with(['job' => $job, 'album_types' => $album_type, 'customers' => $customers, 'job_no' => $job_no, 'job_data' => $job_data, 'album_1' => $album_1, 'album_2' => $album_2, 'album_3' => $album_3, 'album_4' => $album_4, 'album_5' => $album_5]);
    }

    public function edit(Request $request, $id)
    {

        if ($request->get('customer_name') == 0) {
            return redirect()->back()->withInput()->with([
                'error' => true,
                'error.message' => 'Please select the customer..',
                'error.title' => 'Failed...!'
            ]);
        }

        $create_by = Sentinel::getUser();

        $job = JobNew::find($id);
        $job->customer_id = $request->get('customer_name');
        $job->delivery = $request->get('delivery');
        $job->couple_name = $request->get('couple_name');
        //$job->job_no = $request->get('job_no');
        $job->remark = nl2br($request->get('remark'));
        $job->due_date = $request->get('due_date');
        $job->create_by = $create_by->employee_id;
        $job->save();

        $id = $job->id;

        DB::select('DELETE FROM job_data WHERE job_new_id =' . $id);
        // DB::select(DB::raw('DELETE FROM job_album WHERE job_id ='.$id));

        $size = $request->radio_type[0];
        if ($request->radio_type) {
            for ($i = 1; $i <= 5; $i++) {
                foreach ($request->radio_type as $type) {
                    $album_type = JobData::create([
                        'job_new_id' => $id,
                        'job_master_data_id' => $type,
                        'album_no' => $i,
                    ]);
                }
            }
            $job->album = $request->radio_type[0];
        }

        $master_data = JobMasterData::where('depth', '=', 0)->get();

        foreach ($master_data as $data) {
            for ($i = 1; $i <= 5; $i++) {
                if ($request->get('data_' . $i . '_' . $data->id)) {
                    $job_data = $request->get('data_' . $i . '_' . $data->id);

                    $album_type = JobData::create([
                        'job_new_id' => $id,
                        'job_master_data_id' => $job_data,
                        'album_no' => $i,
                    ]);
                    /* if ($data->name == 'Album Size') {
                         $album = JobAlbum::where('job_id', $job->id)->where('album_id', $i)->first();
                         $album->album_no = $job_data;
                         $album->save();

                         //$job['qr_' . $i] = $job->job_no . '-' . $i . '-' . $album->id;
                     }*/
                }
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            $job_album = JobAlbum::where('job_id', $job->id)->where('album_id', $i)->first();
            if ($job_album) {
                $job_album->pages = $request->get('pages_' . $i);
                $job_album->save();
            }
        }

        $job->updated_at = date('Y-m-d H:i:s');
        $job->save();

        if ($job) {
            $file = $request->file('attachment');
            if (is_file($file)) {
                $extension = $file->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension;
                $thumbnailImage = Image::make($file);
                $originalImage = Image::make($file);
                $thumbnailPath = 'storage/images/job/';
                $thumbnailImage->resize(775, 645);
                $thumbnailImage->save($thumbnailPath . $name);
                $originalImage->save($thumbnailPath . 'original/' . $name);
                $job->album_size = $size;
                $job->img_url = $thumbnailPath . $name;
                $job->original_img = $thumbnailPath . 'original/' . $name;
            }

            $file_1 = $request->file('attachment_1');
            if (is_file($file_1)) {
                $extension_1 = $file_1->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_1;
                // $thumbnailImage_1 = Image::make($file_1);
                $originalImage_1 = Image::make($file_1);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_1->resize(775, 645);
                // $thumbnailImage_1->save($albumPath . $name);
                $originalImage_1->save($albumPath . 'original/' . $name);
                $job->attachment_1 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_2 = $request->file('attachment_2');
            if (is_file($file_2)) {
                $extension_2 = $file_2->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_2;
                // $thumbnailImage_2 = Image::make($file_2);
                $originalImage_2 = Image::make($file_2);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_2->resize(775, 645);
                // $thumbnailImage_2->save($albumPath . $name);
                $originalImage_2->save($albumPath . 'original/' . $name);
                $job->attachment_2 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_3 = $request->file('attachment_3');
            if (is_file($file_3)) {
                $extension_3 = $file_3->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_3;
                // $thumbnailImage_3 = Image::make($file_3);
                $originalImage_3 = Image::make($file_3);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_3->resize(775, 645);
                // $thumbnailImage_3->save($albumPath . $name);
                $originalImage_3->save($albumPath . 'original/' . $name);
                $job->attachment_3 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_4 = $request->file('attachment_4');
            if (is_file($file_4)) {
                $extension_4 = $file_4->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_4;
                // $thumbnailImage_4 = Image::make($file_4);
                $originalImage_4 = Image::make($file_4);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_4->resize(775, 645);
                // $thumbnailImage_4->save($albumPath . $name);
                $originalImage_4->save($albumPath . 'original/' . $name);
                $job->attachment_4 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_5 = $request->file('attachment_5');
            if (is_file($file_5)) {
                $extension_5 = $file_5->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_5;
                // $thumbnailImage_5 = Image::make($file_5);
                $originalImage_5 = Image::make($file_5);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_5->resize(775, 645);
                // $thumbnailImage_5->save($albumPath . $name);
                $originalImage_5->save($albumPath . 'original/' . $name);
                $job->attachment_5 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }
            $job->save();

            return redirect('job/list');
        }
        return redirect('job/list')->with([
            'success' => true,
            'success.message' => 'Job ' . $job->job_no . ' is successfully updated!',
            'success.title' => 'Success...!'
        ]);
    }

    public function getLevelData(Request $request, $id)
    {
        //$emp_types = EmployeeType::get()->pluck('type', 'id');
        $added_level = JobLevelConfig::where('job_id', $id)->orderBy('job_link')->get()->pluck('id', 'type');
        if (sizeof($added_level) == 0) {
            $added_level = ['Colour', 'Planning', 'Printing', 'Binding', 'Laminating', 'Cover', 'QA'];
        }
        return $added_level;
        //return array('emp_types'=>$emp_types,'added_level'=>$added_level);
    }

    public function resize()
    {
        $all_job = Job::get();
        foreach ($all_job as $job) {
            if (file_exists($job->img_url)) {
                $thumbnailImage = Image::make($job->img_url);
                $thumbnailImage->resize(775, 645);
                $thumbnailImage->save($job->img_url);

                $img_path = explode('/', $job->img_url);
                $file = $img_path[3];
                $original_path = implode('/', explode('/', $job->img_url, -1)) . '/original/' . $file;
                $job_obj = Job::find($job->id);
                $job_obj->original_img = $original_path;
                $job_obj->save();
            }
        }
        return 1;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $job_hierarchy = JobMasterData::get()->toHierarchy();
        $tree = NestJob::generate($job_hierarchy);

        return view("jobManage::job-data")->with([
            'job' => $job_hierarchy,
            'name' => '',
            'tree' => $tree
        ]);
    }

    function dataAdd($id = 0)
    {
        if ($id > 0) {
            $parent_data = JobMasterData::find($id);
            $id = $parent_data->id;
        }
        $jobs = JobMasterData::where('depth', 0)->get()->pluck('name', 'id')->prepend('Root', '0');
        return view("jobManage::data-add")->with([
            'jobs' => $jobs,
            'name' => '',
            'parent_data' => $id
        ]);
    }

    function dataEdit($id = 0)
    {
        if ($id > 0) {
            $job_data = JobMasterData::find($id);
        }
        $jobs = JobMasterData::where('depth', 0)->get()->pluck('name', 'id')->prepend('Root', '0');
        return view("jobManage::data-edit")->with([
            'jobs' => $jobs,
            'name' => $job_data->name,
            'parent_data' => $job_data->parent
        ]);
    }

    function addData(Request $request)
    {

        $parent = JobMasterData::find($request->parent);

        $sortAfter = JobMasterData::find($request->parent_order);

        $tag = JobMasterData::create([
            'name' => $request->name
        ]);

        if ($parent != null) {
            $tag->makeChildOf($parent);
        } else {
            $tag->makeRoot();
        }

        if ($sortAfter && $tag->id != $sortAfter->id) {
            $tag->moveToRightOf($sortAfter);
        }

        JobMasterData::rebuild();

        return redirect()->back()->with([
            'success' => true,
            'success.message' => 'Job data added successfully!',
            'success.title' => 'Success..!'
        ]);
    }

    function editData($id, Request $request)
    {

        $job = JobMasterData::find($id);

        $parent = JobMasterData::find($request->parent);

        $sortAfter = JobMasterData::find($request->parent_order);

        $job->name = $request->name;
        $job->save();

        if ($parent != null) {
            $job->makeChildOf($parent);
        } else {
            $job->makeRoot();
        }

        if ($sortAfter && $job->id != $sortAfter->id) {
            $job->moveToRightOf($sortAfter);
        }

        JobMasterData::rebuild();

        return redirect()->back()->with([
            'success' => true,
            'success.message' => 'Job data edit successfully!',
            'success.title' => 'Success..!'
        ]);
    }

    function deleteData(Request $request)
    {

        $job = JobMasterData::find($request->id);

        $parent = JobMasterData::find($job->parent);

        $sortAfter = JobMasterData::find($job->parent_order);

        $job->delete();

        JobMasterData::rebuild();

        return array('status' => 'success');
    }

    function dataSearch(Request $request)
    {
        $job_hierarchy = JobMasterData::where('status', 1);
        if ($request->get('name')) {
            $job_hierarchy = $job_hierarchy->where('name', 'LIKE', '%' . $request->name . '%')->first()->getDescendantsAndSelf()->toHierarchy();
        } else {
            $job_hierarchy = $job_hierarchy->get()->toHierarchy();
        }

        $tree = NestJob::generate($job_hierarchy);

        return view("jobManage::job-data")->with([
            'job' => $job_hierarchy,
            'name' => $request->name,
            'tree' => $tree
        ]);
    }

    function newAddView(Request $request)
    {
        $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
        $customers = Customer::all();
        $last_job = JobNew::orderBy('id', 'DESC')->limit(1)->get();
        $job_data = JobMasterData::get()->toHierarchy();
        $job_no = sizeof($last_job) > 0 && $last_job[0]->job_no ? $this->generateNo($last_job[0]->job_no) : $this->generateNo(0);
        return view('jobManage::new-add')->with(['album_types' => $album_type, 'customers' => $customers, 'job_no' => $job_no, 'job_data' => $job_data]);
    }

    function AddNewJob(Request $request)
    {

        //return $request->all();

        $last_job = JobNew::orderBy('id', 'DESC')->limit(1)->get();
        $job_no = sizeof($last_job) > 0 && $last_job[0]->job_no ? $this->generateNo($last_job[0]->job_no) : $this->generateNo(0);

        // return $request->all();
        $is_exist = JobNew::where('job_no', $request->get('job_no'))->get();
        if (sizeof($is_exist) > 0 || !$request->get('job_no')) {

            return redirect()->back()->withInput()->with([
                'error' => true,
                'error.message' => 'Job No already exist..',
                'error.title' => 'Failed...!'
            ]);
        }

        if ($request->get('customer_name') == 0) {
            return redirect()->back()->withInput()->with([
                'error' => true,
                'error.message' => 'Please select the customer..',
                'error.title' => 'Failed...!'
            ]);
        }

        $paper = array();
        $create_by = Sentinel::getUser();

        $job = JobNew::create([
            'customer_id' => $request->get('customer_name'),
            'couple_name' => $request->get('couple_name'),
            'job_no' => $job_no,
            'remark' => nl2br($request->get('remark')),
            'due_date' => $request->get('due_date'),
            'create_by' => $create_by->employee_id,
            'delivery' => $request->get('delivery'),
            'status' => 0
        ]);

        $id = $job->id;

        $size = $request->radio_type[0];
        if ($request->radio_type) {
            for ($i = 1; $i <= 5; $i++) {
                foreach ($request->radio_type as $type) {
                    $album_type = JobData::create([
                        'job_new_id' => $id,
                        'job_master_data_id' => $type,
                        'album_no' => $i,
                    ]);
                }
            }
            $job->album = $request->radio_type[0];
        }

        $master_data = JobMasterData::where('depth', '=', 0)->get();

        foreach ($master_data as $data) {
            for ($i = 1; $i <= 5; $i++) {
                if ($request->get('data_' . $i . '_' . $data->id)) {
                    $job_data = $request->get('data_' . $i . '_' . $data->id);

                    $album_type = JobData::create([
                        'job_new_id' => $id,
                        'job_master_data_id' => $job_data,
                        'album_no' => $i,
                    ]);
                    /*if ($data->name == 'Album Size') {
                        $album = JobAlbum::create([
                            'job_id' => $job->id,
                            'album_id' => $i,
                            'album_no' => $job_data
                        ]);
                        $job['qr_' . $i] = $job->job_no . '-' . $i . '-' . $album->id;
                    }*/
                }
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->get('size_' . $i)) {
                $job_album = JobAlbum::create([
                    'job_id' => $job->id,
                    'album_id' => $i,
                    'pages' => $request->get('pages_' . $i),
                    'album_size' => $request->get('size_' . $i)
                ]);
                $job['qr_' . $i] = $job->job_no . '-' . $i;
            }
        }

        if ($job) {
            $file = $request->file('attachment');
            if (is_file($file)) {
                $extension = $file->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension;
                $thumbnailImage = Image::make($file);
                $originalImage = Image::make($file);
                $thumbnailPath = 'storage/images/job/';
                $thumbnailImage->resize(775, 645);
                $thumbnailImage->save($thumbnailPath . $name);
                $originalImage->save($thumbnailPath . 'original/' . $name);
                $job->album_size = $size;
                $job->img_url = $thumbnailPath . $name;
                $job->original_img = $thumbnailPath . 'original/' . $name;
            }

            $file_1 = $request->file('attachment_1');
            if (is_file($file_1)) {
                $extension_1 = $file_1->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_1;
                // $thumbnailImage_1 = Image::make($file_1);
                $originalImage_1 = Image::make($file_1);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_1->resize(775, 645);
                // $thumbnailImage_1->save($albumPath . $name);
                $originalImage_1->save($albumPath . 'original/' . $name);
                $job->attachment_1 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_2 = $request->file('attachment_2');
            if (is_file($file_2)) {
                $extension_2 = $file_2->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_2;
                // $thumbnailImage_2 = Image::make($file_2);
                $originalImage_2 = Image::make($file_2);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_2->resize(775, 645);
                // $thumbnailImage_2->save($albumPath . $name);
                $originalImage_2->save($albumPath . 'original/' . $name);
                $job->attachment_2 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_3 = $request->file('attachment_3');
            if (is_file($file_3)) {
                $extension_3 = $file_3->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_3;
                // $thumbnailImage_3 = Image::make($file_3);
                $originalImage_3 = Image::make($file_3);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_3->resize(775, 645);
                // $thumbnailImage_3->save($albumPath . $name);
                $originalImage_3->save($albumPath . 'original/' . $name);
                $job->attachment_3 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_4 = $request->file('attachment_4');
            if (is_file($file_4)) {
                $extension_4 = $file_4->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_4;
                // $thumbnailImage_4 = Image::make($file_4);
                $originalImage_4 = Image::make($file_4);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_4->resize(775, 645);
                // $thumbnailImage_4->save($albumPath . $name);
                $originalImage_4->save($albumPath . 'original/' . $name);
                $job->attachment_4 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }

            $file_5 = $request->file('attachment_5');
            if (is_file($file_5)) {
                $extension_5 = $file_5->getClientOriginalExtension();
                $name = $job->job_no . '.' . $extension_5;
                // $thumbnailImage_5 = Image::make($file_5);
                $originalImage_5 = Image::make($file_5);
                $albumPath = 'storage/images/job/album';
                // $thumbnailImage_5->resize(775, 645);
                // $thumbnailImage_5->save($albumPath . $name);
                $originalImage_5->save($albumPath . 'original/' . $name);
                $job->attachment_5 = $albumPath . $name;
                // $job->original_img = $albumPath . 'original/' . $name;
            }
            $job->save();

            return redirect('job/level/' . $job->id);
        }
        return redirect()->back()->with([
            'error' => true,
            'error.message' => 'Job add failed..',
            'error.title' => 'Failed...!'
        ]);
    }

    public function qrList(Request $request, $id)
    {

        $job = JobNew::find($id);

        $barcode = new BarcodeGenerator();
        $barcode->setScale(2);
        $barcode->setThickness(25);
        $barcode->setFontSize(15);

        if (!$job) {
            return redirect()->back()->with([
                'error' => true,
                'error.message' => 'Invalid Job ..',
                'error.title' => 'Failed...!'
            ]);
        }
        return view("jobManage::qr-list")->with([
            'job' => $job,
            'barcode' => $barcode
        ]);
    }

    public function qrPrint($id)
    {
        $job = JobNew::find($id);
        if ($job) {
            $destinationPath = 'storage/images/qr/' . $job->id . '/';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $d = new DNS1D();
            $d->setStorPath($destinationPath);
            if ($job->qr_1) {
                $d->getBarcodePNGPath($job->qr_1, "C39+", 1, 80);
            }
            if ($job->qr_2) {
                $d->getBarcodePNGPath($job->qr_2, "C39+", 1, 80);
            }
            if ($job->qr_3) {
                $d->getBarcodePNGPath($job->qr_3, "C39+", 1, 80);
            }
            if ($job->qr_4) {
                $d->getBarcodePNGPath($job->qr_4, "C39+", 1, 80);
            }
            if ($job->qr_5) {
                $d->getBarcodePNGPath($job->qr_5, "C39+", 1, 80);
            }
            $files = glob('storage/images/qr/' . $job->id . '/*');
            Zipper::make('storage/' . $job->job_no . '.zip')->add($files)->close();
            if (file_exists('storage/' . $job->job_no . '.zip')) {
                return response()->download('storage/' . $job->job_no . '.zip');
            }
        }
        return redirect()->back();
    }

    public function newList(Request $request)
    {
        return redirect('job/list');
    }

    public function editNewJob(Request $request, $qr_id)
    {
        $job = JobNew::with(['customer', 'data.master', 'printer'])->where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();
        $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
        $customers = Customer::all();
        $job_data = JobMasterData::where('depth', 0)->get();
        return view('jobManage::new-color-edit')->with(['album_types' => $album_type, 'customers' => $customers, 'job_data' => $job_data, 'job' => $job]);
    }

    public function editPlaningJob(Request $request, $qr_id)
    {
        $job = JobNew::with(['customer', 'data.master.super', 'printer', 'job_album'])->where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();
        $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
        $customers = Customer::all();
        $job_data = JobMasterData::where('depth', 0)->get();
        return view('jobManage::new-planing-edit')->with(['album_types' => $album_type, 'customers' => $customers, 'job_data' => $job_data, 'job' => $job]);
    }

    public function editQAJob(Request $request, $qr_id)
    {
        $job = JobNew::with(['customer', 'data.master.parent', 'printer'])->where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();
        $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
        $customers = Customer::all();
        $job_data = JobMasterData::where('depth', 0)->get();
        return view('jobManage::new-qa-edit')->with(['album_types' => $album_type, 'customers' => $customers, 'job_data' => $job_data, 'job' => $job]);
    }

    public function editOtherJob(Request $request, $qr_id)
    {
        $job = JobNew::with(['customer', 'data.master', 'printer'])->where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();
        $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
        $customers = Customer::all();
        $job_data = JobMasterData::where('depth', 0)->get();
        return view('jobManage::job-other-edit')->with(['album_types' => $album_type, 'customers' => $customers, 'job_data' => $job_data, 'job' => $job]);
    }

    public function newJobSearch(Request $request, $id = 0)
    {
        $qr_id = $request->get('qr');

        $user = Sentinel::getUser();

        if (trim($user->roles[0]->name) == 'customer') {
            $customer = Customer::where('user_id', $user->id)->first();
            $job = JobNew::with(['customer', 'data.master', 'printer'])->where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->orWhere('job_no', $qr_id)->where('customer_id', $customer->id)->first();
        } else {
            $job = JobNew::with(['customer', 'data.master', 'printer'])->where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->orWhere('job_no', $qr_id)->first();
        }
        if ($job) {

            $qr = $qr_id;
            if ($user->id > 1) {
                if ($user->roles[0]['slug'] == 'colour') {
                    return redirect('job/new/edit/' . $qr);
                } else if ($user->roles[0]['slug'] == 'planning') {
                    return redirect('job/planing/edit/' . $qr);
                } else if ($user->roles[0]['slug'] == 'qa') {
                    return redirect('job/qa/edit/' . $qr);
                } else {
                    $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
                    $customers = Customer::all();
                    $job_data = JobMasterData::where('depth', 0)->get();
                    return view('jobManage::job-other-edit')->with(['album_types' => $album_type, 'customers' => $customers, 'job_data' => $job_data, 'job' => $job, 'qr' => $qr]);
                }
            } else {
                $qr = $qr_id;
                $album_type = JobMasterData::find(1)->getDescendants()->toHierarchy();
                $customers = Customer::all();
                $job_data = JobMasterData::where('depth', 0)->get();
                return view('jobManage::job-other-edit')->with(['album_types' => $album_type, 'customers' => $customers, 'job_data' => $job_data, 'job' => $job, 'qr' => $qr]);
            }
        } else {
            $emp = Employee::with('type')->find($qr_id);
            if ($emp) {

                $qr_id = $request->get('album_qr');
                //$ids = explode('-', $qr_id);
                // return $id;
                $job = JobNew::find($id);
                if ($job) {
                    $level = JobLevelConfig::where('job_id', $id)->where('type', $emp->type->type)->first();
                    if ($level) {
                        $album = JobAlbum::where('job_id', $job->id)->where('album_id', $qr_id)->first();
                        $album->user = $emp->id;
                        $album->user_level = $level->job_link;
                        if ($album->status == 0) {
                            $album->status = 1;
                        } else {
                            $album->status = 0;
                        }
                        $album->save();

                        $job_album = JobAlbum::where('job_id', $job->id)->get();
                        $status = 0;
                        foreach ($job_album as $al) {
                            // Log::info($al);
                            if ($al->user_level) {
                                if ($status > $al->user_level || $status == 0) {
                                    $status = $al->user_level;
                                    // Log::info($status);
                                }
                            }
                        }

                        // return $status;

                        if ($job->status > $status) {
                            $job->status = $status;
                            $job->save();
                        }
                    } else {
                        return redirect()->back()->with([
                            'info' => true,
                            'info.message' => 'Invalid job allocation...',
                            'info.title' => 'Information...!'
                        ]);
                    }

                    return redirect('job/new/list')->with([
                        'success' => true,
                        'success.message' => 'Job ' . $job->job_no . ' is confirm!',
                        'success.title' => 'Success...!'
                    ]);
                } else {
                    return redirect()->back()->with([
                        'info' => true,
                        'info.message' => 'Invalid Job',
                        'info.title' => 'Information...!'
                    ]);
                }
            } else {
                return redirect()->back()->with([
                    'info' => true,
                    'info.message' => 'Invalid QR or Job NO',
                    'info.title' => 'Information...!'
                ]);
            }
        }
    }

    public function newJobEdit(Request $request, $qr_id)
    {
        //return Sentinel::getUser();
        try {
            $transaction = DB::transaction(function () use ($request, $qr_id) {
                $job = JobNew::where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();
                JobPrinter::where('job_id', $job->id)->delete();
                for ($i = 1; $i <= 5; $i++) {
                    if (sizeof($request->get('radio_' . $i)) > 0) {
                        foreach ($request->get('radio_' . $i) as $printer) {
                            $album_printer = JobPrinter::create([
                                'job_id' => $job->id,
                                'album_id' => $i,
                                'printer' => $printer
                            ]);
                        }
                    } else {
                        throw new Exception("Stock Not Exist..");
                    }
                }
            });
            return redirect('job/new/list')->with([
                'success' => true,
                'success.message' => 'Printer added successfully!',
                'success.title' => 'Success..!'
            ]);
        } catch (Exception $ex) {
            return redirect()->back()->with([
                'error' => true,
                'error.message' => 'Printer add failed..',
                'error.title' => 'Failed...!'
            ])->withInput();
        }
    }

    public function newPlaningEdit(Request $request, $qr_id)
    {
        //return $request->all();
        try {

            $transaction = DB::transaction(function () use ($request, $qr_id) {

                $master_data = JobMasterData::where('name', 'like', 'cover%')->get();
                $job = JobNew::where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();

                foreach ($master_data as $data) {
                    for ($i = 1; $i <= 5; $i++) {
                        if ($request->get('data_' . $i . '_' . $data->id)) {
                            $job_data = $request->get('data_' . $i . '_' . $data->id);
                            if ($request->get('id_' . $i . '_' . $data->id)) {
                                $job_data_id = $request->get('id_' . $i . '_' . $data->id);
                                $album_type = JobData::find($job_data_id);
                                $album_type->job_master_data_id = $job_data;
                                $album_type->save();
                            } else {
                                $album_type = JobData::create([
                                    'job_new_id' => $job->id,
                                    'job_master_data_id' => $job_data,
                                    'album_no' => $i,
                                ]);
                            }
                        }
                    }
                }

                for ($i = 1; $i <= 5; $i++) {
                    if (sizeof($request->get('page_' . $i)) > 0) {
                        $album = JobAlbum::where('job_id', $job->id)->where('album_id', $i)->first();
                        $album->pages = $request->get('page_' . $i);
                        $album->save();
                    }
                }
            });

            return redirect('job/new/list')->with([
                'success' => true,
                'success.message' => 'Printer added successfully!',
                'success.title' => 'Success..!'
            ]);
        } catch (Exception $ex) {
            Log::info($ex);
            return redirect()->back()->with([
                'error' => true,
                'error.message' => 'Printer add failed..',
                'error.title' => 'Failed...!'
            ])->withInput();
        }
    }

    public function newQAEdit(Request $request, $qr_id)
    {

        try {
            $transaction = DB::transaction(function () use ($request, $qr_id) {

                $job = JobNew::where('qr_1', $qr_id)->orWhere('qr_2', $qr_id)->orWhere('qr_3', $qr_id)->orWhere('qr_4', $qr_id)->orWhere('qr_5', $qr_id)->first();
                $master_data = JobData::where('job_new_id', $job->id)->get();

                foreach ($master_data as $data) {
                    if ($request->get($data->id) > -1) {
                        $qa_res = $request->get($data->id);
                        $job_data = JobData::find($data->id);
                        $job_data->qa_res = $qa_res;
                        $job_data->save();
                    }
                }
            });

            return redirect('job/new/list')->with([
                'success' => true,
                'success.message' => 'Job edit successfully!',
                'success.title' => 'Success..!'
            ]);
        } catch (Exception $ex) {
            return redirect()->back()->with([
                'error' => true,
                'error.message' => 'Job edit failed..',
                'error.title' => 'Failed...!'
            ])->withInput();
        }
    }

    public function noJobCustomers(Request $request)
    {
        $customerList = Customer::all();

        $marketeerList = Employee::where('employee_type_id', 2)->get();

        $customers = Customer::select('remon_customer.*', DB::raw('(SELECT created_at FROM job_new WHERE customer_id = remon_customer.id ORDER BY id DESC LIMIT 1) as last_job'))->with('marketeer');

        $from = date('Y-m-d', strtotime('-30 days'));
        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
        }

        $customers = $customers->whereNotIn('id', function ($q) use ($from, $to) {
            $q->from('job_new')->whereRaw("DATE(created_at) BETWEEN '" . $from . "' AND '" . $to . "'")->groupBy('customer_id')->pluck('customer_id');
        });

        if ($request->get('customer') > 0) {
            $customers = $customers->where('id', $request->get('customer'));
        }

        if ($request->get('marketeer')) {
            $customers = $customers->where('marketeer_id', $request->get('marketeer'));
        }

        $customers = $customers->orderBy('last_job', 'DESC')
            ->paginate(12);

        return view('jobManage::no-job-customer-report', ['orders' => $customers->appends(Input::except('page'))])->with(['orders' => $customers, 'marketeerList' => $marketeerList, 'invoice_no' => $request->get('invoice_no'), 'marketeer' => $request->get('marketeer'), 'from' => $from, 'to' => $to, 'status' => $request->get('status'), 'customerList' => $customerList, 'customer' => $request->get('customer')]);
    }

    public function noJobCustomersPrint(Request $request)
    {


        $customers = Customer::select('remon_customer.*', DB::raw('(SELECT created_at FROM job_new WHERE customer_id = remon_customer.id ORDER BY id DESC LIMIT 1) as last_job'))->with('marketeer');

        $from = date('Y-m-d', strtotime('-30 days'));
        $to = date('Y-m-d');
        if (strlen($request->get('from')) > 0) {
            $from = $request->get('from');
            if (strlen($request->get('to')) > 0) {
                $to = $request->get('to');
            }
        }

        $customers = $customers->whereNotIn('id', function ($q) use ($from, $to) {
            $q->from('job_new')->whereRaw("DATE(created_at) BETWEEN '" . $from . "' AND '" . $to . "'")->groupBy('customer_id')->pluck('customer_id');
        });

        $cus_all = '';
        if ($request->get('customer') > 0) {
            $cus_all = Customer::find($request->get('customer'));
            $customer = $cus_all->f_name . ' ' . $cus_all->l_name;
            $cus_all = $cus_all->mobile . ' / ' . $cus_all->telephone;
            $customers = $customers->where('id', $request->get('customer'));
        } else {
            $customer = 'All';
        }

        if ($request->get('marketeer')) {
            $customers = $customers->where('marketeer_id', $request->get('marketeer'));
            $marketeer = Employee::find($request->get('marketeer'));
            $marketeer = $marketeer->first_name . ' ' . $marketeer->last_name;
        } else {
            $marketeer = 'All';
        }

        $customers = $customers->orderBy('last_job', 'DESC');
        $customers = $customers->get();

        //return $orders;

        $header = ['customer' => $customer, 'marketeer' => $marketeer, 'from' => $from, 'to' => $to, 'cus_all' => $cus_all];
        if ($request->get('action') == 1) {



            if ($customers) {
                $page1 = view('jobManage::print.no-job-customer')->with(['orders' => $customers, 'page_header' => $header]);
            } else {
                return response()->view("errors.404");
            }

            $pdf = new PdfTemplate('P', 'mm', 'A4');
            $pdf->SetMargins(28.35, 10);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetAutoPageBreak(TRUE, 20);
            $pdf->AddPage();
            $pdf->writeHtml($page1);
            $pdf->output("recipt.pdf", 'I');
        } else {
            $now = new \DateTime('NOW');
            $date = $now->format('Y-m-d_H-i-s'); //
            $fileName = $date . '_sheet';
            Excel::load(storage_path('xls/template/no_job_customer.xls'), function ($excel) use ($customers, $header) {
                $tbl_column = 7;
                $index = 1;
                $excel->getActiveSheet()->setCellValue('B' . 2, $header['customer']);
                $excel->getActiveSheet()->setCellValue('B' . 3, $header['cus_all']);
                $excel->getActiveSheet()->setCellValue('B' . 4, $header['marketeer']);
                $excel->getActiveSheet()->setCellValue('D' . 2, $header['from']);
                $excel->getActiveSheet()->setCellValue('D' . 3, $header['to']);

                foreach ($customers as $order) {

                    $excel->getActiveSheet()->setCellValue('A' . $tbl_column, $index);
                    $excel->getActiveSheet()->setCellValue('B' . $tbl_column, $order->f_name . ' ' . $order->l_name);
                    $excel->getActiveSheet()->setCellValue('C' . $tbl_column, $order->address);
                    $excel->getActiveSheet()->setCellValue('D' . $tbl_column, $order->email);
                    $excel->getActiveSheet()->setCellValue('E' . $tbl_column, $order->mobile);
                    $excel->getActiveSheet()->setCellValue('F' . $tbl_column, $order->marketeer->first_name . ' ' . $order->marketeer->last_name);
                    $excel->getActiveSheet()->setCellValue('G' . $tbl_column, $order->last_job);
                    $tbl_column++;
                    $index++;
                }
            })->setFileName($fileName)->store('xlsx', storage_path('xls/download'))->download();
        }

        return redirect()->back();
    }
}
