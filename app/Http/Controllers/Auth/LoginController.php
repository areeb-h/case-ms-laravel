<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    //protected $redirectTo = '/login';
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('Administrator')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Lawyer')) {
            return redirect()->route('lawyer.dashboard'); // Replace 'lawyer.dashboard' with the actual route name for lawyer dashboard
        } elseif ($user->hasRole('Paralegal')) {
            return redirect()->route('paralegal.dashboard'); // Replace 'client.dashboard' with the actual route name for client dashboard
        } elseif ($user->hasRole('Client')) {
            return redirect()->route('client.dashboard'); // Replace 'client.dashboard' with the actual route name for client dashboard
        } else {
            return redirect()->route('home'); // Redirect to a default page if no matching role is found
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
