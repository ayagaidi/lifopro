<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Region;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        ActivityLogger::activity("عرض صفحة الشركات");

        return view('dashbord.company.index');
    }


    public function companies()
    {

        $Company = Company::with(['cities','regions'])->select('*')->orderBy('created_at', 'DESC');
        return datatables()->of($Company)
            ->addColumn('edit', function ($Company) {
          
                    $company_id = encrypt($Company->id);

                     return '<a style="color: #f97424;" href="' . route('company/edit', $company_id) . '"><i  class="fa  fa-edit" > </i></a>';
                
            })
            ->addColumn('img', function ($company) {
                if ($company->logo) {
                    return '<img src="' . asset('logo/companies/' . $company->logo) . '" alt="Profile Image">';
                } else {
                    return 'لايوجد';
                }
            })
            ->addColumn('website', function ($company) {
                if ($company->website) {
                    return '<a target="_blank" style="color: blue;" href="' . $company->website . '"><i  class="fa  fa-map" > </i></a>';
                } else {
                    return 'لايوجد';
                }
            })
            ->addColumn('email', function ($company) {
                if ($company->email) {
                    return $company->email;
                } else {
                    return 'لايوجد';
                }
            })
            ->addColumn('phonenumber_manger', function ($company) {
                if ($company->phonenumber_manger) {
                    return $company->phonenumber_manger;
                } else {
                    return 'لايوجد';
                }
            })
            ->addColumn('changeStatus', function ($company) {
                $company = encrypt($company->id);

                return '<a href="' . route('company/changeStatus', $company) . '"><i  class="fa  fa-refresh"> </i></a>';
            })
            ->addColumn('company_users', function ($company) {
                $company = $company->id;

                return '<a href="' . route('company_users', $company) . '"><i  class="fa  fa-users"> </i></a>';
            })
            ->rawColumns(['edit','company_users', 'changeStatus', 'phonenumber_manger','img', 'website'])


            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        ActivityLogger::activity("عرض اضافة شركة الشركات");
        $regions = Region::get();

        return view('dashbord.company.create')
            ->with('regions', $regions);
    }



    public function getCity($id)
    {
        try {
            $City = City::where('regions_id', $id)->get();
            return response()->json($City);
        } catch (DecryptException $e) {
            abort(404, 'هذه الصفحة غير موجودة');
        }
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

            'regions_id.required' => "اخترالمنطقة",
            'password_confirmation.required' => "قم بادخال تاكيد كلمة المرور  ",

        ];
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:companies',
            'phonenumber' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'email' => 'nullable|string|email|unique:companies', // Assuming users table stores company email
            'website' => 'nullable|string|max:255',
            'fullname_manger' => 'required|string|max:255',
            'phonenumber_manger' => 'required|string|max:255',
            'regions_id' => 'nullable|integer|exists:regions,id', // Assuming regions table
            'cities_id' => 'nullable|integer|exists:cities,id', // Assuming cities table
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:company_users', // Assuming users table stores company username
            'password' => 'required|string|min:8|confirmed',
            'img' => 'nullable',
            'password_confirmation' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $companies = new Company();
                $companies->name = $request->name;
                $companies->phonenumber = $request->phonenumber;
                $companies->code = $request->code;
                $companies->website = $request->website;
                $companies->email = $request->email;
                $companies->fullname_manger = $request->fullname_manger;
                $companies->phonenumber_manger = $request->phonenumber_manger;
                $companies->regions_id = $request->regions_id;
                $companies->cities_id = $request->cities_id;
                $companies->address = $request->address;

                if($request->file('img')){

               
                $fileObject = $request->file('img');

                $filereq = "company" . time() . ".png";
                $path = $fileObject->move('logo/companies/', $filereq);
                $companies->logo = $filereq;
            }
                $companies->save();

                $company_users = new CompanyUser();
                $company_users->username = $request->username;
                $company_users->password = Hash::make($request->password);
                $company_users->fullname = $request->username;


                $company_users->user_type_id = 1;
                $company_users->companies_id =  $companies->id;

                $company_users->save();
            });
            Alert::success("تمت اضافة شركة بنجاح");
            ActivityLogger::activity($request->name . "تمت اضافة شركة بنجاح");

            return redirect()->route('company');
        } catch (\Exception $e) {

            Alert::warning(" فشل اضافة  شركة ");
            ActivityLogger::activity($e->getMessage()  . " فشل اضافة  شركة ");

            return redirect()->back();
        }
    }


    

     public function changeStatus(Request $request, $id)
    {
        $companies_id = decrypt($id);
        $companies = Company::find($companies_id);
        try {
            DB::transaction(function () use ($request, $id) {
                $companies_id = decrypt($id);
                $companies = Company::find($companies_id);
                if ($companies->active == 1) {
                    $active = 0;
                    $companies->active = $active;
                    $companies->save();

                    // تعطيل جميع المكاتب تحت الشركة
                    \App\Models\Office::where('companies_id', $companies->id)->update(['active' => $active]);
                    // تعطيل جميع المستخدمين التابعين للمكاتب
                    $officeIds = \App\Models\Office::where('companies_id', $companies->id)->pluck('id');
                    \App\Models\OfficeUser::whereIn('offices_id', $officeIds)->update(['active' => $active]);
                    // تعطيل جميع المستخدمين التابعين للشركة
                    \App\Models\CompanyUser::where('companies_id', $companies->id)->update(['active' => $active]);
                } else {
                    $active = 1;
                    $companies->active = $active;
                    $companies->save();
                      // تفعيل جميع المكاتب تحت الشركة
                    \App\Models\Office::where('companies_id', $companies->id)->update(['active' => $active]);
                    // تفعيل جميع المستخدمين التابعين للمكاتب
                    $officeIds = \App\Models\Office::where('companies_id', $companies->id)->pluck('id');
                    \App\Models\OfficeUser::whereIn('offices_id', $officeIds)->update(['active' => $active]);
                    // تفعيل جميع المستخدمين التابعين للشركة
                    \App\Models\CompanyUser::where('companies_id', $companies->id)->update(['active' => $active]);
         
                    // لا يتم تفعيل المكاتب والمستخدمين تلقائياً عند التفعيل
                }
            });
            ActivityLogger::activity($companies->name . "تمت عملية تغيير حالة الشركة");
            Alert::success("تمت عملية تغيير حالة الشركة");

            return redirect('company');
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة الشركة");
            ActivityLogger::activity($e->getMessage()."فشل تغيير حالة الشركة");

            return redirect('company');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $companyy)
    { 

            $company_id = decrypt($companyy);
            $companyy = Company::find($company_id);
            ActivityLogger::activity("صفحة تعديل بيانات شركة");
            $regions = Region::get();

             
            return view('dashbord.company.edit')
            ->with('companyy', $companyy)
            ->with('regions', $regions);
       

   
     
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $company_id = decrypt($id);
        $company = Company::find($company_id);
        $messages = [
            'name.required' => "ادخل  الشركة ",
            'phonenumber.required' => "ادخل رقم الهاتف",
            'code.required' => "ادخل رمز الشركة",
            'email.required' => "ادخل  البريد الألكتروني  ",
            'website.required' => "ادخل  الموقع الالكتروني  ",
            'fullname_manger.required' => "ادخل  مدير الشركة (المندوب)  ",
            's.required' => "ادخل  رقم هاتف المدير (المندوب)  ",
            'address.required' => "ادخل العنوان ",
          
            'img.required' => "ادخل  صورة",

            'cities_id.required' => "اخترالمنطقة",

            'regions_id.required' => "اخترالمنطقة",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:companies,name,' . $company->id], // Exclude current car from unique validation

            'phonenumber' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'email' => ['required', 'string', 'unique:companies,email,' . $company->id], // Exclude current car from unique validation

            'website' => 'nullable|string|max:255',
            'fullname_manger' => 'required|string|max:255',
            'phonenumber_manger' => 'required|string|max:255',
            'regions_id' => 'nullable|integer|exists:regions,id', // Assuming regions table
            'cities_id' => 'nullable|integer|exists:cities,id', // Assuming cities table
            'address' => 'required|string|max:255',
          

        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $company_id = decrypt($id);
                $companies = Company::find($company_id);
                $companies->name = $request->name;
                $companies->phonenumber = $request->phonenumber;
                $companies->code = $request->code;
                $companies->website = $request->website;
                $companies->email = $request->email;
                $companies->fullname_manger = $request->fullname_manger;
                $companies->phonenumber_manger = $request->phonenumber_manger;
                $companies->regions_id = $request->regions_id;
                $companies->cities_id = $request->cities_id;
                $companies->address = $request->address;
               
                if (!$request->file('img')) {
                } else {
                $fileObject = $request->file('img');
                if($companies->logo==""){   
                              
                    $fileObject = $request->file('img');

                    $filereq = "company" . time() . ".png";
                   
                }else{
                    $filereq =$companies->logo;
                }
                $path = $fileObject->move('logo/companies/', $filereq);
               
                $companies->logo = $filereq;
            }
                $companies->save();

            });
            Alert::success("تمت تعديل شركة بنجاح");
            ActivityLogger::activity($request->name . "تمت تعديل شركة بنجاح");

            return redirect()->route('company');
        } catch (\Exception $e) {

            Alert::warning(" فشل تعديل  شركة ");
            ActivityLogger::activity($e->getMessage()  . " فشل تعديل  شركة ");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
