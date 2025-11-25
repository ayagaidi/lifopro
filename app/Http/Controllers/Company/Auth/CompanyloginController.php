<?php

namespace App\Http\Controllers\Company\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPemail;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Customer;
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
use App\Services\TwoFactorAuthService;

use function PHPUnit\Framework\isNull;

class CompanyloginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'company/report/issuing';
    protected $twoFactorAuth;


    public function __construct(TwoFactorAuthService $twoFactorAuth)
    {
        $this->middleware('guest')->except('logout');
        $this->twoFactorAuth = $twoFactorAuth;
    }


    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
  



    public function guard()
    {
        return Auth::guard('companys');
    }




    public function showLoginForm()
    {
        $guardName = $this->guard()->getName();
        $showCaptcha = session('failed_attempts_' . $guardName, 0) >= 5;
        return view('comapny.auth.login', compact('showCaptcha'));
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
        $userid = CompanyUser::where('username', $request->username)->first();
        if (is_null($userid)) {

            $guardName = $this->guard()->getName();
            session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
            return redirect()
                ->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors(['username' => trans('auth.failed')]);
        }

        $comapy=Company::find($userid->companies_id);
        if ($comapy->active==0) {
            $guardName = $this->guard()->getName();
            session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
            return redirect()
            ->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors(['username' =>"حساب الشركة معطل الرجاء الاتصال بمدير الشركة"]);

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
                $guardName = $this->guard()->getName();
                // Reset failed attempts counter on success
                session(['failed_attempts_' . $guardName => 0]);

                // Check if user requires 2FA (company admin with user_type_id = 1)
                if ($this->twoFactorAuth->requiresTwoFactor($user)) {
                    // Generate and send OTP
                    $otpResult = $this->twoFactorAuth->generateAndSendOTP($user, 'company_login');
                    
                    if ($otpResult['success']) {
                        // Log successful login before 2FA
                        ActivityLogger::activity("تسجيل دخول الشركة بنجاح - مطلوب التحقق بخطوتين");
                        
                        // Clear the current login session
                        $this->guard()->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        
                        // Start new session for 2FA verification
                        session(['company_2fa_user_id' => $user->id]);
                        
                        return redirect()->route('company.2fa.verify');
                    } else {
                        // Failed to send OTP
                        $this->guard()->logout();
                        return redirect()
                            ->back()
                            ->withInput($request->only($this->username(), 'remember'))
                            ->withErrors(['username' => 'فشل في إرسال رمز التحقق. يرجى المحاولة مرة أخرى. تأكد من صحة البريد الإلكتروني المسجل في النظام.']);
                    }
                }

                // Send the normal successful login response for non-2FA users
                ActivityLogger::activity("تسجيل دخول الشركة بنجاح");

                return $this->sendLoginResponse($request);
            } else {
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                ActivityLogger::activity("فشل تسجيل دخول الشركة");

                $this->incrementLoginAttempts($request);
                $guardName = $this->guard()->getName();
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
        ActivityLogger::activity("فشل تسجيل دخول الشركة");

        $this->incrementLoginAttempts($request);
        $guardName = $this->guard()->getName();
        session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);

        return $this->sendFailedLoginResponse($request);
    }
    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();


        return $this->loggedOut($request) ?:

            redirect('company/login');
    }


}
