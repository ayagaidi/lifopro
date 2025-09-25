<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Offices;
use App\Models\Card;
use App\Models\Cardstautes;
use App\Models\Company;
use App\Models\Office;
use App\Models\issuing;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Yajra\DataTables\Facades\DataTables;

class CardController extends Controller{
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
                ->where('company_user_permissions_id', 4)
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


    public function search()
    {

        $Offices = Office::where('companies_id',Auth::user()->companies_id)->get();

        $Cardstautes = Cardstautes::where('id','!=',0)->get();
        return view('comapny.card.search')
            ->with('Cardstautes', $Cardstautes)
            ->with('Offices', $Offices);
    }
//     public function searchby(Request $request)
// {
//     try {
//         $companyId = Auth::user()->companies_id;

//         $query = Card::with(['users', 'companies', 'offices', 'cardstautes', 'requests'])
//           ->where('cards.companies_id', $companyId);

//         // فلترة حسب رقم الطلب (book_id)
//         if (!empty($request->request_number)) {
//             $query->where('book_id', $request->request_number);
//         }

//         // فلترة حسب المكتب
//         if (!empty($request->offices_id)) {
//             $query->where('offices_id', $request->offices_id);
//         }

//         // فلترة حسب رقم البطاقة
//         if (!empty($request->card_number)) {
//             $query->where('card_number', $request->card_number);
//         }

//         // فلترة حسب حالة البطاقة
//         $status = $request->cardstautes_id;
//         if (!is_null($status)) {
//             $query->where('cardstautes_id', $status);

//             // فلترة حسب التاريخ بناءً على نوع الحالة
//             if (!empty($request->fromdate) && !empty($request->todate)) {
//                 $from = Carbon::parse($request->fromdate)->startOfDay();
//                 $to = Carbon::parse($request->todate)->endOfDay();

//                 switch ($status) {
//                     case 0: // متبقية
//                         $query->whereBetween('created_at', [$from, $to]);
//                         break;
//                     case 1: // معينة
//                         $query->whereBetween('card_insert_date', [$from, $to]);
//                         break;
//                             case 2: // مصدّرة
//                         $query->join('issuings', 'issuings.cards_id', '=', 'cards.id')
//                               ->select('cards.*', 'issuings.created_at as issuing_date')
//                               ->whereBetween('issuings.created_at', [$from, $to])
//                               ->orderByDesc('issuings.created_at');
//                         break;
//                     case 3: // ملغاة
//                         $query->whereBetween('card_delete_date', [$from, $to]);
//                         break;
//                 }
//             }
//         }

//         $cards = $query->get();

//         if ($cards->isEmpty()) {
//             return response()->json([
//                 'code'    => 2,
//                 'status'  => false,
//                 'message' => 'لايوجد بطاقات.',
//             ]);
//         }

//         return response()->json([
//             'code'    => 1,
//             'status'  => true,
//             'message' => 'تم جلب البطاقات بنجاح.',
//             'data'    => $cards,
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'code'    => 0,
//             'status'  => false,
//             'message' => 'خطأ في المعالجة: ' . $e->getMessage(),
//         ], 500);
//     }
// }

// public function searchby(Request $request)
// {
//     $card = Card::with(['users','issuing', 'companies', 'offices', 'cardstautes', 'requests'])
//         ->where('companies_id', Auth::user()->companies_id);

//     if ($request->request_number) {
//         $card->WhereHas('requests', function ($query) use ($request) {
//                     $query->where('request_number', $request->request_number);
//                 });
        
//     }
    
    

//     if ($request->offices_id) {
//         $card->where('offices_id', $request->offices_id);
//     }

//     if ($request->card_number) {
//         $card->where('card_number', $request->card_number);
//     }

//     if ($request->cardstautes_id !== null && $request->cardstautes_id !== '') {
//         $card->where('cardstautes_id', $request->cardstautes_id);
//     }

//     $status = $request->cardstautes_id;

//     if (!empty($request->fromdate) && !empty($request->todate) && $status !== null) {
//         $from = Carbon::parse($request->fromdate)->startOfDay();
//         $to = Carbon::parse($request->todate)->endOfDay();

//         switch ((int) $status) {
//             case 0: // متبقية
//                 $card->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
//                 break;

//             case 1: // معينة (استخدم تاريخ الرفع من requests)
//                         $card->whereHas('requests', function ($q) use ($from, $to) {
//                             $q->whereBetween('uploded_datetime', [$from, $to]);
//                         });
//                         break;

//             case 2: // مصدّرة
//                 $card->whereHas('issuing', function ($query) use ($from, $to) {
//                     $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
//                 });
//                 break;

//             case 3: // ملغاة
//                 $card->whereBetween('card_delete_date', [$from . " 00:00:00", $to . " 23:59:59"]);
//                 break;
                
        
//         }
//     }

//     $cards = $card->get();

//     if ($cards->isEmpty()) {
//         return response()->json([
//             'code' => 2,
//             'status' => false,
//             'message' => 'لايوجد بطاقات',
//         ], 200);
//     }

//     return response()->json([
//         'code' => 1,
//         'status' => true,
//         'message' => 'يتم عرض البطاقات',
//         'data' => $cards
//     ], 200);
// }

public function searchby(Request $request)
{
    $card = Card::with(['users', 'issuing', 'companies', 'offices', 'cardstautes', 'requests'])
        ->where('companies_id', Auth::user()->companies_id);

    $status = $request->cardstautes_id;

    // ✅ تحقق من شرط: إذا تم تحديد الحالة، يجب أن يُرسل رقم الطلب أو التاريخين
    if (!is_null($status) && $status !== '') {
        $hasDates = !empty($request->fromdate) && !empty($request->todate);
        $hasRequest = !empty($request->request_number);

        if (!$hasDates && !$hasRequest) {
            return response()->json([
                'code' => 0,
                'status' => false,
                'message' => 'عند تحديد حالة البطاقة، يجب إدخال رقم الطلب أو تحديد الفترة الزمنية (من - إلى).',
            ], 422);
        }

        $card->where('cardstautes_id', $status);

        if ($hasDates) {
            try {
                $from = Carbon::parse($request->fromdate)->startOfDay();
                $to = Carbon::parse($request->todate)->endOfDay();

                switch ((int) $status) {
                    case 0:
                        $card->whereBetween('created_at', [$from, $to]);
                        break;
                    case 1:
                        $card->whereHas('requests', function ($q) use ($from, $to) {
                            $q->whereBetween('uploded_datetime', [$from, $to]);
                        });
                        break;
                    case 2:
                        $card->whereHas('issuing', function ($q) use ($from, $to) {
                            $q->whereBetween('created_at', [$from, $to]);
                        });
                        break;
                    case 3:
                        $card->whereBetween('card_delete_date', [$from, $to]);
                        break;
                }
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 0,
                    'status' => false,
                    'message' => 'تنسيق التاريخ غير صالح.',
                ], 422);
            }
        }
    }

    // ✅ تحقق: لا يُسمح بإرسال رقم الطلب بدون حالة
    if (!empty($request->request_number) && (is_null($status) || $status === '')) {
        return response()->json([
            'code' => 0,
            'status' => false,
            'message' => 'يرجى تحديد حالة البطاقة عند إدخال رقم الطلب.',
        ], 422);
    }


    // فلترة حسب المكتب
    if (!empty($request->offices_id)) {
        $card->where('offices_id', $request->offices_id);
    }

    // فلترة حسب رقم البطاقة
    if (!empty($request->card_number)) {
        $card->where('card_number', $request->card_number);
    }

    $cards = $card->get();

    if ($cards->isEmpty()) {
        return response()->json([
            'code' => 2,
            'status' => false,
            'message' => 'لايوجد بطاقات',
        ], 200);
    }

    return response()->json([
        'code' => 1,
        'status' => true,
        'message' => 'يتم عرض البطاقات',
        'data' => $cards
    ], 200);
}

