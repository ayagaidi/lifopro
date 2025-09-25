<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class CountryController extends Controller
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
        ActivityLogger::activity("عرض كافة الدول");

        return view('dashbord.country.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        ActivityLogger::activity("اضافة دولة عرض صفحة");

        return view('dashbord.country.create');
    }



    public function country()
    {
    
        $country = Country::orderBy('created_at', 'DESC');
        return datatables()->of($country)
        ->addColumn('edit', function ($country) {
            $ccountry_id = encrypt($country->id);
        
            return '<a style="color: #f97424;" href="' . route('country/edit',$ccountry_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('changeStatus', function ($country) {
            $ccountry_id = encrypt($country->id);

            return '<a href="' . route('country/changeStatus', $ccountry_id) . '"><i  class="fa  fa-refresh"> </i></a>';
        })

     
    
        ->rawColumns(['edit','changeStatus'])


            ->make(true);
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' =>"الرجاء ادخل الاسم",
            'symbol.required' =>"الرجاء ادخل الرمز",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:countries'],
            'symbol' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $country = new Country();
                $country->name = $request->name;
                $country->symbol = $request->symbol;

                $country->save();
            });
            Alert::success("تمت عملية اضافة دولة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية اضافة دولة بنجاح");

            return redirect()->route('country');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة دولة");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة دولة");

            return redirect()->route('country');
        }
    }

    public function changeStatus(Request $request, $id)

    {
        $country_id = decrypt($id);
        $country = Country::find($country_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $country_id = decrypt($id);
                $country = Country::find($country_id);
        
              
                if ($country->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $country->active = $active;
                $country->save();
            });
            ActivityLogger::activity($country->name ."تمت عملية تغيير حالة دولة");
            Alert::success(" تمت عملية تغيير حالة دولة بنجاح");

            return redirect('country');
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة دولة");
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة دولة");

            return redirect('country');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $car)
    { 

            $Country_id = decrypt($car);
            $Country = Country::find($Country_id);
            ActivityLogger::activity("صفحة تعديل بيانات دولة");
            return view('dashbord.country.edit')->with('Country', $Country);
       

   
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $country_id = decrypt($id);
        $country = Country::find($country_id);

     
        $messages = [
            'name.required' =>"الرجاء ادخل الاسم",
            'symbol.required' =>"الرجاء ادخل الرمز",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:countries,name,' . $country->id], // Exclude current car from unique validation
            'symbol' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $country_id = decrypt($id);
                $country = Country::find($country_id);
        
                $country->name = $request->name;
                $country->symbol = $request->symbol;

                $country->save();
            });
            Alert::success("تمت عملية تعديل بيانات دولة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية تعديل دولة بنجاح");

            return redirect()->route('country');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل دولة");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل دولة");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        //
    }
}
