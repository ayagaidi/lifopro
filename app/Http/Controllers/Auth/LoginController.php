<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use App\Models\ActivityLog;
use App\Services\TwoFactorAuthService;

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
    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 2; 

    protected $twoFactorAuth;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'report/issuing';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TwoFactorAuthService $twoFactorAuth)
    {
        $this->middleware('guest')->except('logout');
        $this->twoFactorAuth = $twoFactorAuth;
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }

    public function showLoginForm()
    {
        $guardName = $this->guard()->getName();
        $showCaptcha = session('failed_attempts_' . $guardName, 0) >= 5;
        return view('auth.login', compact('showCaptcha'));
    }
    public function username()
    {
        $login = request()->input('email');
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        request()->merge([$field => $login]);

        return $field;
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

        $guardName = $this->guard()->getName();

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
                // Reset failed attempts counter on success
                session(['failed_attempts_' . $guardName => 0]);

                // Check if user requires 2FA (super admin with admin role)
                if ($this->twoFactorAuth->requiresTwoFactor($user)) {
                    // Generate and send OTP
                    $otpResult = $this->twoFactorAuth->generateAndSendOTP($user, 'login');
                    
                    if ($otpResult['success']) {
                        // Log successful login before 2FA
                        ActivityLogger::activity("تسجيل دخول بنجاح - مطلوب التحقق بخطوتين");
                        
                        // Clear the current login session
                        $this->guard()->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        
                        // Start new session for 2FA verification
                        session(['2fa_user_id' => $user->id]);
                        
                        return redirect()->route('2fa.verify');
                    } else {
                        // Failed to send OTP
                        $this->guard()->logout();
                        return redirect()
                            ->back()
                            ->withInput($request->only($this->username(), 'remember'))
                            ->withErrors(['email' => 'فشل في إرسال رمز التحقق. يرجى المحاولة مرة أخرى.']);
                    }
                }

                // Send the normal successful login response for non-2FA users
                ActivityLogger::activity("تسجيل دخول بنجاح");

                return $this->sendLoginResponse($request);
            } else {
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                ActivityLogger::activity(" فشل تسجيل دخول ");

                $this->incrementLoginAttempts($request);
                session(['failed_attempts_' . $guardName => session('failed_attempts_' . $guardName, 0) + 1]);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['email' => 'حسابك معطل قم بالاتصال بمدير النظام']);
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

        redirect('login');
    }
}
