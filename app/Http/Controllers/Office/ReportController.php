<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\issuing;
use App\Models\OfficeUser;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
                ->where('office_user_permissions_id', 3)
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

        $OfficeUsers = OfficeUser::where('offices_id', Auth::user()->offices_id)->get();
        return view('office.report.search')
            ->with('OfficeUsers', $OfficeUsers);
    }

    public function indexsummary()
    {

        $OfficeUsers = OfficeUser::where('offices_id', Auth::user()->offices_id)->get();
        return view('office.report.searchsummery')
            ->with('OfficeUsers', $OfficeUsers);
    }


    public function searchby(Request $request)
    {
        // Parse and format dates
        $from = Carbon::parse($request->fromdate)->format('Y-m-d');
        $to = Carbon::parse($request->todate)->format('Y-m-d');

        // Initialize query with eager loading
        $issuing = Issuing::with(['vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])
            ->where('offices_id', Auth::user()->offices_id)
            ->select('*');

        // Apply user-type-based filtering
        if (Auth::user()->user_type_id == 1) {
            // Check if office_users_id is provided
            if (!empty($request->office_users_id)) {
                if ($request->office_users_id == 0) {

                    $issuing;
                } else {
                    $issuing->where('office_users_id', $request->office_users_id);
                }
            }
        } else {
            $issuing->where('office_users_id', Auth::user()->id);
        }


        // Apply search criteria
        if (!empty($request->card_number)) {
            $issuing->whereHas('cards', function ($query) use ($request) {
                $query->where('card_number', $request->card_number);
            });
        }


        if (!empty($request->insurance_name)) {
            $issuing->where('insurance_name', 'like', $request->insurance_name);
        }

        if (!empty($request->plate_number)) {
            $issuing->where('plate_number', $request->plate_number);
        }

        if (!empty($request->fromdate) && !empty($request->todate)) {
            $issuing = $issuing->whereBetween('issuing_date', [$from . " 00:00:00", $to . " 23:59:59"]);
        }

        // Apply date range filtering (if provided)


        // Order and retrieve results
        $issuing = $issuing->orderBy('created_at', 'DESC')->get();

        // Check if results are empty
        if ($issuing->isEmpty()) {
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
        $from = Carbon::parse($request->fromdate)->format('Y-m-d');
        $to = Carbon::parse($request->todate)->format('Y-m-d');

        // Initialize query with eager loading
        $issuing = Issuing::with(['vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])
            ->where('offices_id', Auth::user()->offices_id)
            ->select('*');

        // Apply user-type-based filtering
        if (Auth::user()->user_type_id == 1) {
            // Check if office_users_id is provided
            if (!empty($request->office_users_id)) {
                if ($request->office_users_id == 0) {

                    $issuing;
                } else {
                    $issuing->where('office_users_id', $request->office_users_id);
                }
            }
        } else {
            $issuing->where('office_users_id', Auth::user()->id);
        }


        // Apply search criteria
        if (!empty($request->card_number)) {
            $issuing->whereHas('cards', function ($query) use ($request) {
                $query->where('card_number', $request->card_number);
            });
        }


        if (!empty($request->insurance_name)) {
            $issuing->where('insurance_name', 'like', $request->insurance_name);
        }

        if (!empty($request->plate_number)) {
            $issuing->where('plate_number', $request->plate_number);
        }

        if (!empty($request->fromdate) && !empty($request->todate)) {
            $issuing = $issuing->whereBetween('issuing_date', [$from . " 00:00:00", $to . " 23:59:59"]);
        }

        // Apply date range filtering (if provided)


        // Order and retrieve results
        $issuing = $issuing->orderBy('created_at', 'ASC')->get();

        // Check if results are empty
        // Check if results are empty
        if ($issuing->isEmpty()) {
            FacadesAlert::warning(" لايوجد بطاقات");

            return redirect()->back();
        } else {

            $total = $issuing->sum('insurance_total');

            return view('office.report.resul')
            ->with('fromdate', $request->fromdate)
            ->with('todate', $request->todate)
                ->with('issuing', $issuing)
                ->with('total', $total);
        }
    }


    public function searchpdfresultsummary(Request $request)
    {
        // Parse and format dates
        $from = Carbon::parse($request->fromdate)->format('Y-m-d');
        $to = Carbon::parse($request->todate)->format('Y-m-d');

        // Initialize query with eager loading
        $issuing = Issuing::with(['vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])
            ->where('offices_id', Auth::user()->offices_id)
            ->select('*');

        // Apply user-type-based filtering
        if (Auth::user()->user_type_id == 1) {
            // Check if office_users_id is provided
            if (!empty($request->office_users_id)) {
                if ($request->office_users_id == 0) {

                    $issuing;
                } else {
                    $issuing->where('office_users_id', $request->office_users_id);
                }
            }
        } else {
            $issuing->where('office_users_id', Auth::user()->id);
        }


        // Apply search criteria
        if (!empty($request->card_number)) {
            $issuing->whereHas('cards', function ($query) use ($request) {
                $query->where('card_number', $request->card_number);
            });
        }


        if (!empty($request->insurance_name)) {
            $issuing->where('insurance_name', 'like', $request->insurance_name);
        }

        if (!empty($request->plate_number)) {
            $issuing->where('plate_number', $request->plate_number);
        }

        if (!empty($request->fromdate) && !empty($request->todate)) {
            $issuing = $issuing->whereBetween('issuing_date', [$from . " 00:00:00", $to . " 23:59:59"]);
        }

        // Apply date range filtering (if provided)


        // Order and retrieve results
        $issuing = $issuing->orderBy('created_at', 'ASC')->get();

        // Check if results are empty
        // Check if results are empty
        if ($issuing->isEmpty()) {
            FacadesAlert::warning(" لايوجد بطاقات");

            return redirect()->back();
        } else {

            $total = $issuing->sum('insurance_total');

            return view('office.report.resultsummary')
            ->with('fromdate', $request->fromdate)
            ->with('todate', $request->todate)
                ->with('issuing', $issuing)
                ->with('total', $total);
        }
    }

    public function indexstock()
    {





        $cardsstock = DB::table('cards')
            ->select(
                DB::raw('COUNT(*) AS total_cards'),
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
            ->where('offices.id',  Auth::user()->offices_id)
            ->groupBy('officesname')
            ->get();
        return view('office.report.stockindex')->with('cardsstock', $cardsstock);
    }
}
