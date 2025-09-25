<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Offices;
use App\Models\Card;
use App\Models\Cardstautes;
use App\Models\Company;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class CardController extends Controller
{
       public function __construct()
    {
        $this->middleware('auth:officess');

    $this->middleware(function ($request, $next) {
        $user = Auth::guard('officess')->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // تحقق من نوع المستخدم
        if ($user->userType->id == 1) {
            return $next($request); // مسموح للمستخدم من نوع 1
        }

        if ($user->userType->id == 2) {
            $hasPermission = \App\Models\OfficeUserRole::where('office_users_id', $user->id)
                ->where('office_user_permissions_id', 1)
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
        $Cardstautes = Cardstautes::where('id','!=',0)->get();
        return view('office.card.search')
            ->with('Cardstautes', $Cardstautes);
    }

    public function searchby(Request $request)
    {

        $from = Carbon::parse($request->fromdate)->format('Y-m-d');
        $to = Carbon::parse($request->todate)->format('Y-m-d');

        $card = Card::with(['users', 'companies', 'offices',  'cardstautes', 'requests'])
            ->where('offices_id', Auth::user()->offices_id)
            ->select('*');
        if ($request->request_number) {
            $card = $card->whereHas('requests',  function ($query) use ($request) {
                $query->where('request_number', $request->request_number);
            });
        }



        if ($request->card_number) {
            $card->where('card_number', $request->card_number);
        }

        if ($request->cardstautes_id) {

            $card->where('cardstautes_id', $request->cardstautes_id);
        }

        if (!empty($request->fromdate) && !empty($request->todate)) {
            $card = $card->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);

        }
        $card = $card->get();

        if ($card->isEmpty()) {
            return response()->json([
                'code' => 2,
                'status' => false,
                'message' => 'لايوجد بطاقات',
            ], 200);
        } else {
            return response()->json([
                'code' => 1,

                'status' => true,
                'message' => 'يتم عرض البطاقات ',
                'data' => $card
            ], 200);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('office.card.index');
    }
    public function cardall()
    {

        $card = Card::with(['users', 'companies', 'offices', 'cardstautes', 'requests'])
            ->where('offices_id', Auth::user()->offices_id)->orderBy('created_at', 'DESC');
        return datatables()->of($card)
            ->addColumn('companies', function ($card) {
                if (is_null($card->companies_id)) {
                    return 'الإتحاد الليبي للتأمين';
                } else {
                    return $card->companies->name;
                }
            })
            ->addColumn('offices', function ($card) {
                if (is_null($card->offices_id)) {
                    return ' لدي الشركة';
                } else {
                    return $card->offices->name;
                }
            })
            ->rawColumns(['companies', 'offices'])

            ->make(true);
    }

    public function indexcancel()
    {

        return view('office.card.indexcancel');
    }

    public function cardallcancel()
    {

        $card = Card::with(['users', 'companies', 'offices', 'cardstautes', 'requests'])
            ->where('cardstautes_id', 3)
            ->where('offices_id', Auth::user()->offices_id)->orderBy('created_at', 'DESC');
        return datatables()->of($card)
            ->addColumn('companies', function ($card) {
                if (is_null($card->companies_id)) {
                    return 'الإتحاد الليبي للتأمين';
                } else {
                    return $card->companies->name;
                }
            })
            ->addColumn('offices', function ($card) {
                if (is_null($card->offices_id)) {
                    return ' لدي الشركة';
                } else {
                    return $card->offices->name;
                }
            })
            ->rawColumns(['companies', 'offices'])

            ->make(true);
    }
    public function indexsold()
    {

        return view('office.card.indexsold');
    }

    
    public function cardallsold()
    {

        $card = Card::with(['users', 'companies', 'offices', 'cardstautes', 'requests'])
            ->where('cardstautes_id', 2)
            ->where('offices_id', Auth::user()->offices_id)->orderBy('created_at', 'DESC');
        return datatables()->of($card)
            ->addColumn('companies', function ($card) {
                if (is_null($card->companies_id)) {
                    return 'الإتحاد الليبي للتأمين';
                } else {
                    return $card->companies->name;
                }
            })
            ->addColumn('offices', function ($card) {
                if (is_null($card->offices_id)) {
                    return ' لدي الشركة';
                } else {
                    return $card->offices->name;
                }
            })
            ->rawColumns(['companies', 'offices'])

            ->make(true);
    }


    public function indexactive()
    {

        return view('office.card.indexactive');
    }


    public function cardallactive()
    {

        $card = Card::with(['users', 'offices', 'companies', 'cardstautes', 'requests'])
            ->where('cardstautes_id', 1)
            ->where('offices_id', Auth::user()->offices_id)->orderBy('created_at', 'DESC');
        return datatables()->of($card)
            ->addColumn('companies', function ($card) {
                if (is_null($card->companies_id)) {
                    return 'الإتحاد الليبي للتأمين';
                } else {
                    return $card->companies->name;
                }
            })

            ->addColumn('offices', function ($card) {
                if (is_null($card->offices_id)) {
                    return ' لدي الشركة';
                } else {
                    return $card->offices->name;
                }
            })
            ->rawColumns(['companies', 'offices'])
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
