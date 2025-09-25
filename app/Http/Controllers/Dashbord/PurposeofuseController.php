<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Purposeofuse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class PurposeofuseController extends Controller
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
        ActivityLogger::activity("عرض كافة غرض الاستعمال ");

        return view('dashbord.purposeofuses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        ActivityLogger::activity("اضافة غرض الاستعمال عرض صفحة");

        return view('dashbord.purposeofuses.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'detail.required' =>"الرجاء ادخل التفاصيل",

        ];
        $this->validate($request, [
            'detail' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $purposeofuses = new Purposeofuse();
                $purposeofuses->detail = $request->detail;

                $purposeofuses->save();
            });
            Alert::success("تمت عملية اضافة غرض الاستعمال بنجاح");
            ActivityLogger::activity($request->detail . "تمت عملية اضافة غرض الاستعمال بنجاح");

            return redirect()->route('purposeofuses');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة غرض الاستعمال");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة غرض الاستعمال");

            return redirect()->route('purposeofuses');
        }
    }

    public function purposeofuses()
    {
    
        $purposeofuses = Purposeofuse::orderBy('created_at', 'DESC');
        return datatables()->of($purposeofuses)
        ->addColumn('edit', function ($purposeofuses) {
            $purposeofuses_id = encrypt($purposeofuses->id);
        
            return '<a style="color: #f97424;" href="' . route('purposeofuses/edit',$purposeofuses_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('changeStatus', function ($purposeofuses) {
            $purposeofuses_id = encrypt($purposeofuses->id);

            return '<a href="' . route('purposeofuses/changeStatus', $purposeofuses_id) . '"><i  class="fa  fa-refresh"> </i></a>';
        })

     
    
        ->rawColumns(['edit','changeStatus'])


            ->make(true);
    }


    public function changeStatus(Request $request, $id)

    {
        $purposeofuses_id = decrypt($id);
        $purposeofuses = Purposeofuse::find($purposeofuses_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $purposeofuses_id = decrypt($id);
                $purposeofuses = Purposeofuse::find($purposeofuses_id);
               if ($purposeofuses->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $purposeofuses->active = $active;
                $purposeofuses->save();
            });
            ActivityLogger::activity($purposeofuses->detail ."تمت عملية تغيير حالة سيارة");
            Alert::success(" تمت عملية تغيير حالة غرض الاستعمال بنجاح");

            return redirect('purposeofuses');
        } catch (\Exception $e) {

            Alert::warning("فشل تغيير حالة غرض الاستعمال");
            ActivityLogger::activity($e->getMessage() . "فشل تغيير حالة غرض الاستعمال");

            return redirect('purposeofuses');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purposeofuse $purposeofuse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $purposeofuses)
    { 

            $purposeofuses_id = decrypt($purposeofuses);
            $purposeofuses = Purposeofuse::find($purposeofuses_id);
            ActivityLogger::activity("صفحة تعديل بيانات غرض الاستعمال");
            return view('dashbord.purposeofuses.edit')->with('purposeofuses', $purposeofuses);
       

   
     
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $purposeofuses_id = decrypt($id);
        $purposeofuses = Purposeofuse::find($purposeofuses_id);

          $messages = [
            'detail.required' =>"الرجاء ادخل التفاصيل",

        ];
        $this->validate($request, [
            'detail' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $purposeofuses_id = decrypt($id);
                $purposeofuses = Purposeofuse::find($purposeofuses_id);
        
             
                $purposeofuses->detail = $request->detail;

                $purposeofuses->save();
            });
            Alert::success("تمت عملية تعديل بيانات غرض الاستعمال بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية تعديل غرض الاستعمال بنجاح");

            return redirect()->route('purposeofuses');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل غرض الاستعمال");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل غرض الاستعمال");

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purposeofuse $purposeofuse)
    {
        //
    }
}
