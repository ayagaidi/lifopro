<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Models\OfficeUserPermissions;
use App\Models\OfficeUserRole;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class OfficeUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:officess');
         $this->middleware(function ($request, $next) {
        if (Auth::check() && Auth::user()->userType->id == 1) {
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    });
    }
    
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('office.offices_users.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $Permisson = OfficeUserPermissions::get();

        return view('office.offices_users.create')
            ->with('Permisson', $Permisson);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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


        ], $messages);
        try {
            if (is_null($request->input('permisson')) == true) {
                Alert::warning(" يجب تحديد صلاحية واحده ع الاقل");

                return redirect()->back();
            } else {
                DB::transaction(function () use ($request) {


                    $OfficeUsers = new OfficeUser();
                    $OfficeUsers->username = $request->username;
                    $OfficeUsers->password = Hash::make($request->password);

                    if( $request->fullname){
                        $OfficeUsers->fullname = $request->fullname;

                    }else{
                        $OfficeUsers->fullname = $request->username;

                    }

                    $OfficeUsers->user_type_id = 2;
                    $OfficeUsers->offices_id = Auth::user()->offices_id;

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
                return redirect()->route('office/offices_users');
            }
        } catch (\Exception $e) {

            Alert::warning($e . " فشل اضافة مستخدم  مكتب شركة ");

            return redirect()->back();
        }
    }



    public function offices_users()
    {

        $offices_id = Auth::user()->offices_id;
        $offices_users = OfficeUser::with(['userType'])
            ->select('*')->where('offices_id', $offices_id)
            ->where('id', '!=', Auth::user()->id)->orderBy('created_at', 'DESC');
        return datatables()->of($offices_users)
            ->addColumn('edit', function ($offices_users) use ($offices_id) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a style="color: #f97424;" href="' . route('office/offices_users/edit', ['id' => $offices_users_id]) . '"><i  class="fa  fa-edit" > </i></a>';
            })

            ->addColumn('changeStatus', function ($offices_users) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a href="' . route('office/offices_users/changeStatus', $offices_users_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })

            ->addColumn('showpermission', function ($offices_users) {

                $offices_users_id = encrypt($offices_users->id);
                $OfficeUser = OfficeUser::find($offices_users->id);
                if ($OfficeUser->user_type_id == 1) {
                    return 'كافة الصلاحيات';
                } else {
                    return '<a href="' . route('office/offices_users/showpermission', $offices_users_id) . '"><i  class="fa  fa-file"> </i></a>';
                }
            })     ->addColumn('changepassord', function ($offices_users) {
                $offices_users_id = encrypt($offices_users->id);

                return '<a href="' . route('office/offices_users/changepassord', $offices_users_id) . '"><i  class="fa  fa-lock"> </i></a>';
            })


           


            ->rawColumns(['edit', 'changeStatus','changepassord', 'showpermission'])


            ->make(true);
    }


    public function showpermission($offices_users)
    {
        $offices_users_id = decrypt($offices_users);
        $offices_users = OfficeUser::find($offices_users_id);
        $role = OfficeUserRole::with('office_user_permissions')->where('office_users_id', $offices_users_id)
        ->whereHas('office_user_permissions', function($q){
            $q->whereNotNull('name');        })->get();

        return view('office.offices_users.premission')
            ->with('role', $role)
            ->with('offices_users', $offices_users)
        ;
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
        $Permisson = OfficeUserPermissions::get();


        return view('office.offices_users.edit')
            ->with('officesUser', $officesUser)
            ->with('Permisson', $Permisson)
            ->with('offices_id', $offices_id)
        ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $OfficeUser_id = decrypt($id);
        $OfficeUser = OfficeUser::find($OfficeUser_id);
        $messages = [
            'username.required' => "ادخل  اسم المستخدم ",


            'user_type_id.required' => "اختر نوع الحساب",


        ];
        $this->validate($request, [
            'username' => ['required', 'string', 'unique:office_users,username,' . $OfficeUser->id], // Exclude current car from unique validation



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
            return redirect()->route('office/offices_users',$officeUser->offices_id);
    
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


    public function showChangePasswordForm($id)
    {


        return view('office.offices_users.change_form');
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
        Alert::success(trans('users.changesecc'));
        return redirect()->route('office/offices_users');
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
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة مستخدم مكتب");

            return redirect()->back();
        }
    }
}
