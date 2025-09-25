<?php

namespace App\Http\Controllers\Dashbord;

use App\Models\Price;
use App\Http\Controllers\Controller;
use App\Models\car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class PriceController extends Controller
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
        $price=Price::find(1);
        ActivityLogger::activity("عرض كافة اسعار الاقساط");

        return view('dashbord.price.index')
        ->with('price',$price);
    }
    /**
     * Display a listing of the resource.
     */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        ActivityLogger::activity("اضافة اسعار عرض صفحة");

        return view('dashbord.price.create');
    }

    public function price()
    {
    
        $price = Price::orderBy('created_at', 'DESC');
        return datatables()->of($price)
        ->addColumn('edit', function ($price) {
            $price_id = encrypt($price->id);
        
            return '<a style="color: #f97424;" href="' . route('price/edit',$price_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
       

     
    
        ->rawColumns(['edit','changeStatus'])


            ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $messages = [
            'installment_daily_1.required' =>"الرجاء ادخل  القسط اليومي للبند الأول ",
            'installment_daily_2.required' =>"الرجاء ادخل  القسط اليومي للبند الثاني ",
            'supervision.required' =>"الرجاء ادخل الإشراف",
            'tax.required' =>"الرجاء ادخل الضريبة",
            'version.required' =>"الرجاء ادخل الإصدار",
            'stamp.required' =>"الرجاء ادخل الدمغة",
            'increase.required' =>"الرجاء ادخل  معدل الزيادة ",

        ];
        $this->validate($request, [
            'installment_daily_1' => 'required|numeric',
            'installment_daily_2' => 'required|numeric',
            'supervision' => 'required|numeric',
            'tax' => 'required|numeric',
            'version' => 'required|numeric',
            'stamp' => 'required|numeric',
            'increase' => 'required|numeric',
        ], $messages);
       

        try {
            DB::transaction(function () use ($request) {
                $price = new Price();
                $price->installment_daily_1 = $request->installment_daily_1;
                $price->installment_daily_2 = $request->installment_daily_2;
                $price->supervision = $request->supervision;
                $price->tax = $request->tax;
                $price->version = $request->version;
                $price->stamp = $request->stamp;
                $price->increase = $request->increase;

                $price->save();
            });

            Alert::success("تمت عملية اضافة سعر بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية اضافة سعر بنجاح");

            return redirect()->route('price');
        } catch (\Exception $e) {

            Alert::warning("فشل اضافة سعر");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة سعر");

            return redirect()->route('price');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $Price)
    { 

            $Price_id = decrypt($Price);
            $Price = Price::find($Price_id);
            ActivityLogger::activity("صفحة تعديل بيانات قسط");
            return view('dashbord.price.edit')->with('Price', $Price);
       

   
     
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $Price_id = decrypt($id);
        $Price = Price::find($Price_id);
        $messages = [
            'installment_daily_1.required' =>"الرجاء ادخل  القسط اليومي للبند الأول ",
            'installment_daily_2.required' =>"الرجاء ادخل  القسط اليومي للبند الثاني ",
            'supervision.required' =>"الرجاء ادخل الإشراف",
            'tax.required' =>"الرجاء ادخل الضريبة",
            'version.required' =>"الرجاء ادخل الإصدار",
            'stamp.required' =>"الرجاء ادخل الدمغة",
            'increase.required' =>"الرجاء ادخل  معدل الزيادة ",

        ];
        $this->validate($request, [
            'installment_daily_1' => 'required|numeric',
            'installment_daily_2' => 'required|numeric',
            'supervision' => 'required|numeric',
            'tax' => 'required|numeric',
            'version' => 'required|numeric',
            'stamp' => 'required|numeric',
            'increase' => 'required|numeric',
        ], $messages);
        try {
            DB::transaction(function () use ($request,$id) {

                $Price_id = decrypt($id);
        $price = Price::find($Price_id);
        $price->installment_daily_1 = $request->installment_daily_1;
        $price->installment_daily_2 = $request->installment_daily_2;
        $price->supervision = $request->supervision;
        $price->tax = $request->tax;
        $price->version = $request->version;
        $price->stamp = $request->stamp;
        $price->increase = $request->increase;

        $price->save();
            });
            Alert::success("تمت عملية تعديل بيانات قسط بنجاح");
            ActivityLogger::activity($request->name . "تمت عملية تعديل قسط بنجاح");

            return redirect()->route('price');
        } catch (\Exception $e) {

            Alert::warning("فشل تعديل قسط");
            ActivityLogger::activity($e->getMessage() . "فشل تعديل قسط");

            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Price $price)
    {
        //
    }
}
