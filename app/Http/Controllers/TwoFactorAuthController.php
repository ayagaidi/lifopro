<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class TwoFactorAuthController extends Controller
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
        $userId = Session::get('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
        }

        // Check if user already has a valid OTP
        if (!$this->twoFactorAuth->hasValidOtp($userId, 'login')) {
            return redirect()->route('login')->with('error', 'رمز التحقق غير صالح. يرجى تسجيل الدخول مرة أخرى.');
        }

        $remainingTime = $this->twoFactorAuth->getOtpRemainingTime($userId, 'login');
        
        return view('auth.two-factor', compact('remainingTime'));
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
        $userId = Session::get('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'انتهت صلاحية الجلسة. يرجى تسجيل الدخول مرة أخرى.');
        }

        $otpCode = $request->otp_code;

        $result = $this->twoFactorAuth->verifyOTP($userId, $otpCode, 'login');

        if ($result['success']) {
            // Log the user in manually
            Auth::loginUsingId($userId);
            
            // Clear 2FA session
            Session::forget('2fa_user_id');
            
            Alert::success('نجح التحقق', 'مرحباً بك في نظام الاتحاد العام للتامين');
            return redirect()->intended('report/issuing');
        }

        return back()->withErrors(['otp_code' => $result['message']]);
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        // Get user from session
        $userId = Session::get('2fa_user_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الجلسة'
            ]);
        }

        // Get user model
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ]);
        }
        
        // Generate and send new OTP
        $result = $this->twoFactorAuth->generateAndSendOTP($user, 'login');

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