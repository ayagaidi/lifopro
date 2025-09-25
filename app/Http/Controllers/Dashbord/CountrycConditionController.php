<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CountrycCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
class CountrycConditionController extends Controller
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
        ActivityLogger::activity("عرض كافة شروط الدول");

        return view('dashbord.countryconditions.index');
    }
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        $Contry=Country::where('active',1)->get();
        ActivityLogger::activity("اضافة شروط دولة عرض صفحة");

        return view('dashbord.countryconditions.create')
        ->with('Contry',$Contry);
    }




    public function countryconditions()
    {
    
        $countryconditions = CountrycCondition::with('countries')->orderBy('created_at', 'DESC');
        return datatables()->of($countryconditions)
        ->addColumn('edit', function ($countryconditions) {
            $countryconditions_id = encrypt($countryconditions->id);
        
            return '<a style="color: #f97424;" href="' . route('countryconditions/edit',$countryconditions_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('changeStatus', function ($countryconditions) {
            $countryconditions_id = encrypt($countryconditions->id);

            return '<a href="' . route('countryconditions/changeStatus', $countryconditions_id) . '"><i  class="fa  fa-refresh"> </i></a>';
        })

     
    
        ->rawColumns(['edit','changeStatus'])


            ->make(true);
    }


    public function changeStatus(Request $request, $id)

    {
        $CountrycCondition_id = decrypt($id);
        $CountrycCondition = CountrycCondition::find($CountrycCondition_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $CountrycCondition_id = decrypt($id);
                $CountrycCondition = CountrycCondition::find($CountrycCondition_id);
        
              
                if ($CountrycCondition->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $CountrycCondition->active = $active;
                $CountrycCondition->save();
            });
            ActivityLogger::activity($CountrycCondition->id ."تمت عملية تغيير حالة شرط دولة");
            Alert::success(" تمت عملية تغيير حالة شرط دولة بنجاح");

            return redirect('countryconditions');
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة شرط دولة");
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة شرط دولة");

            return redirect('countryconditions');
        }
    }
    public function store(Request $request)
    {
        $messages = [
            'statementypecoverage.required' =>"الرجاء ادخل  بيان نوع التغطية",
            'unifiedofficaddress.required' =>"الرجاء ادخل  عنوان المكتب الموحد",
            'countries_id.required' =>"الرجاء اختر الدولة ",

        ];
        $this->validate($request, [
            'countries_id' => ['required'],
            'unifiedofficaddress' => ['required'],
            'statementypecoverage' => ['required']

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $countryconditions = new CountrycCondition();
                $countryconditions->countries_id = $request->countries_id;
                $countryconditions->unifiedofficaddress = $request->unifiedofficaddress;
                $countryconditions->statementypecoverage = $request->statementypecoverage;

                $countryconditions->save();
            });
            Alert::success("تمت عملية اضافةشرط دولة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية اضافةشرط دولة بنجاح");

            return redirect()->route('countryconditions');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافةشرط دولة");
            ActivityLogger::activity($e->getMessage() . "فشل اضافةشرط دولة");

            return redirect()->back();
        }
    }
    /**
     * Store a newly created resource in storage.
     */
  

    /**
     * Display the specified resource.
     */
    public function show(CountrycCondition $countrycCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $car)
    { 

        $Contry=Country::get();
        $CountrycConditiony_id = decrypt($car);

            $CountrycCondition = CountrycCondition::find($CountrycConditiony_id);
            ActivityLogger::activity("صفحة تعديل بيانات شرط دولة");
            return view('dashbord.countryconditions.edit')
            ->with('Contry', $Contry)
            ->with('CountrycCondition', $CountrycCondition);
       

   
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'statementypecoverage.required' =>"الرجاء ادخل  بيان نوع التغطية",
            'unifiedofficaddress.required' =>"الرجاء ادخل  عنوان المكتب الموحد",
            'countries_id.required' =>"الرجاء اختر الدولة ",

        ];
        $this->validate($request, [
            'countries_id' => ['required'],
            'unifiedofficaddress' => ['required'],
            'statementypecoverage' => ['required']

        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {
                $CountrycConditiony_id = decrypt($id);

                $countryconditions = CountrycCondition::find($CountrycConditiony_id);
         
                $countryconditions->countries_id = $request->countries_id;
                $countryconditions->unifiedofficaddress = $request->unifiedofficaddress;
                $countryconditions->statementypecoverage = $request->statementypecoverage;

                $countryconditions->save();
            });
            Alert::success("تمت عملية تعديل شرط دولة بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية تعديل شرط دولة بنجاح");

            return redirect()->route('countryconditions');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل شرط دولة");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل شرط دولة");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CountrycCondition $countrycCondition)
    {
        //
    }
}
