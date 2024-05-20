<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        $data['tittle'] = 'Login';

        return view('auth.login', $data);
    }

    public function login(Request $request)
    {

        // dd($request->all());
    
        $checkUser = DB::table('users')->where('username', $request->username)->first();

        if ($checkUser) {

            if( $checkUser->password != $request->password ){
                return back()->with('LoginError', 'username or password do not match our records.' );
            }

            Auth::loginUsingId($checkUser->id); // Log in the user

            $request->session()->regenerate();
            
            return redirect()->intended('absensi');
          
        }

        return back()->with('LoginError', 'username or password do not match our records.' );
    }




    public function logout(Request $request)
    {
        $user_id = Auth::user()->id;

        $time_now = Carbon::now();

        DB::table('users')->where('id', $user_id)->update(['last_login' => $time_now]);

        auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

}



