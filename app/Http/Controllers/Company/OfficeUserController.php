<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Models\OfficeUserPermissions;
use App\Models\OfficeUserRole;
use App\Models\UserType;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Illuminate\Support\Facades\Auth;
class OfficeUserController extends Controller
{
        public function __construct()
    {
    $this->middleware('auth:companys'); // فرض أن المستخدم داخل guard companys

    $this->middleware(function ($request, $next) {
        $user = Auth::guard('companys')->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // تحقق من نوع المستخدم
        if ($user->userType->id == 1) {
            return $next($request); // مسموح للمستخدم من نوع 1
        }

        if ($user->userType->id == 2) {
            $hasPermission = \App\Models\CompanyUserRole::where('company_users_id', $user->id)
                ->where('company_user_permissions_id', 2)
                ->first();

            if ($hasPermission) {
                return $next($request);
            } else {
                abort(403, 'ليس لديك صلاحية الوصول.');
            }
        }

        // أي نوع آخر غير مسموح
        abort(403, 'لديك صلاحية الوصول.');
    });
}
    /**
     * Display a listing of the resource.
     */
    public function index($officeid)
    {
        ActivityLogger::activity("عرض مستخدمين المكتب");

        return view('comapny.offices_users.index')
        ->with('officeid', $officeid)
            ;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($officeid)
    {
        ActivityLogger::activity("عرض اضافة  مستخدم  المكتب ");
        $Office = Office::find($officeid);
        $user_types = UserType::all();
        $Permisson = OfficeUserPermissions::get();

        return view('comapny.offices_users.create')

        
        ->with('officeid', $officeid)
        ->with('user_types', $user_types)
        ->with('Permisson', $Permisson)
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
            if( $request->fullname){
                $OfficeUsers->fullname = $request->fullname;

            }else{
                $OfficeUsers->fullname = $request->username;

            }

                $OfficeUsers->user_type_id = $request->user_type_id;
                $OfficeUsers->offices_id =  $officesid;

                $OfficeUsers->save();

                $selectedPermissions = $request->input('permisson');
                // dd($request->input('permisson'));




                foreach ($selectedPermissions as $companyUserRole) {
                    // Handle and save the data as needed
                    $OfficeUserRole = new OfficeUserRole();
                    $OfficeUserRole->office_users_id = (int)$OfficeUsers->id;
                    $OfficeUserRole->office_user_permissions_id = $companyUserRole;
                    $OfficeUserRole->save();
                }
            });
            Alert::success("تمت اضافة  مستخدم مكتب بنجاح");
            return redirect()->route('company/offices_users',$officesid);

        } catch (\Exception $e) {

            Alert::warning(" فشل اضافة مستخدم  مكتب شركة ");

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

                     return '<a style="color: #f97424;" href="' . route('company/offices_users/edit', ['id'=>$offices_users_id,'offices_id'=>$offices_id]) . '"><i  class="fa  fa-edit" > </i></a>';
                
            })
         
            ->addColumn('changeStatus', function ($offices_users) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a href="' . route('company/offices_users/changeStatus', $offices_users_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })

            ->addColumn('changepassord', function ($offices_users) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a href="' . route('company/offices_users/changep/edit', $offices_users_id) . '"><i  class="fa  fa-lock"> </i></a>';
            })
            ->addColumn('showpermission', function ($offices_users) {

                $offices_users_id = encrypt($offices_users->id);
                $OfficeUser = OfficeUser::find($offices_users->id);
                if ($OfficeUser->user_type_id == 1) {
                    return 'كافة الصلاحيات';
                } else {
                    return '<a href="' . route('company/offices_users/showpermission', $offices_users_id) . '"><i  class="fa  fa-file"> </i></a>';
                }
            })   


            ->rawColumns(['edit','showpermission', 'changeStatus','changepassord'])


            ->make(true);
    }



    function deletePermission($permissionId)
    {
        try {
            // Find the permission to be deleted
            $permission = OfficeUserRole::findOrFail($permissionId);

            // Check if the permission is associated with any roles

            // Delete the permission
            $permission->delete();

            Alert::success("تمت عملية الحدف    بنجاح");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning($e . " فشل حذف صلاحية مستخدم شركة ");

            return redirect()->back();
        }
    }

    public function showpermission($offices_users)
    {
        $offices_users_id = decrypt($offices_users);
        $offices_users = OfficeUser::find($offices_users_id);
        $role = OfficeUserRole::with('office_user_permissions')->where('office_users_id', $offices_users_id)
        ->whereHas('office_user_permissions', function($q){
            $q->whereNotNull('name');        })->get();        return view('comapny.offices_users.premission')
            ->with('role', $role)
            ->with('offices_users', $offices_users)
        ;
    }

    public function showChangePasswordForm($id)
    {
     

        return view('comapny.offices_users.change_form');
    }

    public function changePassword(Request $request,$id)
    {
        

        $OfficeUser_id = decrypt($id);
        $OfficeUser = OfficeUser::find($OfficeUser_id);

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
        
        // Log password change for office user
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور لحساب مستخدم المكتب',
            'user_name' => $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->username,
            'company_name' => $user->offices->companies->name ?? null,
            'office_name' => $user->offices->name ?? null,
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تغيير كلمة المرور',
        ]);
        
        Alert::success(trans('users.changesecc'));
                return redirect()->route('company/offices_users', $OfficeUser->offices_id);

        // return redirect()->back();
    }
    /**
     * Display the specified resource.
     */
    public function show(OfficeUser $officeUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($offices)
    { 

            $offices_id = decrypt($offices);
            $officesUser = OfficeUser::find($offices_id);
            ActivityLogger::activity("صفحة تعديل بيانات  مستخدم مكتب");
            $Permisson = OfficeUserPermissions::get();
            $user_types = UserType::all();
            return view('comapny.offices_users.edit')
            ->with('officesUser', $officesUser)
            ->with('Permisson', $Permisson)
            ->with('user_types', $user_types)
            ->with('offices_id', $offices_id)
          ;
       

   
     
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
            // Decrypt the user ID
            $decryptedId = decrypt($id);
            $officeUser = OfficeUser::find($decryptedId);

            // Get the selected permissions from the request
            $selectedPermissions = $request->input('permisson'); // Fixed typo
    
            DB::transaction(function () use ($request, $decryptedId, $selectedPermissions) {
                // Find and update the OfficeUser
                $officeUser = OfficeUser::find($decryptedId);
                if ($officeUser) {
                    $officeUser->username = $request->input('username');
                    $officeUser->user_type_id = $request->input('user_type_id');
                    $officeUser->save();
                }
    
                if( $selectedPermissions){
                    foreach ($selectedPermissions as $permissionId) {
                        $existingRole = OfficeUserRole::where('office_users_id', $decryptedId)
                            ->where('office_user_permissions_id', $permissionId)
                            ->first();
        
                        if ($existingRole) {
                            // If the role exists, you can choose to update it if needed
                            // For now, just ensure it exists in the database
                        } else {
                            // Create a new OfficeUserRole
                            $officeUserRole = new OfficeUserRole();
                            $officeUserRole->office_users_id =  $officeUser->id;
                            $officeUserRole->office_user_permissions_id = $permissionId;
                            $officeUserRole->save();
                        }
                    }
                }
                // Update or create OfficeUserRole entries
            
            });
    
            // Show success message and redirect
            Alert::success('تمت تعديل مستخدم مكتب بنجاح');
    
            // Assuming $offices_id is available in the context
            return redirect()->route('company/offices_users',$officeUser->offices_id);
    
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            Alert::error($e.'حدث خطأ أثناء تعديل المستخدم');
            return redirect()->back()->withInput();
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
        return redirect()->route('company/offices_users', $OfficeUser->offices_id);

        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة  مستخدم مكتب");
            ActivityLogger::activity($e->getMessage()."فشل تغيير حالة مستخدم مكتب");

            return redirect()->back();
        }
    }

}
