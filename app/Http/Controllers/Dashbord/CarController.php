<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class CarController extends Controller
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
        ActivityLogger::activity("عرض كافة السيارات");

        return view('dashbord.car.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        ActivityLogger::activity("اضافة سيارة عرض صفحة");

        return view('dashbord.car.create');
    }



    public function car()
    {
    
        $car = car::orderBy('created_at', 'DESC');
        return datatables()->of($car)
        ->addColumn('edit', function ($car) {
            $car_id = encrypt($car->id);
        
            return '<a style="color: #f97424;" href="' . route('car/edit',$car_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('changeStatus', function ($car) {
            $car_id = encrypt($car->id);

            return '<a href="' . route('car/changeStatus', $car_id) . '"><i  class="fa  fa-refresh"> </i></a>';
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
            'name' => ['required', 'string', 'max:250', 'unique:cars'],
            'symbol' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $car = new car();
                $car->name = $request->name;
                $car->symbol = $request->symbol;

                $car->save();
            });
            Alert::success("تمت عملية اضافة سيارة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية اضافة سيارة بنجاح");

            return redirect()->route('car');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة سيارة");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة سيارة");

            return redirect()->route('car');
        }
    }

    public function changeStatus(Request $request, $id)

    {
        $carr_id = decrypt($id);
        $car = car::find($carr_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $carr_id = decrypt($id);
                $car = car::find($carr_id);
                if ($car->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $car->active = $active;
                $car->save();
            });
            ActivityLogger::activity($car->name ."تمت عملية تغيير حالة سيارة");
            Alert::success(" تمت عملية تغيير حالة سيارة بنجاح");

            return redirect('car');
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة سيارة");
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة سيارة");

            return redirect('car');
        }
    }
    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $car)
    { 

            $car_id = decrypt($car);
            $car = car::find($car_id);
            ActivityLogger::activity("صفحة تعديل بيانات سيارة");
            return view('dashbord.car.edit')->with('car', $car);
       

   
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $carr_id = decrypt($id);
        $car = car::find($carr_id);
        $messages = [
            'name.required' =>"الرجاء ادخل الاسم",
            'symbol.required' =>"الرجاء ادخل الرمز",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:cars,name,' . $car->id], // Exclude current car from unique validation
            'symbol' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $carr_id = decrypt($id);
                $car = car::find($carr_id);
                $car->name = $request->name;
                $car->symbol = $request->symbol;

                $car->save();
            });
            Alert::success("تمت عملية تعديل بيانات سيارة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية تعديل سيارة بنجاح");

            return redirect()->route('car');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل سيارة");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل سيارة");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(car $car)
    {
        //
    }
}
