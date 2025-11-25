<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\CompanyUser;

class CompanyTwoFactorAuthController extends Controller
{
    protected $twoFactorAuth;

    public function __construct(TwoFactorAuthService $twoFactorAuth)
    {
        $this->twoFactorAuth = $twoFactorAuth;
    }

    /**
     * Show the 2FA verification form
     */
    public function showVerificationForm()
    {
        // Check if user has 2FA session
        $userId = Session::get('company_2fa_user_id');
        if (!$userId) {
            return redirect()->route('company/login')->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
        }

        // Check if user already has a valid OTP
        if (!$this->twoFactorAuth->hasValidOtp($userId, 'company_login')) {
            return redirect()->route('company/login')->with('error', 'رمز التحقق غير صالح. يرجى تسجيل الدخول مرة أخرى.');
        }

        $remainingTime = $this->twoFactorAuth->getOtpRemainingTime($userId, 'company_login');
        
        return view('comapny.auth.two-factor', compact('remainingTime'));
    }

    /**
     * Verify the 2FA code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|numeric|digits:6',
        ]);

        // Get user from session
        $userId = Session::get('company_2fa_user_id');
        if (!$userId) {
            return redirect()->route('company/login')->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
        }

        $otpCode = $request->otp_code;

        $result = $this->twoFactorAuth->verifyOTP($userId, $otpCode, 'company_login');

        if ($result['success']) {
            // Log the company user in manually
            Auth::guard('companys')->loginUsingId($userId);
            
            // Clear 2FA session
            Session::forget('company_2fa_user_id');
            
            Alert::success('نجح التحقق', 'مرحباً بك في نظام إدارة شركة التأمين');
            return redirect()->intended('company/home');
        }

        return back()->withErrors(['otp_code' => $result['message']]);
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        // Get user from session
        $userId = Session::get('company_2fa_user_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الجلسة'
            ]);
        }

        // Get user model
        $user = CompanyUser::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ]);
        }
        
        // Generate and send new OTP
        $result = $this->twoFactorAuth->generateAndSendOTP($user, 'company_login');

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
}
