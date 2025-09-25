<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\InsuranceClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class InsuranceClauseController extends Controller
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
        ActivityLogger::activity("عرض كافة بند التامين");

        return view('dashbord.insurance_clause.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        ActivityLogger::activity("اضافة بند التامين عرض صفحة");

        return view('dashbord.insurance_clause.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'type.required' => "الرجاء ادخل النوع",
            'slug.required' => "الرجاء ادخل الاسم",

        ];
        $this->validate($request, [
            'slug' => ['required', 'string', 'unique:insurance_clauses'],
            'type' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $InsuranceClause = new InsuranceClause();
                $InsuranceClause->slug = $request->slug;
                $InsuranceClause->type = $request->type;
                if ($request->type == "PV") {
                    $InsuranceClause->type_id = 1;
                }
                if ($request->type == "CV") {
                    $InsuranceClause->type_id = 2;
                }


                $InsuranceClause->save();
            });
            Alert::success("تمت عملية اضافة بند التامين بنجاح");
            ActivityLogger::activity($request->slug . "تمت عملية اضافة بند التامين بنجاح");

            return redirect()->route('insurance_clause');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة بند التامين");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة بند التامين");

            return redirect()->back();
        }
    }


    public function insuranceClause()
    {

        $insuranceClause = InsuranceClause::orderBy('created_at', 'DESC');
        return datatables()->of($insuranceClause)
            ->addColumn('edit', function ($insuranceClause) {
                $insuranceClausecar_id = encrypt($insuranceClause->id);

                return '<a style="color: #f97424;" href="' . route('insurance_clause/edit', $insuranceClausecar_id) . '"><i  class="fa  fa-edit" > </i></a>';
            })

            ->rawColumns(['edit'])


            ->make(true);
    }
    /**
     * Display the specified resource.
     */
    public function show(InsuranceClause $insuranceClause)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $idd = decrypt($id);
        $InsuranceClause = InsuranceClause::find($idd);
        ActivityLogger::activity("تعديل بيانات  بند التامين");
        return view('dashbord.insurance_clause.edit')
            ->with('InsuranceClause', $InsuranceClause);
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $idd = decrypt($id);
        $InsuranceClause = InsuranceClause::find($idd);
        $messages = [
            'type.required' => "الرجاء ادخل النوع",
            'slug.required' => "الرجاء ادخل الاسم",

        ];
        $this->validate($request, [
            'slug' => ['required', 'string', 'unique:insurance_clauses,slug,' . $InsuranceClause->id], // Exclude current car from unique validation
            'type' => ['required']
        ], $messages);
        try {
            DB::transaction(function () use ($request, $id) {

                $idd = decrypt($id);
                $InsuranceClause = InsuranceClause::find($idd);
                $InsuranceClause->slug = $request->slug;
                $InsuranceClause->type = $request->type;
                if ($request->type == "PV") {
                    $InsuranceClause->type_id = 1;
                }
                if ($request->type == "CV") {
                    $InsuranceClause->type_id = 2;
                }


                $InsuranceClause->save();
            });
            Alert::success("تمت عملية تعديل بند التامين بنجاح");
            ActivityLogger::activity($request->slug . "تمت عملية تعديل بند التامين بنجاح");

            return redirect()->route('insurance_clause');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل بند التامين");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل بند التامين");

            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsuranceClause $insuranceClause)
    {
        //
    }
}