public function searchbypdf(Request $request)
{
    $card = Card::with(['users', 'issuing', 'companies', 'offices', 'cardstautes', 'requests'])
        ->where('companies_id', Auth::user()->companies_id);

    $status = $request->cardstautes_id;

    // ✅ تحقق من شرط: إذا تم تحديد الحالة، يجب أن يُرسل رقم الطلب أو التاريخين
    if (!is_null($status) && $status !== '') {
        $hasDates = !empty($request->fromdate) && !empty($request->todate);
        $hasRequest = !empty($request->request_number);

        if (!$hasDates && !$hasRequest) {
            return response()->json([
                'code' => 0,
                'status' => false,
                'message' => 'عند تحديد حالة البطاقة، يجب إدخال رقم الطلب أو تحديد الفترة الزمنية (من - إلى).',
            ], 422);
        }

        $card->where('cardstautes_id', $status);

        if ($hasDates) {
            try {
                $from = Carbon::parse($request->fromdate)->startOfDay();
                $to = Carbon::parse($request->todate)->endOfDay();

                switch ((int) $status) {
                    case 0:
                        $card->whereBetween('created_at', [$from, $to]);
                        break;
                    case 1:
                        $card->whereHas('requests', function ($q) use ($from, $to) {
                            $q->whereBetween('uploded_datetime', [$from, $to]);
                        });
                        break;
                    case 2:
                        $card->whereHas('issuing', function ($q) use ($from, $to) {
                            $q->whereBetween('created_at', [$from, $to]);
                        });
                        break;
                    case 3:
                        $card->whereBetween('card_delete_date', [$from, $to]);
                        break;
                }
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 0,
                    'status' => false,
                    'message' => 'تنسيق التاريخ غير صالح.',
                ], 422);
            }
        }
    }

    // ✅ تحقق: لا يُسمح بإرسال رقم الطلب بدون حالة
    if (!empty($request->request_number) && (is_null($status) || $status === '')) {
        return response()->json([
            'code' => 0,
            'status' => false,
            'message' => 'يرجى تحديد حالة البطاقة عند إدخال رقم الطلب.',
        ], 422);
    }


    // فلترة حسب المكتب
    if (!empty($request->offices_id)) {
        $card->where('offices_id', $request->offices_id);
    }

    // فلترة حسب رقم البطاقة
    if (!empty($request->card_number)) {
        $card->where('card_number', $request->card_number);
    }


    $cards = $card->get();
    $user = Auth::user(); // لجلب اسم المستخدم الحالي

    return view('comapny.card.searchbypdf')
        ->with('cards', $cards)
        ->with('filters', $request->all())
        ->with('user', $user);;
}

