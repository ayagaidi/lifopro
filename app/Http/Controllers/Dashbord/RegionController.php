<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class RegionController extends Controller
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
        ActivityLogger::activity("عرض صفحة المناطق");

        return view('dashbord.region.index');
    }

    public function region()
    {
    
        $region = Region::orderBy('created_at', 'DESC');
        return datatables()->of($region)
        ->addColumn('edit', function ($region) {
            $region_id = encrypt($region->id);
        
            return '<a style="color: #f97424;" href="' . route('region/edit',$region_id).'"><i  class="fa  fa-edit" > </i></a>';
        })

    
    
        ->rawColumns(['edit'])


            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

       
        ActivityLogger::activity("صفحة اضافة منطقة");
        return view('dashbord.region.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
   

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $region)
    { 

            $region_id = decrypt($region);
            $region = Region::find($region_id);
            ActivityLogger::activity("صفحة تعديل المنطقه");
            return view('dashbord.region.edit')->with('region', $region);
       

   
     
    }

    public function update(Request $request,$region)
    {
        $region_id = decrypt($region);
        $region = Region::find($region_id);
        $messages = [
            'name.required' => "ادخل اسم المنطقة",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:regions,name,' . $region->id], // Exclude current car from unique validation

        ], $messages);
        try {
            DB::transaction(function () use ($request,$region_id) {

            $region = Region::find($region_id);
                $region->name = $request->name;

                $region->save();
            });
            Alert::success("تمت تعديل منطقة بنجاح");
            ActivityLogger::activity($request->name ."تمت تعديل منطقة بنجاح");

            return redirect()->route('region');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل منطقة");
            ActivityLogger::activity($e->getMessage() ."فشل تعديل منطقة");

            return redirect()->route('region');
        }
    
    }
    /**
     * Update the specified resource in storage.
     */

    
     public function store(Request $request)
    {
        $messages = [
            'name.required' => "ادخل اسم المنطقة",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250','unique:regions'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $region = new Region();
                $region->name = $request->name;

                $region->save();
            });
            Alert::success("تمت اضافة منطقة بنجاح");
            ActivityLogger::activity($request->name ."تمت اضافة منطقة بنجاح");

            return redirect()->route('region');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة منطقة");
            ActivityLogger::activity($e->getMessage() ."فشل اضافة منطقة");

            return redirect()->route('region');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
    }
}
