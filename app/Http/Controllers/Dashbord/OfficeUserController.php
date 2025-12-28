<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class OfficeUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index($officeid)
    {
        ActivityLogger::activity("عرض مستخدمين المكتب");

        $Office = Office::find($officeid);
        return view('dashbord.offices_users.index')
        ->with('officeid', $officeid)
            ->with('Office', $Office);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($officeid)
    {
        ActivityLogger::activity("عرض اضافة  مستخدم  المكتب ");
        $Office = Office::find($officeid);
        $user_types = UserType::where('id',1)->get();

        return view('dashbord.offices_users.create')
        ->with('officeid', $officeid)
        ->with('user_types', $user_types)

            ->with('Office', $Office);
           
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$officesid)
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
            
            'username' => 'required|string|max:255|unique:office_users', // Assuming users table stores company username
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => ['required'],
            'user_type_id' => ['required'],


        ], $messages);
        try {
            DB::transaction(function () use ($request,$officesid) {


                $OfficeUsers = new OfficeUser();
                $OfficeUsers->username = $request->username;
                $OfficeUsers->password = Hash::make($request->password);
                $OfficeUsers->fullname = $request->username;

                $OfficeUsers->user_type_id = $request->user_type_id;
                $OfficeUsers->offices_id =  $officesid;

                $OfficeUsers->save();
            });
            Alert::success("تمت اضافة  مستخدم مكتب بنجاح");
            ActivityLogger::activity($request->name . "تمت اضافةمستخدم  مكتب بنجاح");
            return redirect()->route('offices_users',$officesid);

        } catch (\Exception $e) {

            Alert::warning(" فشل اضافة  مستخدم مكتب  ");
            ActivityLogger::activity($e->getMessage()  . "  فشل اضافة   مستخدم مكتب ");

            return redirect()->back();
        }
    }



    public function offices_users($offices_id)
    {

        $offices_users = OfficeUser::with(['userType'])
        ->select('*')->
        where('offices_id',$offices_id)->orderBy('created_at', 'DESC');
        return datatables()->of($offices_users)
        ->addColumn('edit', function ($offices_users) use ($offices_id) {          
                    $offices_users_id = encrypt($offices_users->id);

                     return '<a style="color: #f97424;" href="' . route('offices_users/edit', ['id'=>$offices_users_id,'offices_id'=>$offices_id]) . '"><i  class="fa  fa-edit" > </i></a>';
                
            })
         
            ->addColumn('changeStatus', function ($offices_users) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a href="' . route('offices_users/changeStatus', $offices_users_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })
            ->addColumn('changepassordoffices_users', function ($offices_users) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a href="' . route('offices_users/changepassordoffices_users', $offices_users_id) . '"><i  class="fa  fa-lock"> </i></a>';
            })
          
          
            ->rawColumns(['edit', 'changeStatus','changepassordoffices_users'])


            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeUser $officeUser)
    {
        //
    }
    function showChangePasswordForm($id)
    {

        return view('dashbord.offices_users.change_form');
    }

    public function changePassword(Request $request, $id)
    {

        $offices_users_ids = decrypt($id);
        $OfficeUser = OfficeUser::find($offices_users_ids);
        $messages = [

            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' => trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'new-password' => ['required', 'string', 'min:6'],
            'new-password-confirm' => ['required', 'same:new-password', 'string', 'min:6'],
        ], $messages);
        //Change Password
        $user = $OfficeUser;
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Log password change
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور لمستخدم المكتب',
            'user_name' => $user->name ?? $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->name ?? $user->username,
            'company_name' => $user->offices->companies->name ?? null,
            'office_name' => $user->offices->name ?? null,
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تحديث أمني',
        ]);

        Alert::success(trans('users.changesecc'));
        return redirect()->route('offices_users', $OfficeUser->offices_id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($offices,$offices_id)
    { 

            $offices_id = decrypt($offices);
            $officesUser = OfficeUser::find($offices_id);
            ActivityLogger::activity("صفحة تعديل بيانات  مستخدم مكتب");
            $user_types = UserType::all();
            return view('dashbord.offices_users.edit')
            ->with('officesUser', $officesUser)
            ->with('user_types', $user_types)
            ->with('offices_id', $offices_id);
       

   
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id,$offices_id)
    {
        $OfficeUser_id = decrypt($id);
        $OfficeUser = OfficeUser::find($OfficeUser_id);
        $messages = [
            'username.required' => "ادخل  اسم المستخدم ",
           

            'user_type_id.required' => "اختر نوع الحساب",


        ];
        $this->validate($request, [
            'username' => ['required', 'string', 'unique:office_users,username,' . $OfficeUser->id], // Exclude current car from unique validation

            'user_type_id' => 'required',
          

        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $OfficeUser = decrypt($id);
                $OfficeUser = OfficeUser::find($OfficeUser);
                $OfficeUser->username=$request->username;
                $OfficeUser->user_type_id=$request->user_type_id;

                $OfficeUser->save();

            });
            Alert::success("تمت تعديل مستخدم مكتب بنجاح");
            ActivityLogger::activity($request->name . "تمت تعديل  مستخدم مكتب بنجاح");

            return redirect()->route('company_users',$OfficeUser->id);
        } catch (\Exception $e) {

            Alert::warning(" فشل تعديل   مستخدم مكتب ");
            ActivityLogger::activity($e->getMessage()  . " فشل تعديل مستخدم  مكتب ");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeUser $officeUser)
    {
        //
    }



    public function changeStatus(Request $request, $id)

    {
        $OfficeUser_id = decrypt($id);
        $OfficeUser = OfficeUser::find($OfficeUser_id);
        try {
            DB::transaction(function () use ($request, $id) {
                $OfficeUser_id = decrypt($id);
                $OfficeUser = OfficeUser::find($OfficeUser_id);
                if ($OfficeUser->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $OfficeUser->active = $active;
                $OfficeUser->save();
                
            });
            ActivityLogger::activity($OfficeUser->username . "تمت عملية تغيير حالة  مستخدم مكتب");
            Alert::success("تمت عملية تغيير حالة  مستخدم مكتب");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة  مستخدم مكتب");
            ActivityLogger::activity($e->getMessage()."فشل تغيير حالة مستخدم مكتب");

            return redirect()->back();
        }
    }

}