// public function searchbypdf(Request $request)
// {
//     $query = Card::query()
//         ->with(['users', 'companies', 'cardstautes', 'requests']);

//     // فلترة حسب حالة البطاقة
//     $status = $request->cardstautes_id;
//     if (!is_null($status)) {
//         $query->where('cardstautes_id', $status);

//         // تحديد الحقل الزمني المناسب حسب الحالة
//         if (!empty($request->fromdate) && !empty($request->todate)) {
//             try {
//                 $from = Carbon::parse($request->fromdate)->startOfDay();
//                 $to = Carbon::parse($request->todate)->endOfDay();

//                 switch ($status) {
//                     case 0: // متبقية
//                         $query->whereBetween('created_at', [$from, $to]);
//                         break;

//                     case 1: // معينة
//                         $query->whereBetween('card_insert_date', [$from, $to]);
//                         break;

//                     case 2: // مصدّرة
//                         $query->join('issuings', 'issuings.cards_id', '=', 'cards.id')
//                               ->select('cards.*', 'issuings.created_at as issuing_date')
//                               ->whereBetween('issuings.created_at', [$from, $to])
//                               ->orderByDesc('issuings.created_at');
//                         break;

//                     case 3: // ملغاة
//                         $query->whereBetween('card_delete_date', [$from, $to]);
//                         break;
//                 }
//             } catch (\Exception $e) {
//                 return response()->json([
//                     'code' => 0,
//                     'status' => false,
//                     'message' => 'تنسيق التاريخ غير صالح.',
//                 ], 422);
//             }
//         }
//     }

//     // فلترة حسب رقم الطلب
//     if (!empty($request->request_number)) {
//         $query->whereHas('requests', fn($q) => $q->where('request_number', $request->request_number));
//     }

//     // فلترة حسب الشركة
//     if ($request->companies_id === "0") {
//         $query->whereNull('companies_id');
//     } elseif (!empty($request->companies_id)) {
//         $query->where('companies_id', $request->companies_id);
//     }

//     // فلترة حسب رقم البطاقة
//     if (!empty($request->card_number)) {
//         $query->where('card_number', $request->card_number);
//     }

//     $cards = $query->get();

