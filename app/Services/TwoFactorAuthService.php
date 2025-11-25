<?php

namespace App\Services;

use App\Models\UserOtp;
use App\Models\User;
use App\Mail\OTPemail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TwoFactorAuthService
{
    /**
     * Generate a random OTP code
     */
    public function generateOtpCode($length = 6)
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Check if user requires 2FA
     */
    public function requiresTwoFactor($user)
    {
        // Super admins (admin role) need 2FA
        if ($user instanceof User && $user->hasRole('admin')) {
            return true;
        }
        
        // Company admins need 2FA
        if ($user instanceof \App\Models\CompanyUser && $user->userType && $user->userType->id == 1) {
            return true;
        }
        
        return false;
    }

    /**
     * Generate and send OTP to user
     */
    public function generateAndSendOTP($user, $type = 'login', $expiresInMinutes = 10)
    {
        // Clean up old OTPs for this user and type
        $this->cleanupOldOtps($user->id, $type);

        // Generate new OTP
        $otpCode = $this->generateOtpCode();
        $expiresAt = Carbon::now()->addMinutes($expiresInMinutes);

        // Save OTP to database
        $otp = UserOtp::create([
            'user_id' => $user->id,
            'type' => $type,
            'otp_code' => $otpCode,
            'expires_at' => $expiresAt,
            'is_used' => false,
            'attempts' => 0,
        ]);

        // Send email with OTP
        try {
            Mail::to($user->email)->send(new OTPemail($otpCode, $user, $type, $expiresInMinutes));
            return [
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
                'otp_id' => $otp->id
            ];
        } catch (\Exception $e) {
            dd($e);
            // Log error and delete the OTP if email fails
            $otp->delete();
            Log::error('2FA Email sending failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'فشل في إرسال رمز التحقق. يرجى المحاولة مرة أخرى.'
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP($userId, $otpCode, $type = 'login', $maxAttempts = 3)
    {
        // Find valid OTP
        $otp = UserOtp::where('user_id', $userId)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otp) {
            return [
                'success' => false,
                'message' => 'رمز التحقق غير صالح أو منتهي الصلاحية',
                'attempts_remaining' => 0
            ];
        }

        // Check attempts limit
        if ($otp->attempts >= $maxAttempts) {
            return [
                'success' => false,
                'message' => 'تم تجاوز الحد الأقصى للمحاولات. اطلب رمزاً جديداً',
                'attempts_remaining' => 0
            ];
        }

        // Increment attempts
        $otp->increment('attempts');

        // Verify code
        if ($otp->otp_code === $otpCode) {
            // Mark as used
            $otp->update(['is_used' => true]);
            
            return [
                'success' => true,
                'message' => 'تم التحقق من الرمز بنجاح',
                'attempts_remaining' => $maxAttempts - $otp->attempts
            ];
        }

        $attemptsRemaining = $maxAttempts - $otp->attempts;
        
        return [
            'success' => false,
            'message' => 'رمز التحقق غير صحيح. المحاولات المتبقية: ' . $attemptsRemaining,
            'attempts_remaining' => $attemptsRemaining
        ];
    }

    /**
     * Clean up old OTPs
     */
    private function cleanupOldOtps($userId, $type)
    {
        // Delete expired OTPs
        UserOtp::where('user_id', $userId)
            ->where('type', $type)
            ->where(function ($query) {
                $query->where('expires_at', '<', Carbon::now())
                      ->orWhere('is_used', true);
            })
            ->delete();

        // Keep only the latest 5 OTPs per user per type
        $oldOtps = UserOtp::where('user_id', $userId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->skip(5)
            ->take(PHP_INT_MAX)
            ->pluck('id');
            
        UserOtp::whereIn('id', $oldOtps)->delete();
    }

    /**
     * Check if user has valid unexpired OTP for given type
     */
    public function hasValidOtp($userId, $type = 'login')
    {
        return UserOtp::where('user_id', $userId)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Get remaining time for current OTP
     */
    public function getOtpRemainingTime($userId, $type = 'login')
    {
        $otp = UserOtp::where('user_id', $userId)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otp) {
            return 0;
        }

        return $otp->expires_at->diffInSeconds(Carbon::now());
    }
}