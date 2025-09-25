<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Models\OfficeUserr;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
class OfficeController extends Controller
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
        ActivityLogger::activity("عرض  المكاتب");

        return view('dashbord.offices.index');
    }


 public function companyoffices()
    {


        $companyoffices = Office::with('companies')->selectRaw('count(id),companies_id')->groupBy('companies_id')
        ->orderBy('companies_id', 'desc')->get();
        return datatables()->of($companyoffices)
        ->addColumn('file', function ($companyoffices) {          
                    $companies_id = encrypt($companyoffices->companies_id);

                     return '<a style="color: #f97424;" href="' . route('offices/getall',$companies_id) . '"><i  class="fa  fa-folder" > </i></a>';
                
            })
         
        
          
            ->rawColumns(['file'])


            ->make(true);
    }

    public function indexof($comif)
    {
        $id=decrypt($comif);
        ActivityLogger::activity("عرض  مكاتب الشركات");
        $companyoffices = Office::with('companies')->where('companies_id',$id)->first();
        return view('dashbord.offices.indexof')->with('companyoffices',$companyoffices);
    }


    public function officesALL($comif)
    {

        $officesALL = Office::with(['companies','cities','regions'])
        ->where('companies_id',$comif)->select('*')->orderBy('created_at', 'DESC');
        return datatables()->of($officesALL)
            ->addColumn('edit', function ($officesALL) {
          
                    $officesALL_id = encrypt($officesALL->id);

                     return '<a style="color: #f97424;" href="' . route('offices/edit', $officesALL_id) . '"><i  class="fa  fa-edit" > </i></a>';
                
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
            ->addColumn('changeStatus', function ($offices) {
                $offices = encrypt($offices->id);

                return '<a href="' . route('offices/changeStatus', $offices) . '"><i  class="fa  fa-refresh"> </i></a>';
            })
            ->addColumn('company_users', function ($offices) {
                $offices = $offices->id;

                return '<a href="' . route('offices_users', $offices) . '"><i  class="fa  fa-users"> </i></a>';
            })
            ->rawColumns(['edit','company_users', 'changeStatus', 'phonenumber_manger'])


            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        ActivityLogger::activity("عرض اضافة مكتب المكاتب");
        $regions = Region::get();
        $Company = Company::get();

        return view('dashbord.offices.create')
        ->with('Company', $Company)
            ->with('regions', $regions);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => "ادخل  اسم المكتب ",
            'phonenumber.required' => "ادخل رقم الهاتف",
            'code.required' => "ادخل رمز المكتب",
            'email.required' => "ادخل  البريد الألكتروني  ",
            'website.required' => "ادخل  الموقع الالكتروني  ",
            'fullname_manger.required' => "ادخل  اسم مدير المكتب (المندوب)  ",
            'phonenumber_manger.required' => "ادخل  رقم هاتف المدير (المندوب)  ",
            'address.required' => "ادخل العنوان ",
            'username.required' => "ادخل  اسم المتسخدم  ",
            'password.required' => "ادخل  كلمة المرور",
            'img.required' => "ادخل  صورة",
            'companies_id.required' => "اختر الشركة",

            'cities_id.required' => "اخترالمنطقة",
            
            'regions_id.required' => "اخترالمنطقة",
            'password_confirmation.required' => "قم بادخال تاكيد كلمة المرور  ",

        ];
        $this->validate($request, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offices')->where(function ($query) use ($request) {
                    return $query->where('companies_id',  $request->companies_id);
                }),
            ],
            // 'name' => 'required|string|max:255|unique:offices',
            'phonenumber' => 'required|string|max:255',
            'email' => 'nullable|string|email|unique:offices', // Assuming users table stores company email
            'fullname_manger' => 'required|string|max:255',
            'phonenumber_manger' => 'required|string|max:255',
            'regions_id' => 'nullable|integer|exists:regions,id', // Assuming regions table
            'cities_id' => 'nullable|integer|exists:cities,id', // Assuming cities table
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:office_users', // Assuming users table stores company username
            'password' => 'required|string|min:8|confirmed',
            'companies_id' => 'required',
            'password_confirmation' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $Office = new Office();
                $Office->name = $request->name;
                $Office->phonenumber = $request->phonenumber;
                $Office->email ="empty@email.ly";
                $Office->fullname_manger = $request->fullname_manger;
                $Office->phonenumber_manger = $request->phonenumber_manger;
                $Office->regions_id = $request->regions_id;
                $Office->cities_id = $request->cities_id;
                $Office->address = $request->address;

                $Office->companies_id= $request->companies_id;

                $Office->save();

                $OfficeUser = new OfficeUser();
                $OfficeUser->username = $request->username;
                $OfficeUser->password = Hash::make($request->password);
                $OfficeUser->fullname = $request->username;


                $OfficeUser->user_type_id = 1;
                $OfficeUser->offices_id =  $Office->id;

                $OfficeUser->save();
            });
            Alert::success("تمت اضافة مكتب بنجاح");
            ActivityLogger::activity($request->name . "تمت اضافة مكتب بنجاح");

            return redirect()->route('offices');
        } catch (\Exception $e) {

            Alert::warning(" فشل اضافة  مكتب ");
            ActivityLogger::activity($e->getMessage()  . " فشل اضافة  مكتب ");

            return redirect()->back();
        }
    }




     public function changeStatus(Request $request, $id)

    {
        $Office_id = decrypt($id);
        $Office = Office::find($Office_id);
        try {
            DB::transaction(function () use ($request, $id) {
                $Office_id = decrypt($id);
                $Office = Office::find($Office_id);
                if ($Office->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $Office->active = $active;
                $Office->save();
                                OfficeUser::where('offices_id', $Office->id)->update(['active' => $active]);

                
            });
            ActivityLogger::activity($Office->name . "تمت عملية تغيير حالة المكتب");
            Alert::success("تمت عملية تغيير حالة المكتب");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة المكتب");
            ActivityLogger::activity($e->getMessage()."فشل تغيير حالة المكتب");

            return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $office)
    { 

            $office_id = decrypt($office);
            $Office = Office::find($office_id);
            ActivityLogger::activity("صفحة تعديل بيانات مكتب");
            $regions = Region::get();

            $Company = Company::get();

            return view('dashbord.offices.edit')
            ->with('Office', $Office)
            ->with('Company', $Company)

            ->with('regions', $regions);
       

   
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $offfice)
    {
        $Office_id = decrypt($offfice);
        $office = Office::find($Office_id);
        $messages = [
            'name.required' => "ادخل  اسم المكتب ",
            'phonenumber.required' => "ادخل رقم الهاتف",
            'code.required' => "ادخل رمز المكتب",
            'email.required' => "ادخل  البريد الألكتروني  ",
            'website.required' => "ادخل  الموقع الالكتروني  ",
            'fullname_manger.required' => "ادخل  اسم مدير المكتب (المندوب)  ",
            'phonenumber_manger.required' => "ادخل  رقم هاتف المدير (المندوب)  ",
            'address.required' => "ادخل العنوان ",
            'username.required' => "ادخل  اسم المتسخدم  ",
            'password.required' => "ادخل  كلمة المرور",
            'img.required' => "ادخل  صورة",
            'companies_id.required' => "اختر الشركة",

            'cities_id.required' => "اخترالمنطقة",
            
            'regions_id.required' => "اخترالمنطقة",
            'password_confirmation.required' => "قم بادخال تاكيد كلمة المرور  ",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:offices,name,' . $office->id], // Exclude current car from unique validation

            'email' => ['required', 'string', 'unique:offices,email,' . $office->id], // Exclude current car from unique validation

  
            'phonenumber' => 'required|string|max:255',
            'fullname_manger' => 'required|string|max:255',
            'phonenumber_manger' => 'required|string|max:255',
            'regions_id' => 'required|integer|exists:regions,id', // Assuming regions table
            'cities_id' => 'required|integer|exists:cities,id', // Assuming cities table
            'address' => 'required|string|max:255',
            'companies_id' => 'required',

        ], $messages);
        try {
            DB::transaction(function () use ($request,$offfice) {

                $Office_id = decrypt($offfice);
                $Office = Office::find($Office_id);             
                   $Office->name = $request->name;
                $Office->phonenumber = $request->phonenumber;
                $Office->email = $request->email;
                $Office->fullname_manger = $request->fullname_manger;
                $Office->phonenumber_manger = $request->phonenumber_manger;
                $Office->regions_id = $request->regions_id;
                $Office->cities_id = $request->cities_id;
                $Office->address = $request->address;

                $Office->companies_id= $request->companies_id;

                $Office->save();

               
            });
            Alert::success("تمت تعديل بيانات مكتب بنجاح");
            ActivityLogger::activity($request->name . "تمت  تعديل بيانات مكتب بنجاح");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning(" فشل  تعديل بيانات  مكتب ");
            ActivityLogger::activity($e->getMessage()  . " فشل  تعديل بيانات  مكتب ");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        //
    }
}
