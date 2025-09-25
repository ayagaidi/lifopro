<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\VehicleNationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class VehicleNationalityController extends Controller
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
        ActivityLogger::activity("عرض كافة جنسية المركبة");

        return view('dashbord.vehicle_nationalities.index');
    }


    public function create()
    {



        ActivityLogger::activity("اضافة جنسية المركبة عرض صفحة");

        return view('dashbord.vehicle_nationalities.create');
    }

    /**
     * Show the form for creating a new resource.
     */
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' =>"الرجاء ادخل الاسم",
            'symbol.required' =>"الرجاء ادخل الرمز",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:vehicle_nationalities'],
            'symbol' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $VehicleNationality = new VehicleNationality();
                $VehicleNationality->name = $request->name;
                $VehicleNationality->symbol = $request->symbol;

                $VehicleNationality->save();
            });
            Alert::success("تمت عملية اضافة جنسية المركبة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية اضافة جنسية المركبة بنجاح");

            return redirect()->route('vehiclenationalities');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة جنسية المركبة");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة جنسية المركبة");

            return redirect()->route('vehiclenationalities');
        }
    }

    public function vehiclenationalities()
    {
    
        $vehiclenationalities = VehicleNationality::orderBy('created_at', 'DESC');
        return datatables()->of($vehiclenationalities)
        ->addColumn('edit', function ($vehiclenationalities) {
            $vehiclenationalities_id = encrypt($vehiclenationalities->id);
        
            return '<a style="color: #f97424;" href="' . route('vehiclenationalities/edit',$vehiclenationalities_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('changeStatus', function ($vehiclenationalities) {
            $vehiclenationalities_id = encrypt($vehiclenationalities->id);

            return '<a href="' . route('vehiclenationalities/changeStatus', $vehiclenationalities_id) . '"><i  class="fa  fa-refresh"> </i></a>';
        })

     
    
        ->rawColumns(['edit','changeStatus'])


            ->make(true);
    }


    public function changeStatus(Request $request, $id)

    {
        $VehicleNationalityid = decrypt($id);
        $VehicleNationality = VehicleNationality::find($VehicleNationalityid);

        try {
            DB::transaction(function () use ($request, $id) {
                $VehicleNationalityid = decrypt($id);
                $VehicleNationality = VehicleNationality::find($VehicleNationalityid);
        
                if ($VehicleNationality->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $VehicleNationality->active = $active;
                $VehicleNationality->save();
            });
            ActivityLogger::activity($VehicleNationality->name ."تمت عملية تغيير حالة جنسية المركبة");
            Alert::success(" تمت عملية تغيير حالة جنسية المركبة بنجاح");

            return redirect('vehiclenationalities');
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة جنسية المركبة");
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة جنسية المركبة");

            return redirect('vehiclenationalities');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleNationality $vehicleNationality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $car)
    { 

            $car_id = decrypt($car);
            $VehicleNationality = VehicleNationality::find($car_id);
            ActivityLogger::activity("صفحة تعديل بيانات جنسية المركبة");
            return view('dashbord.vehicle_nationalities.edit')->with('VehicleNationality', $VehicleNationality);
       

   
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $cVehicleNationality_id = decrypt($id);
        $VehicleNationality = VehicleNationality::find($cVehicleNationality_id);
     
        $messages = [
            'name.required' =>"الرجاء ادخل الاسم",
            'symbol.required' =>"الرجاء ادخل الرمز",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:vehicle_nationalities,name,' . $VehicleNationality->id], // Exclude current car from unique validation
            'symbol' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $cVehicleNationality_id = decrypt($id);
                $VehicleNationality = VehicleNationality::find($cVehicleNationality_id);
                $VehicleNationality->name = $request->name;
                $VehicleNationality->symbol = $request->symbol;

                $VehicleNationality->save();
            });
            Alert::success("تمت عملية تعديل بيانات جنسية المركبة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية تعديل جنسية المركبة بنجاح");

            return redirect()->route('vehiclenationalities');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل جنسية المركبة");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل جنسية المركبة");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleNationality $vehicleNationality)
    {
        //
    }
}
