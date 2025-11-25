<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class OTPemail extends Mailable
{
    public $otp;
    public $user;
    public $type;
    public $expiresIn;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $user, $type = 'login', $expiresIn = 10)
    {
        $this->otp = $otp;
        $this->user = $user;
        $this->type = $type;
        $this->expiresIn = $expiresIn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->getSubject();
        $messageText = $this->getMessage();

        return $this->subject($subject)
                   ->view('emails.otp')
                   ->with([
                       'otp' => $this->otp,
                       'user' => $this->user,
                       'type' => $this->type,
                       'expiresIn' => $this->expiresIn,
                       'messageText' => $messageText,
                   ]);
    }

    private function getSubject()
    {
        switch ($this->type) {
            case 'login':
                return 'رمز التحقق للدخول - Lifopro System';
            case 'reset_password':
                return 'رمز إعادة تعيين كلمة المرور - Lifopro System';
            default:
                return 'رمز التحقق - Lifopro System';
        }
    }

    private function getMessage()
    {
        switch ($this->type) {
            case 'login':
                return 'استخدم رمز التحقق التالي لتسجيل الدخول إلى حسابك في نظام الاتحاد العام للتامين';
            case 'reset_password':
                return 'استخدم رمز التحقق التالي لإعادة تعيين كلمة المرور الخاصة بك';
            default:
                return 'استخدم رمز التحقق التالي لإتمام العملية';
        }
    }
}