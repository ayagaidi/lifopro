<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserPermissions;
use App\Models\CompanyUserRole;
use App\Models\UserType;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class CompanyUserController extends Controller
{
   public function __construct()
{
        $this->middleware('auth:companys');
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

        return view('comapny.company_users.index');
    }


    public function company_users()
    {

        $company_users = CompanyUser::with(['userType'])
            ->select('*')->where('id', '!=', Auth::user()->id)
            ->where('companies_id', Auth::user()->companies_id)->orderBy('created_at', 'DESC');
        return datatables()->of($company_users)
            ->addColumn('edit', function ($company_users) {
                $company_users_id = encrypt($company_users->id);

                return '<a style="color: #f97424;" href="' . route('company/company_users/edit', ['id' => $company_users_id]) . '"><i  class="fa  fa-edit" > </i></a>';
            })

            ->addColumn('changeStatus', function ($company_users) {
                $company_users_id = encrypt($company_users->id);

                return '<a href="' . route('company/company_users/changeStatus', $company_users_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })

            ->addColumn('showpermission', function ($company_users) {

                $company_users_id = encrypt($company_users->id);
                $comapnyuser = CompanyUser::find($company_users->id);
                if ($comapnyuser->user_type_id == 1) {
                    return 'كافة الصلاحيات';
                } else {
                    return '<a href="' . route('company/company_users/showpermission', $company_users_id) . '"><i  class="fa  fa-file"> </i></a>';
                }
            })
            ->addColumn('changepassord', function ($company_users) {
                $company_users_id = encrypt($company_users->id);

                return '<a href="' . route('company/changepassord/edit', $company_users_id) . '"><i  class="fa  fa-lock"> </i></a>';
            })


            ->rawColumns(['edit', 'changeStatus','changepassord', 'showpermission'])


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
            Alert::success("تمت عملية تغيير حالة  مستخدم الشركة");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة  مستخدم الشركة");

            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_types = UserType::all();

        $Permisson = CompanyUserPermissions::get();
        return view('comapny.company_users.create')
            ->with('Permisson', $Permisson)
            ->with('user_types', $user_types)

        ;
    }

    public function showChangePasswordForm($id)
    {


        return view('comapny.company_users.change_form');
    }

    public function changePassword(Request $request,$id)
    {

        $CompanyUser_id = decrypt($id);
        $CompanyUser = CompanyUser::find($CompanyUser_id);

        $messages = [

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
        
        // Log password change for API user
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور لحساب مستخدم الشركة',
            'user_name' => $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->username,
            'company_name' => $user->companies->name ?? null,
            'office_name' => null, // Company users don't belong to an office
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تغيير كلمة المرور',
        ]);
        
        Alert::success(trans('users.changesecc'));
                   return redirect()->route('company/company_users');


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

            'username' => 'required|string|max:255|unique:company_users', // Assuming users table stores company username
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => ['required'],
            'user_type_id' => ['required'],


        ], $messages);
        try {
            if (is_null($request->input('permisson')) == true) {
                Alert::warning(" يجب تحديد صلاحية واحده ع الاقل");

                return redirect()->back();
            } else {
            DB::transaction(function () use ($request) {


                $company_users = new CompanyUser();
                $company_users->username = $request->username;
                $company_users->password = Hash::make($request->password);
                
                if($request->fullname)
                {
                    $company_users->fullname = $request->fullname;

                }else{
                    $company_users->fullname = $request->username;

                }

                $company_users->user_type_id = $request->user_type_id;
                $company_users->companies_id =  Auth::user()->companies_id;

                $company_users->save();
                $selectedPermissions = $request->input('permisson');
                // dd($request->input('permisson'));
                if ($request->user_type_id == 2) {


                    if (is_null($request->input('permisson')) == true) {
                        Alert::warning(" يجب تحديد صلاحية واحده ع الاقل");

                        return redirect()->back();
                    } else {
                        foreach ($selectedPermissions as $companyUserRole) {
                            // Handle and save the data as needed
                            $newCompanyUserRole = new CompanyUserRole();
                            $newCompanyUserRole->company_users_id = (int)$company_users->id;
                            $newCompanyUserRole->company_user_permissions_id = $companyUserRole;
                            $newCompanyUserRole->save();
                        }
                    }
                }
            });
            Alert::success("تمت اضافة  مستخدم شركة بنجاح");

            return redirect()->route('company/company_users');
     }   } catch (\Exception $e) {

            Alert::warning($e . " فشل اضافة مستخدم شركة ");

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function showpermission($companyUser)
    {
        $CompanyUser_id = decrypt($companyUser);
        $CompanyUser = CompanyUser::find($CompanyUser_id);
        $role = CompanyUserRole::with('company_user_permissions')->where('company_users_id', $CompanyUser_id)->get();
        return view('comapny.company_users.premission')
            ->with('role', $role)
            ->with('CompanyUser', $CompanyUser)
        ;
    }

    function deletePermission($permissionId)
    {
        try {
            // Find the permission to be deleted
            $permission = CompanyUserRole::findOrFail($permissionId);

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
     * Show the form for editing the specified resource.
     */
    public function edit($companyy)
    {

        $CompanyUser_id = decrypt($companyy);
        $CompanyUser = CompanyUser::find($CompanyUser_id);

        $user_types = UserType::all();
        $Permisson = CompanyUserPermissions::get();
        return view('comapny.company_users.edit')
        ->with('Permisson', $Permisson)
            ->with('CompanyUser', $CompanyUser)
            ->with('user_types', $user_types)
        ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $CompanyUser_id = decrypt($id);
        $CompanyUser = CompanyUser::find($CompanyUser_id);
        $messages = [
            'username.required' => "ادخل  اسم المستخدم ",


            'user_type_id.required' => "اختر نوع الحساب",


        ];
        $this->validate($request, [
            'username' => ['required', 'string', 'unique:company_users,username,' . $CompanyUser->id], // Exclude current car from unique validation

            'user_type_id' => 'required',


        ], $messages);
        try {
            DB::transaction(function () use ($request, $id) {

                $CompanyUser_id = decrypt($id);
                $CompanyUser = CompanyUser::find($CompanyUser_id);
                $CompanyUser->username = $request->username;
                $CompanyUser->user_type_id = $request->user_type_id;

                $CompanyUser->save();

                $selectedPermissions = $request->input('permisson');
                // dd($request->input('permisson'));
                // if ($request->user_type_id == 2) {


                //     if (is_null($request->input('permisson')) == true) {
                //         Alert::warning(" يجب تحديد صلاحية واحده ع الاقل");

                //         return redirect()->back();
                //     } else {
                //         foreach ($selectedPermissions as $companyUserRole) {
                //             // Handle and save the data as needed
                //             $newCompanyUserRole = new CompanyUserRole();
                //             $newCompanyUserRole->company_users_id = (int)$CompanyUser->id;
                //             $newCompanyUserRole->company_user_permissions_id = $companyUserRole;
                //             $newCompanyUserRole->save();
                //         }
                //     }
                // }


                if( $selectedPermissions){
                    foreach ($selectedPermissions as $permissionId) {
                        $existingRole = CompanyUserRole::where('company_users_id', $CompanyUser->id)
                            ->where('company_user_permissions_id', $permissionId)
                            ->first();
        
                        if ($existingRole) {
                            // If the role exists, you can choose to update it if needed
                            // For now, just ensure it exists in the database
                        } else {
                            $newCompanyUserRole = new CompanyUserRole();
                                        $newCompanyUserRole->company_users_id = (int)$CompanyUser->id;
                                        $newCompanyUserRole->company_user_permissions_id = $permissionId;
                                        $newCompanyUserRole->save();
                        }
                    }
                }
            });
            Alert::success("تمت تعديل مستخدم شركة بنجاح");

            return redirect()->route('company/company_users');
        } catch (\Exception $e) {

            Alert::warning($e . " فشل تعديل   مستخدم شركة ");

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
}
