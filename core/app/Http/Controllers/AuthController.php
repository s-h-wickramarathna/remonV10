<?php

namespace App\Http\Controllers;

use Application\EmployeeManage\Models\Employee;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginView(Request $request)
    {
        try {
            if (!Sentinel::check()) {
                return view('layouts.login');

            } else {
                $redirect = $request->session()->get('loginRedirect');
                $request->session()->forget('loginRedirect');
                return redirect($redirect);

            }
        } catch (\Exception $e) {
            return view('layouts.login')->with(['login' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        $credentials = array(
            'username'    => $request->input('username'),
            'password' => $request->input('password')
        );

        if ($request->input('remember')) {
            $remember = true;
        } else {
            $remember = false;
        }

        // dd($request->all());

        try {
            $users = DB::table('user')->where(['username' => $request->input('username'), 'status' => 1])->get();

            if ($users) {
                $user = Sentinel::authenticate($credentials, $remember);
                if ($user) {

                    if ($request->session()->exists('loginRedirect')) {
                        $redirect = $request->session()->get('loginRedirect');
                        $request->session()->forget('loginRedirect');

                        return redirect($redirect);

                    }else{
                        return redirect('/');
                        
                    }
                    
                } else {
                    $msg = 'Invalid login username/password. Try again!';
                }
            } else {
                $msg = "Your Account has been Deactivated!.";
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
        }

        return redirect('user/login')->withErrors(array(
            'login' => $msg
        ))->withInput($request->except('password'));
    }

    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('login');
    }
}
