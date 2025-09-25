<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\Pages;
use App\Models\Serviceprovider;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use DB;

use Carbon\Carbon;

use App\Models\User;

use Mail;

use Hash;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    public function showForgetPasswordForm()
    {

        return view('front.serviceprovider.passwords.email');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([

            'email' => 'required|email|exists:serviceproviders',
        ]);
        $token = Str::random(64);
        $exist = DB::table('serviceproviders_password_reset')->where('email', $request->email)->first();
        if ($exist) {

            $updated = DB::table('serviceproviders_password_reset')
            ->where('email', $request->email)->update([
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $logo = asset('logo.png');
            $email = $request->email;

            FacadesMail::send('front.serviceprovider.passwords.emailform', ['token' => $token, 'email' => $email], function ($message) use ($request) {

                $message->to($request->email);

                $message->subject('اعادة تعيين كلمة المرور');
            });
            Alert::success('تم إرسال تفاصيل استعادة كلمة المرور الخاصة بك إلى بريدك الإلكتروني!');

            return back()->with('message', ' تم إرسال تفاصيل استعادة كلمة المرور الخاصة بك إلى بريدك الإلكتروني! ');
        
        } else {

            DB::table('serviceproviders_password_reset')->insert([

                'email' => $request->email,

                'token' => $token,

                'created_at' => Carbon::now()

            ]);

            $logo = asset('logo.png');
            $email = $request->email;

            FacadesMail::send('front.serviceprovider.passwords.emailform', ['token' => $token, 'email' => $email], function ($message) use ($request) {

                $message->to($request->email);

                $message->subject('اعادة تعيين كلمة المرور');
            });
            Alert::success('تم إرسال تفاصيل استعادة كلمة المرور الخاصة بك إلى بريدك الإلكتروني!');

            return back()->with('message', ' تم إرسال تفاصيل استعادة كلمة المرور الخاصة بك إلى بريدك الإلكتروني! ');
        }
    
    }

    public function showResetPasswordForm($token, $email)
    {


        // return view('auth.forgetPasswordLink', ['token' => $token]);
        return view('front.serviceprovider.passwords.reset', ['token' => $token, 'email' => $email]);
    }


    public function submitResetPasswordForm(Request $request)

    {

        $request->validate([

            'email' => 'required|email|exists:serviceproviders',

            'password' => 'required|string|min:8|confirmed',

            'password_confirmation' => 'required'

        ]);



        $updatePassword = DB::table('serviceproviders_password_reset')

            ->where([

                'email' => $request->email,

                'token' => $request->token

            ])

            ->first();



        if (!$updatePassword) {

            return back()->withInput()->with('error', 'رمز غير صالح!');
        }



        $user = Serviceprovider::where('email', $request->email)

            ->update(['password' => FacadesHash::make($request->password)]);



        DB::table('serviceproviders_password_reset')->where(['email' => $request->email])->delete();

        Alert::success('تم تغيير كلمة السر الخاصة بك!');


        return redirect('serverprovider/login')->with('message', 'تم تغيير كلمة السر الخاصة بك!');
    }
}
