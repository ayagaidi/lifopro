<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Office;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class RefundController extends Controller
{
         public function __construct()
    {
    $this->middleware('auth:companys'); // فرض أن المستخدم داخل guard companys

    $this->middleware(function ($request, $next) {
        $user = Auth::guard('companys')->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // تحقق من نوع المستخدم
        if ($user->userType->id == 1) {
            return $next($request); // مسموح للمستخدم من نوع 1
        }

        if ($user->userType->id == 2) {
            $hasPermission = \App\Models\CompanyUserRole::where('company_users_id', $user->id)
                ->where('company_user_permissions_id', 1)
                ->first();

            if ($hasPermission) {
                return $next($request);
            } else {
                abort(403, 'ليس لديك صلاحية الوصول.');
            }
        }

        // أي نوع آخر غير مسموح
        abort(403, 'لديك صلاحية الوصول.');
    });
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        return view('comapny.refund.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Offices = Office::where('companies_id', Auth::user()->companies_id)->get();
        return view('comapny.refund.create')
            ->with('Offices', $Offices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'offices_id.required' => 'اختر المكتب',
        ];

        $this->validate($request, [
            'offices_id' => 'required',
        ], $messages);

        try {
            $cardsCount = Card::where('offices_id', $request->offices_id)
                ->where('cardstautes_id', 1)
                ->count();
                
            if ($cardsCount > 0) {
                DB::transaction(function () use ($request,$cardsCount) {

                    $cards = Card::where('offices_id', $request->offices_id)
                    ->where('cardstautes_id', 1)
                    ->get();
                    $Refund = new Refund();
                    $Refund->numerofcard = $request->numerofcard;
                    $Refund->offices_id = $request->offices_id;
                    $Refund->companies_id = Auth::user()->companies_id;
                    
                    $Refund->numerofcard=$cardsCount;
                    $Refund->save();


                    foreach ($cards as $card) {

                        $card->offices_id = null;
                        $card->companies_id = Auth::user()->companies_id;
                        $card->save();
                    }

                });

                Alert::success('تمت اضافة راجعة بنجاح');
                return redirect()->route('company/refund');
            } else {
                // Handle insufficient cards case (e.g., show an error message)
                Alert::warning('لا توجد بطاقات  ');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error($e);

            Alert::warning($e . 'فشل اضافة راجعة');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function refund()
    {

    
        $Refund = Refund::with(['offices', 'companies'])
        ->where('companies_id',Auth::user()->companies_id)
        ->orderBy('created_at', 'desc')
        ->get();
        return datatables()->of($Refund)



        ->make(true);
    
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refund $refund)
    {
        //
    }
}