//  return view('dashbord.card.searchpdf')
//         ->with('cards', $data)
//         ->with('filters', $request->all())
//         ->with('user', $user);;
// }
    // public function searchbyold(Request $request)
    // {

    //     $from = Carbon::parse($request->fromdate)->format('Y-m-d');
    //     $to = Carbon::parse($request->todate)->format('Y-m-d');

    //     $card = Card::with(['users', 'companies','offices',  'cardstautes', 'requests'])
    //     ->where('companies_id',Auth::user()->companies_id)
    //         ->select('*');
    //     // if ($request->request_number) {
    //     //     $card = $card->whereHas('requests',  function ($query) use ($request) {
    //     //         $query->where('request_number', $request->request_number);
    //     //     });
    //     // }
    //   if ($request->request_number) {
    //         $card->where('book_id', $request->request_number);
    //     }
     
     
     
       
    //     if ($request->offices_id) {
    //         $card->where('offices_id', $request->offices_id);
    //     }
      
    //     if ($request->card_number) {
    //         $card->where('card_number', $request->card_number);
    //     }
        

    //     if ($request->cardstautes_id) {
    //         $card->where('cardstautes_id', $request->cardstautes_id);
    //     }

    //     if (!empty($request->fromdate) && !empty($request->todate)) {
    //         $card = $card->whereBetween('card_insert_date', [$from . " 00:00:00", $to . " 23:59:59"]);
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

