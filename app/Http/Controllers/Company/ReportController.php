<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Apiuser;
use App\Models\Card;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\issuing;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Services\LifoApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
                ->where('company_user_permissions_id', 7)
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $Office = Office::where('companies_id', Auth::user()->companies_id)->get();

        $companyUser = CompanyUser::where('companies_id', Auth::user()->companies_id)->get();
        return view('comapny.report.search')
            ->with('Office', $Office)
            ->with('companyUser', $companyUser);
    }
    public function indexsummary()
    {

        $Office = Office::where('companies_id', Auth::user()->companies_id)->get();
        $companyUser = CompanyUser::where('companies_id', Auth::user()->companies_id)->get();

        return view('comapny.report.searchsummary')
            ->with('Office', $Office)
            ->with('companyUser', $companyUser);
    }



    public function searchby(Request $request)
    {
        // Parse and format dates
        $fromd = Carbon::parse($request->fromdate)->format('Y-m-d');
        $tod = Carbon::parse($request->todate)->format('Y-m-d');

        $issuing = Issuing::with([
            'cards',
            'vehicle_nationalities',
            'companies',
            'offices',
            'offices.companies',
            'company_users',
            'office_users',
            'users',
            'cars',
            'countries'
        ])
            ->where(function ($query) {
                $query->where('companies_id', Auth::user()->companies_id)
                    ->orWhereHas('offices', function ($subQuery) {
                        $subQuery->where('companies_id', Auth::user()->companies_id);
                    });
            });



        if (!empty($request->offices_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

                ->select('*');

            $issuing->where('offices_id', $request->offices_id);
        }

        if (!empty($request->company_users_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

                ->select('*')
                ->where('companies_id', Auth::user()->companies_id);
            $issuing->where('company_users_id', $request->company_users_id);
        }
        if (!empty($request->office_users_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

                ->select('*');
            $issuing->where('office_users_id', $request->office_users_id);
        }

        // Apply search criteria
        if (!empty($request->card_number)) {
            $issuing->whereHas('cards', function ($query) use ($request) {
                $query->where('card_number', $request->card_number);
            });
        }

        if (!empty($request->insurance_name)) {
            $issuing->where('insurance_name', 'like', '%' . $request->insurance_name . '%');
        }

        if (!empty($request->plate_number)) {
            $issuing->where('plate_number', $request->plate_number);
        }

        if ($request->fromdate && $request->todate) {

            // When from and to are different, use whereBetween
            $issuing
                ->whereBetween('issuing_date', [$fromd . " 00:00:00", $tod . " 23:59:59"]);
        }
     


        // Order and retrieve results
        $issuing = $issuing->orderBy('created_at', 'DESC')->get();

        // Check if redsults are empty

        if ($issuing->isEmpty()) {

;
            return response()->json([
                'code' => 2,
                'status' => false,
                'message' => 'لايوجد عمليات اصدار',
            ], 200);
        } else {
            return response()->json([
                'code' => 1,
                'status' => true,
                'message' => 'يتم عرض المبيعات ',
                'data' => $issuing,
            ], 200);
        }
    }

    public function searchpdf(Request $request)
    {
        // Parse and format dates
        $fromd = Carbon::parse($request->fromdate)->format('Y-m-d');
        $tod = Carbon::parse($request->todate)->format('Y-m-d');

        // Initialize query with eager loading
        $issuing = Issuing::with([
            'cards',
            'vehicle_nationalities',
            'companies',
            'offices',
            'offices.companies',
            'company_users',
            'office_users',
            'users',
            'cars',
            'countries'
        ])
            ->where(function ($query) {
                $query->where('companies_id', Auth::user()->companies_id)
                    ->orWhereHas('offices', function ($subQuery) {
                        $subQuery->where('companies_id', Auth::user()->companies_id);
                    });
            });


        if (!empty($request->company_users_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

                ->select('*')
                ->where('companies_id', Auth::user()->companies_id);
            $issuing->where('company_users_id', $request->company_users_id);
        }

        if (!empty($request->offices_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

                ->select('*');

            $issuing->where('offices_id', $request->offices_id);
        }


        if (!empty($request->office_users_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

                ->select('*');
            $issuing->where('office_users_id', $request->office_users_id);
        }

        // Apply search criteria
        if (!empty($request->card_number)) {
            $issuing->whereHas('cards', function ($query) use ($request) {
                $query->where('card_number', $request->card_number);
            });
        }

        if (!empty($request->insurance_name)) {
            $issuing->where('insurance_name', 'like', '%' . $request->insurance_name . '%');
        }

        if (!empty($request->plate_number)) {
            $issuing->where('plate_number', $request->plate_number);
        }

        if (!empty($request->fromdate) && !empty($request->todate)) {
            // $issuing->whereBetween('created_at', [$fromd . " 00:00:00", $tod . " 23:59:59"]);
         $issuing
                ->whereBetween('issuing_date', [$fromd . " 00:00:00", $tod . " 23:59:59"]);
        }
        

        // Apply date range filtering (if provided)


        // Order and retrieve results
        $issuing = $issuing->orderBy('created_at', 'ASC')->get();

        // Check if results are empty
        if ($issuing->isEmpty()) {
            Alert::warning(" لايوجد بطاقات");

            return redirect()->back();
        } else {

            $total = $issuing->sum('insurance_total');

            return view('comapny.report.resul')
                ->with('fromdate', $request->fromdate)
                ->with('todate', $request->todate)
                ->with('issuing', $issuing)
                ->with('total', $total);
        }
    }



//     public function searchpdfsummery(Request $request)
//     {
//         // Parse and format dates
//         $from = Carbon::parse($request->fromdate)->format('Y-m-d');
//         $to = Carbon::parse($request->todate)->format('Y-m-d');

//         // Initialize query with eager loading
//         $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

//             ->select('*')
//             ->where('companies_id', Auth::user()->companies_id)
//             ->orwhereHas('offices',  function ($query) {
//                 $query->where('companies_id', Auth::user()->companies_id);
//             });



//         if (!empty($request->offices_id)) {
//             $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

//                 ->select('*');

//             $issuing->where('offices_id', $request->offices_id);
//         }

//         if (!empty($request->company_users_id)) {
//             $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

//                 ->select('*')
//                 ->where('companies_id', Auth::user()->companies_id);
//             $issuing->where('company_users_id', $request->company_users_id);
//         }
//         if (!empty($request->office_users_id)) {
//             $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])

//                 ->select('*');
//             $issuing->where('office_users_id', $request->office_users_id);
//         }

//         // Apply search criteria
//         if (!empty($request->card_number)) {
//             $issuing->whereHas('cards', function ($query) use ($request) {
//                 $query->where('card_number', $request->card_number);
//             });
//         }

//         if (!empty($request->insurance_name)) {
//             $issuing->where('insurance_name', 'like', '%' . $request->insurance_name . '%');
//         }

//         if (!empty($request->plate_number)) {
//             $issuing->where('plate_number', $request->plate_number);
//         }

//   if (!empty($request->fromdate) && !empty($request->todate)) {
//             $issuing = $issuing->whereBetween('issuing_date', [$from . " 00:00:00", $to . " 23:59:59"]);
//         }

//         // Apply date range filtering (if provided)


//         // Order and retrieve results
//         $issuing = $issuing->orderBy('created_at', 'DESC')->get();

//         // Check if results are empty
//         if ($issuing->isEmpty()) {
//             Alert::warning(" لايوجد بطاقات");

//             return redirect()->back();
//         } else {

//             $total = $issuing->sum('insurance_total');

//             return view('comapny.report.resultsummary')
//                 ->with('fromdate', $request->fromdate)
//                 ->with('todate', $request->todate)
//                 ->with('issuing', $issuing)
//                 ->with('total', $total);
//         }
//     }


public function searchpdfsummery(Request $request)
{
    // 1. Validation (اختياري لكن يُنصح به)
    $request->validate([
        'fromdate'         => 'required|date',
        'todate'           => 'required|date|after_or_equal:fromdate',
        'offices_id'       => 'nullable|integer',
        'company_users_id' => 'nullable|integer',
        'office_users_id'  => 'nullable|integer',
        'card_number'      => 'nullable|string',
        'insurance_name'   => 'nullable|string',
        'plate_number'     => 'nullable|string',
        'chassis_number'   => 'nullable|string',
    ]);

    // 2. تحويل التواريخ إلى Carbon objects لبداية ونهاية اليوم
    $from = Carbon::parse($request->fromdate)->startOfDay();
    $to   = Carbon::parse($request->todate)->endOfDay();

    // 3. تهيئة الاستعلام مرة واحدة مع تجميع شرط الشركة والأفرع
    $query = Issuing::with([
            'cards','vehicle_nationalities','companies',
            'offices.companies','company_users','office_users',
            'users','cars','countries'
        ])
        ->where(function($q) {
            $q->where('companies_id', Auth::user()->companies_id)
              ->orWhereHas('offices', function($q2) {
                  $q2->where('companies_id', Auth::user()->companies_id);
              });
        })
        ->whereBetween('issuing_date', [$from, $to]); // 4. فلتر التاريخ

    // 5. إضافة بقية الشروط على نفس الـQueryBuilder
    if ($request->filled('offices_id')) {
        $query->where('offices_id', $request->offices_id);
    }
    if ($request->filled('company_users_id')) {
        $query->where('company_users_id', $request->company_users_id);
    }
    if ($request->filled('office_users_id')) {
        $query->where('office_users_id', $request->office_users_id);
    }
    if ($request->filled('card_number')) {
        $query->whereHas('cards', fn($q)=>
            $q->where('card_number', $request->card_number)
        );
    }
    if ($request->filled('insurance_name')) {
        $query->where('insurance_name', 'like', "%{$request->insurance_name}%");
    }
    if ($request->filled('plate_number')) {
        $query->where('plate_number', $request->plate_number);
    }
    if ($request->filled('chassis_number')) {
        $query->where('chassis_number', $request->chassis_number);
    }

    // 6. ترتيب وجلب النتائج
    $issuing = $query->orderBy('issuing_date','ASC')->get();

    if ($issuing->isEmpty()) {
        Alert::warning("لا توجد بطاقات في هذه المعايير");
        return redirect()->back();
    }

    // 7. حساب الإجمالي وعرض النتيجة
    $total = $issuing->sum('insurance_total');

    return view('comapny.report.resultsummary', [
        'fromdate' => $request->fromdate,
        'todate'   => $request->todate,
        'issuing'  => $issuing,
        'total'    => $total,
    ]);
}

    public function officesuser($id)
    {
        $Officeuser = OfficeUser::where('offices_id', $id)->get();

        if (!$Officeuser) {
            return response()->json(0);
        }

        return response()->json($Officeuser);
    }

    public function companyUser()
    {
        $companyUser = CompanyUser::where('companies_id', Auth::user()->companies_id)->get();

        if (!$companyUser) {
            return response()->json(0);
        }

        return response()->json($companyUser);
    }

    public function indexstock()
    {




        $cardsstock = DB::table('cards')
            ->select(
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                // Count active cards
                'cards.id',
                'cardstautes.id as cardstautes_id',
                'offices.name as officesname'
            ) // Use alias for offices.id
            ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
            ->join('offices', 'offices.id', '=', 'cards.offices_id')
            ->where('cards.companies_id',  Auth::user()->companies_id)
            ->groupBy('officesname')
            ->get();

        return view('comapny.report.stockindex')->with('cardsstock', $cardsstock);
    }


 public function stockpdf()
    {




       $cardsstock = DB::table('cards')
            ->select(
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                // Count active cards
                'cards.id',
                'cardstautes.id as cardstautes_id',
                'offices.name as officesname'
            ) // Use alias for offices.id
            ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
            ->join('offices', 'offices.id', '=', 'cards.offices_id')
            ->where('cards.companies_id',  Auth::user()->companies_id)
            ->groupBy('officesname')
            ->get();

        return view('comapny.report.stock')->with('cardsstock', $cardsstock);
    }

    public function indexstockc()
    {




        $cardsstock = DB::table('cards')
            ->select(
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                // Count active cards
                'cards.id',
                'cardstautes.id as cardstautes_id'
            ) // Use alias for offices.id
            ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
            ->where('cards.companies_id',  Auth::user()->companies_id)
            ->whereNull('offices_id')
            ->groupBy('companies_id')
            ->get();

        return view('comapny.report.cstockindex')->with('cardsstock', $cardsstock);
    }



 public function stockcpdf()
    {




        $cardsstock = DB::table('cards')
            ->select(
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                // Count active cards
                'cards.id',
                'cardstautes.id as cardstautes_id'
            ) // Use alias for offices.id
            ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
            ->where('cards.companies_id',  Auth::user()->companies_id)
            ->whereNull('offices_id')
            ->groupBy('companies_id')
            ->get();

        return view('comapny.report.cstock')->with('cardsstock', $cardsstock);
    }

 
public function indexcanelcardpdf(Request $request)
{
    $searchParams = [
        'request_number' => $request->request_number ?? '',
        'card_number'    => $request->card_number ?? '',
        'fromdate'       => $request->fromdate ?? '',
        'todate'         => $request->todate ?? '',
    ];

    $query = Card::with([
            'users',
            'companies:id,name',
            'cardstautes:id,name',
            'requests:id,request_number',
            'issuing:id,cards_id,issuing_date',
        ])
        ->where('cardstautes_id', 3)
        ->where('companies_id', Auth::user()->companies_id);

    if (!empty($request->request_number)) {
        $query->whereHas('requests', function ($q) use ($request) {
            $q->where('request_number', $request->request_number);
        });
    }

    if (!empty($request->card_number)) {
        $query->where('card_number', $request->card_number);
    }

    if (!empty($request->fromdate) && !empty($request->todate)) {
        $from = Carbon::parse($request->fromdate)->startOfDay();
        $to   = Carbon::parse($request->todate)->endOfDay();
        $query->whereBetween('card_delete_date', [$from, $to]);
    }

    $cards = $query->get();

    return view('comapny.report.searchcancepdf', compact('cards', 'searchParams'));
}




    public function indexcanelcard()
    {


        return view('comapny.report.searchcance');
    }



public function searchcacel(Request $request)
{
    $request->validate([
        'fromdate'        => 'nullable|date',
        'todate'          => 'nullable|date|after_or_equal:fromdate',
        'request_number'  => 'nullable|string',
        'card_number'     => 'nullable|string',
    ]);

    $from = $request->filled('fromdate') ? Carbon::parse($request->fromdate)->startOfDay() : null;
    $to   = $request->filled('todate')   ? Carbon::parse($request->todate)->endOfDay()   : null;

    $companyId = Auth::user()->companies_id;

    $cards = Card::with([
            'users',
            'companies:id,name',
            'cardstautes:id,name',
            'requests:id,request_number',
            'issuing:id,cards_id,issuing_date',
        ])
        ->where('cardstautes_id', 3)
        ->where(function ($q) use ($companyId) {
            $q->where('companies_id', $companyId)
              ->orWhereHas('offices', fn($sub) => $sub->where('companies_id', $companyId));
        })
        ->when($request->request_number, fn($q) =>
            $q->whereHas('requests', fn($sub) =>
                $sub->where('request_number', $request->request_number)
            )
        )
        ->when($request->card_number, fn($q) =>
            $q->where('card_number', $request->card_number)
        )
        ->when($from && $to, fn($q) =>
            $q->whereBetween('card_delete_date', [$from, $to])
        )
        ->get();

    if ($cards->isEmpty()) {
        return response()->json([
            'code'    => 2,
            'status'  => false,
            'message' => 'لا يوجد بطاقات.',
        ]);
    }

    return response()->json([
        'code'    => 1,
        'status'  => true,
        'message' => 'تم جلب البطاقات الملغية.',
        'data'    => $cards,
    ]);
}


    //  public function searchcacel(Request $request)
    // {

    //     $from = Carbon::parse($request->fromdate)->format('Y-m-d');
    //     $to = Carbon::parse($request->todate)->format('Y-m-d');

    //     $card = Card::with(['users', 'companies', 'cardstautes', 'requests'])
    //         ->where(function ($query) {
    //             $query->where('cardstautes_id', 3)
    //                   ->where('companies_id', Auth::user()->companies_id)
    //                   ->orWhereHas('offices', function ($subQuery) {
    //                       $subQuery->where('companies_id', Auth::user()->companies_id);
    //                   });
    //         });

    //     if ($request->request_number) {
    //         $card = $card->whereHas('requests', function ($query) use ($request) {
    //             $query->where('request_number', $request->request_number);
    //         });
    //     }

    //     if ($request->card_number) {
    //         $card = $card->where('card_number', $request->card_number);
    //     }

    //     if (!empty($request->fromdate) && !empty($request->todate)) {
    //         $card = $card->whereBetween('card_delete_date', [$from . " 00:00:00", $to . " 23:59:59"]);
    //     }
       
    //     $card = $card->get();

    //     if ($card->isEmpty()) {
    //         return response()->json([
    //             'code' => 2,
    //             'status' => false,
    //             'message' => 'لايوجد بطاقات',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'code' => 1,
    //             'status' => true,
    //             'message' => 'يتم عرض البطاقات ',
    //             'data' => $card
    //         ], 200);
    //     }
    // }
}
