<?php

namespace App\Http\Controllers\Office\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPemail;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Customer;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Models\PermitRequest;
use App\Models\Serviceprovider;
use App\Models\Tools;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\UserOtp;
use App\Models\WorkTeam;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

use function PHPUnit\Framework\isNull;

class OfficeloginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'office/issuing';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
  



    public function guard()
    {
        return Auth::guard('officess');
    }




    public function showLoginForm()
    {
        $guardName = $this->guard()->getName();
        $showCaptcha = session('failed_attempts_' . $guardName, 0) >= 5;
        return view('office.auth.login', compact('showCaptcha'));
    }




    public function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        $guardName = $this->guard()->getName();
        // Show captcha only after 5 failed attempts
        if (session('failed_attempts_' . $guardName, 0) >= 5) {
            $rules['captcha'] = 'required|captcha';
        }

        $request->validate($rules);
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);
        /* Validation Logic */
        $userid = OfficeUser::where('username', $request->username)->first();

        $guardName = $this->guard()->getName();

        if (is_null($userid)) {
            session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
            return redirect()
                ->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors(['username' => trans('auth.failed')]);
        }
        $Office=Office::find($userid->offices_id);
        $companie=Company::find($Office->companies_id);

        if ($companie->active==0) {
            session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
            return redirect()
            ->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors(['username' =>"حساب الشركة معطل الرجاء الاتصال الاتحاد"]);

        }


        if ($Office->active==0) {
            session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
            return redirect()
            ->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors(['username' =>"حساب المكتب معطل الرجاء الاتصال بمدير الشركة"]);

        }


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }



        // Only Login Active
        if ($this->guard()->validate($this->credentials($request))) {

            $user = $this->guard()->getLastAttempted();


            // Make sure the user is active
            if ($user->active && $this->attemptLogin($request)) {
                // Send the normal successful login response
                // Reset failed attempts counter on success
                session(['failed_attempts_' . $guardName => 0]);

                return $this->sendLoginResponse($request);
            } else {
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.

                $this->incrementLoginAttempts($request);
                session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['username' => 'حسابك معطل قم بالاتصال بمدير النظام']);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);

        return $this->sendFailedLoginResponse($request);
    }
    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();


        return $this->loggedOut($request) ?:

            redirect('office/login');
    }


}
