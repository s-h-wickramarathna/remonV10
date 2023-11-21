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
            // dd(Sentinel::check());
            if (!Sentinel::check()) {

                return view('layouts.login');
            } else {
                // dd('auth');
                $redirect = Session::get('loginRedirect');
                Session::forget('loginRedirect');
                // dd(url()->previous());
                return redirect(url()->previous());
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

        // dd($credentials,$remember);

        try {
            $users = DB::table('user')->where(['username' => $request->input('username'), 'status' => 1])->get();
            // dd($users);
            // dd('ok');
            if ($users) {
                $user = Sentinel::authenticate($credentials, $remember);
                // dd($user);
                if ($user) {
                    // $redirect = Session::get('loginRedirect', '');
                    // Session::forget('loginRedirect');
                    // dd($request->session()->get('loginRedirect'));
                    return redirect($request->session()->pull('loginRedirect', 'default'));
                } else {
                    $msg = 'Invalid login username/password. Try again!';
                }
            } else {
                $msg = "Your Account has been Deactivated!.";
            }
        } catch (\Exception $e) {
            dd($e);
            $msg = $e->getMessage();
        }

        return redirect('user/login')->withErrors(array(
            'login' => $msg
        ))->withInput($request->except('password'));
    }

    public function logout()
    {
        Sentinel::logout();
		return redirect()->route('user.login');
    }
}
