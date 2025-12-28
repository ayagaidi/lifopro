<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use App\Models\ActivityLog;

class CompanyUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index($comid)
    {
        ActivityLogger::activity("عرض مستخدمين الشركات");

        $Company = Company::find($comid);
        return view('dashbord.company_users.index')
            ->with('comid', $comid)
            ->with('Company', $Company);
    }

    public function company_users($comid)
    {

        $company_users = CompanyUser::with(['userType'])
            ->select('*')->where('companies_id', $comid)->orderBy('created_at', 'DESC');
        return datatables()->of($company_users)
            ->addColumn('edit', function ($company_users) use ($comid) {
                $company_users_id = encrypt($company_users->id);

                return '<a style="color: #f97424;" href="' . route('company_users/edit', ['id' => $company_users_id, 'company_id' => $comid]) . '"><i  class="fa  fa-edit" > </i></a>';
            })

            ->addColumn('changeStatus', function ($company_users) {
                $company_users_id = encrypt($company_users->id);

                return '<a href="' . route('company_users/changeStatus', $company_users_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })
            ->addColumn('changepassord', function ($company_users) {
                $company_users_id = encrypt($company_users->id);

                return '<a href="' . route('company_users/changepassord', $company_users_id) . '"><i  class="fa  fa-lock"> </i></a>';
            })

            ->rawColumns(['edit', 'changeStatus', 'changepassord'])

            ->make(true);
    }

    public function changeStatus(Request $request, $id)

    {
        $company_users_id = decrypt($id);
        $company_users = CompanyUser::find($company_users_id);
        try {
            DB::transaction(function () use ($request, $id) {
                $company_users_id = decrypt($id);
                $company_users = CompanyUser::find($company_users_id);
                if ($company_users->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $company_users->active = $active;
                $company_users->save();
            });
            ActivityLogger::activity($company_users->username . "تمت عملية تغيير حالة  مستخدم الشركة");
            Alert::success("تمت عملية تغيير حالة  مستخدم الشركة");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة  مستخدم الشركة");
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة مستخدم الشركة");

            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($comid)
    {
        ActivityLogger::activity("عرض اضافة  مستخدم  شركة الشركات");
        $Company = Company::find($comid);
        $user_types = UserType::where('id', 1)->get();

        return view('dashbord.company_users.create')
            ->with('comid', $comid)
            ->with('user_types', $user_types)

            ->with('Company', $Company);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $comid)
    {
        $messages = [
            'name.required' => "ادخل  الشركة ",
            'phonenumber.required' => "ادخل رقم الهاتف",
            'code.required' => "ادخل رمز الشركة",
            'email.required' => "ادخل  البريد الألكتروني  ",
            'website.required' => "ادخل  الموقع الالكتروني  ",
            'fullname_manger.required' => "ادخل  مدير الشركة (المندوب)  ",
            'phonenumber_manger.required' => "ادخل  رقم هاتف المدير (المندوب)  ",
            'address.required' => "ادخل العنوان ",
            'username.required' => "ادخل  اسم المتسخدم  ",
            'password.required' => "ادخل  كلمة المرور",
            'img.required' => "ادخل  صورة",

            'cities_id.required' => "اخترالمنطقة",

            'user_type_id.required' => "اختر نوع المستخدم",
            'password_confirmation.required' => "قم بادخال تاكيد كلمة المرور  ",


        ];
        $this->validate($request, [

            'username' => 'required|string|max:255|unique:company_users', // Assuming users table stores company username
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => ['required'],
            'email' => 'required|email|max:100|unique:company_users',
            'user_type_id' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request, $comid) {

                $company_users = new CompanyUser();
                $company_users->username = $request->username;
                $company_users->password = Hash::make($request->password);
                $company_users->fullname = $request->username;
                $company_users->email = $request->email;

                $company_users->user_type_id = $request->user_type_id;
                $company_users->companies_id =  $comid;

                $company_users->save();
            });
            Alert::success("تمت اضافة  مستخدم شركة بنجاح");
            ActivityLogger::activity($request->name . "تمت اضافةمستخدم  شركة بنجاح");

            return redirect()->route('company');
        } catch (\Exception $e) {

            Alert::warning(" فشل اضافة مستخدم شركة ");
            ActivityLogger::activity($e->getMessage()  . "  فشل اضافة   مستخدم شركة ");

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyUser $companyUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($companyy, $comid)
    {

        $CompanyUser_id = decrypt($companyy);
        $CompanyUser = CompanyUser::find($CompanyUser_id);
        ActivityLogger::activity("صفحة تعديل بيانات  مستخدم شركة");

        $user_types = UserType::all();

        return view('dashbord.company_users.edit')
            ->with('CompanyUser', $CompanyUser)
            ->with('user_types', $user_types)

            ->with('comid', $comid)
        ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id, $comid)
    {
        $CompanyUser_id = decrypt($id);
        $CompanyUser = CompanyUser::find($CompanyUser_id);
        $messages = [
            'username.required' => "ادخل  اسم المستخدم ",
            'email.required' => "ادخل  البريد الألكتروني  ",
            'user_type_id.required' => "اختر نوع الحساب",

        ];
        $this->validate($request, [
            'username' => ['required', 'string', 'unique:company_users,username,' . $CompanyUser->id], // Exclude current car from unique validation
            'email' => ['required', 'email', 'max:100', 'unique:company_users,email,' . $CompanyUser->id],
            'user_type_id' => 'required',

        ], $messages);
        try {
            DB::transaction(function () use ($request, $id) {
                $CompanyUser_id = decrypt($id);
                $CompanyUser = CompanyUser::find($CompanyUser_id);

                $CompanyUser->username = $request->username;
                $CompanyUser->email = $request->email;
                $CompanyUser->user_type_id = $request->user_type_id;

                $CompanyUser->save();
            });
            Alert::success("تمت تعديل مستخدم شركة بنجاح");
            ActivityLogger::activity($request->name . "تمت تعديل  مستخدم سشركة بنجاح");

            return redirect()->route('company_users', $CompanyUser->companies_id);
        } catch (\Exception $e) {

            Alert::warning(" فشل تعديل   مستخدم شركة ");
            ActivityLogger::activity($e->getMessage()  . " فشل تعديل مستخدم  شركة ");

            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyUser $companyUser)
    {
        //
    }
    function showChangePasswordForm($id)
    {

        return view('dashbord.company_users.change_form');
    }

    public function changePassword(Request $request, $id)
    {

        $CompanyUser_id = decrypt($id);
        $CompanyUser = CompanyUser::find($CompanyUser_id);
        $messages =

        [
            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' => trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'new-password' => ['required', 'string', 'min:6'],
            'new-password-confirm' => ['required', 'same:new-password', 'string', 'min:6'],
        ], $messages);
        //Change Password
        $user = $CompanyUser;
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Log password change
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور للمستخدم ' . ($user->name ?? $user->username),
            'user_name' => $user->name ?? $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->name ?? $user->username,
            'company_name' => $user->companies->name ?? null,
            'office_name' => null, // Company users don't belong to an office
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تحديث أمني',
        ]);

        Alert::success(trans('users.changesecc'));
        return redirect()->route('company_users', $CompanyUser->companies_id);
    }
}
