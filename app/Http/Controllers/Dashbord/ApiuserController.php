<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Apiuser;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class ApiuserController extends Controller
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
        ActivityLogger::activity("عرض كافة حسابات");

        return view('dashbord.apiuser.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        ActivityLogger::activity("عرض اضافة  حساب api   ");
        $Company = Company::get();

        return view('dashbord.apiuser.create')

            ->with('Company', $Company);
           
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'companies_id.required' => "اختر   الشركة ",
            'username.required' => "ادخل  اسم المتسخدم  ",
            'password.required' => "ادخل  كلمة المرور",


            'password_confirmation.required' => "قم بادخال تاكيد كلمة المرور  ",

        ];
        $this->validate($request, [
            
            'username' => 'required|string|max:255|unique:company_users', // Assuming users table stores company username
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => ['required'],
            'companies_id' => ['required'],


        ], $messages);

        $exist=Apiuser::where('companies_id',$request->companies_id)->first();
      
        if(!$exist){
            try {
                DB::transaction(function () use ($request) {
    
    
                    $Apiuser = new Apiuser();
                    $Apiuser->username = $request->username;
                    $Apiuser->password = encrypt($request->password);
                    $Apiuser->companies_id =  $request->companies_id;
    
                    $Apiuser->save();
                });
                Alert::success("تمت   حساب API شركة بنجاح");
                ActivityLogger::activity($request->username ."تمت   حساب API شركة بنجاح");
    
                return redirect()->route('apiuser');
            } catch (\Exception $e) {
    
                Alert::warning(" فشل اضافة  api شركة ");
                ActivityLogger::activity($e->getMessage()  ." فشل اضافة  api شركة ");
    
                return redirect()->back();
            }
        }else{
         
            Alert::warning("هذه الحساب موجودة مسبقا بامكانك تعديل بياناتها");
            ActivityLogger::activity($request->companies_id."هذه الحساب موجودة مسبقا بامكانك تعديل بياناتها");

            return redirect()->back();   
        }
       
    }


    public function apiuser()
    {
    
        $Apiuser = Apiuser::with('companies')->orderBy('created_at', 'DESC');
        return datatables()->of($Apiuser)
        ->addColumn('edit', function ($Apiuser) {
            $Apiuser_id = encrypt($Apiuser->id);
        
            return '<a style="color: #f97424;" href="' . route('apiuser/edit',$Apiuser_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
         ->addColumn('password', function ($Apiuser) {
            $password = decrypt($Apiuser->password);
        
            //return $password ;
                        return '*******' ;

        })
        
        ->rawColumns(['edit','password'])


            ->make(true);
    }
    /**
     * Display the specified resource.
     */
    public function show(Apiuser $apiuser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($apiuser)
    {

        $apiuser_id = decrypt($apiuser);
        $Apiuser = Apiuser::find($apiuser_id);
        $Company = Company::get();

        ActivityLogger::activity("عرض تعديل  حساب api   ");
        $Company = Company::get();
        return view('dashbord.apiuser.edit')
            ->with('Company', $Company)
            ->with('Apiuser', $Apiuser);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$apiuser)
    {
        $apiuser_id = decrypt($apiuser);
        $Apiuser = Apiuser::find($apiuser_id);
        $messages = [
            'username.required' => "ادخل  اسم المتسخدم  ",
            'password.required' => "ادخل  كلمة المرور",


            'password_confirmation.required' => "قم بادخال تاكيد كلمة المرور  ",

        ];
        $this->validate($request, [
            
            'username' => 'required|string|max:255|unique:company_users', // Assuming users table stores company username
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => ['nullable'],


        ], $messages);

        
            try {
                DB::transaction(function () use ($request,$apiuser) {
    
                    $apiuser_id = decrypt($apiuser);
                    $Apiuser = Apiuser::find($apiuser_id);
                    $Apiuser->username = $request->username;
                    if($request->password)
                    {
                        $Apiuser->password = encrypt($request->password);

                    }
    
                    $Apiuser->save();
                });
                Alert::success("تمت   تعديل حساب API شركة بنجاح");
                ActivityLogger::activity($request->username ."تمت    تعديل حساب API شركة بنجاح");
    
                return redirect()->route('apiuser');
            } catch (\Exception $e) {
    
                Alert::warning(" فشل  تعديل حساب  api شركة ");
                ActivityLogger::activity($e->getMessage()  ." فشل  تعديل حساب  api شركة ");
    
                return redirect()->back();
            }
    
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apiuser $apiuser)
    {
        //
    }
}