public function printAllCardsPDF()
{
    $companyId = Auth::user()->companies_id;

    $cards = Card::with(['companies', 'offices', 'cardstautes', 'requests'])
        ->where('companies_id', $companyId)
        ->orderByDesc('created_at')
        ->get();

    $user = auth()->user();

    return view('comapny.card.indexpdf', compact('cards', 'user'));
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('comapny.card.index');
    }
    
        
  public function cardall()
{
    $companyId = Auth::user()->companies_id;

    $cards = Card::with([
        'companies:id,name',
        'offices:id,name',
        'cardstautes:id,name',
        'requests:id,request_number'
    ])
    ->where('companies_id', $companyId)
    ->select( 'card_number', 'companies_id', 'offices_id', 'cardstautes_id', 'requests_id', 'card_insert_date')
    ->orderByDesc('created_at');

    return DataTables::of($cards)
       
        ->editColumn('cardstautes.name', function ($card) {
            return $card->cardstautes->name ?? '-';
        })
        ->editColumn('requests.request_number', function ($card) {
            return $card->requests->request_number ?? '-';
        })
        ->editColumn('card_insert_date', function ($card) {
            return $card->card_insert_date
                ? Carbon::parse($card->card_insert_date)->format('Y-m-d H:i')
                : '-';
        })
        ->rawColumns(['companies', 'offices'])
        ->make(true);
}

   

    public function indexcancel()
    {

        return view('comapny.card.indexcancel');
    }
    //   public function indexcancelpdf()
    // {

    //   $cards = Card::with(['companies', 'offices', 'cardstautes', 'requests'])
    //   ->leftJoin('issuings', 'issuings.cards_id', '=', 'cards.id')
    //     ->where('cardstautes_id', 3)
    //     ->where('cards.companies_id', Auth::user()->companies_id)
        
    //     ->orderBy('card_delete_date', 'ASC')
    //     ->get();

    // return view('comapny.card.indexcancelpdf', compact('cards'));
    // }
    
    public function indexcancelpdf()
{
    $cards = Card::with(['companies', 'offices', 'cardstautes', 'requests', 'issuings'])
        ->where('cardstautes_id', 3)
        ->where('companies_id', Auth::user()->companies_id)
        ->orderBy('card_delete_date', 'ASC')
        ->get();

    $user = Auth::user(); // لجلب اسم المستخدم الحالي

    return view('comapny.card.indexcancelpdf', compact('cards', 'user'));
}


   // $card = Card::with(['users', 'companies', 'offices', 'cardstautes', 'requests'])
    //     ->where('cardstautes_id', 3)
    //     ->where('companies_id', Auth::user()->companies_id)
    //     ->orderBy('created_at', 'DESC');

    // return datatables()->of($card)
    //     ->addColumn('companies', function ($card) {
    //         return $card->companies->name ?? 'الإتحاد الليبي للتأمين';
    //     })
    //     ->addColumn('offices', function ($card) {
    //         return $card->offices->name ?? 'لدى الشركة';
    //     })
        
        
    //     ->rawColumns(['companies', 'offices'])
    //     ->make(true);
  public function cardallcance()
{
 
    $cards = DB::table('cards')
        ->leftJoin('companies', 'cards.companies_id', '=', 'companies.id')
        ->leftJoin('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
        ->leftJoin('requests', 'cards.requests_id', '=', 'requests.id')
        ->leftJoin('issuings', 'issuings.cards_id', '=', 'cards.id')
        ->where('cards.cardstautes_id', 3)
                ->where('cards.companies_id', Auth::user()->companies_id)

        ->orderBy('cards.card_delete_date', 'desc')
        ->select([
            'cards.id',
            'cards.card_serial',
            'cards.card_number',
            'cards.book_id',
            'companies.name as companies_name',
            'cardstautes.name as cardstautes_name',
            'requests.request_number',
            'cards.created_at',
            'cards.card_delete_date',
            'issuings.created_at as issuing_date'
        ])
        ->get();
 return response()->json([
        'draw' => request()->get('draw'),
        'recordsTotal' => $cards->count(),
        'recordsFiltered' => $cards->count(),
        'data' => $cards
    ]);
}


   
   
   
    public function indexsold()
    {

        return view('comapny.card.indexsold');
    }
    
    public function indexsoldpdf()
{
   $cards = Card::select('cards.*', 'issuings.created_at as issuing_date')
    ->join('issuings', 'issuings.cards_id', '=', 'cards.id')
    ->with(['cardstautes', 'requests', 'offices', 'companies']) // يمكنك الإبقاء على العلاقات
    ->where('cards.cardstautes_id', 2)
    ->where('cards.companies_id', Auth::user()->companies_id)
    ->orderBy('issuings.created_at', 'asc')
    ->get();
    $user = Auth::user(); // لعرض اسم المستخدم

    return view('comapny.card.indexsoldpdf', compact('cards', 'user'));
}
   
public function cardallsold()
{
    $cards = Card::select('cards.*')
        ->join('issuings', 'issuings.cards_id', '=', 'cards.id')
        ->where('cards.cardstautes_id', 2)
        ->where('cards.companies_id', Auth::user()->companies_id)
        ->orderByDesc('issuings.created_at')
        ->with(['users', 'companies', 'offices', 'cardstautes', 'requests']);

    return DataTables::eloquent($cards)
        ->addColumn('companies', function ($card) {
            return is_null($card->companies_id) ? 'الإتحاد الليبي للتأمين' : $card->companies->name;
        })
        ->addColumn('offices', function ($card) {
            return is_null($card->offices_id) ? 'لدى الشركة' : $card->offices->name;
        })
        ->addColumn('solddate', function ($card) {
            // نفترض علاقة issuing معرفة باسم issuings في الموديل
            $issuing = $card->issuings ?? \App\Models\issuing::where('cards_id', $card->id)->first();
            if ($issuing && $issuing->created_at) {
                return Carbon::parse($issuing->created_at)->format('Y-m-d H:i');
            }
            return '-';
        })
        ->rawColumns(['companies', 'offices', 'solddate'])
        ->make(true);
}



    public function indexactive()
    {

        return view('comapny.card.indexactive');
    }

   public function indexactivepdf()
    {

 $cards = Card::with(['offices:id,name', 'cardstautes:id,name', 'requests:id,request_number,uploded_datetime'])
        ->where('cardstautes_id', 1)
        ->where('companies_id', Auth::user()->companies_id)
        ->orderBy('created_at', 'DESC')
        ->get(['id', 'card_number', 'offices_id', 'cardstautes_id', 'requests_id','card_insert_date']);

        return view('comapny.card.indexactivepdf', compact('cards'));
    }

 public function cardallactive()
{
    $cards = Card::with(['offices:id,name', 'companies:id,name', 'cardstautes:id,name', 'requests:id,request_number,uploded_datetime'])
        ->where('cardstautes_id', 1)
        ->where('companies_id', Auth::user()->companies_id)
        ->orderBy('created_at', 'DESC')
        ->select(['id', 'card_number', 'companies_id', 'offices_id', 'cardstautes_id', 'requests_id', 'card_insert_date']);

    return datatables()->of($cards)
        ->addColumn('companies', fn($card) => optional($card->companies)->name ?? 'الإتحاد الليبي للتأمين')
        ->addColumn('offices', fn($card) => optional($card->offices)->name ?? 'لدي الشركة')
        ->addColumn('cardstautes_name', fn($card) => optional($card->cardstautes)->name ?? '-')
        ->addColumn('request_number', fn($card) => optional($card->requests)->request_number ?? '-')
        ->rawColumns(['companies', 'offices', 'cardstautes_name', 'request_number'])
        ->make(true);
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        //
    }
}
