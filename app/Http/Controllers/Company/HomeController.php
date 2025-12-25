<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\issuing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ActivityLog;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:companys');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->user_type_id == 1) {;

            // $issiungtoday = issuing::whereDate('issuing_date', Carbon::today())
            //     ->where('companies_id', Auth::user()->companies_id)
            //     ->orWhereHas('offices', function ($query) {
            //         $query->where('companies_id', Auth::user()->companies_id)
            //             ->whereDate('issuing_date', Carbon::today()); // Add where clause if necessary
            //     })
            //     ->count();
            // $issiungmonth = issuing::whereMonth('issuing_date', Carbon::now()->month)->where('companies_id', Auth::user()->companies_id)->orwhereHas('offices',  function ($query) {
            //     $query->where('companies_id', Auth::user()->companies_id)
            //         ->whereMonth('issuing_date', Carbon::now()->month);
            // })->count();
            // $issiung = issuing::where('companies_id', Auth::user()->companies_id)->orwhereHas('offices',  function ($query) {
            //     $query->where('companies_id', Auth::user()->companies_id);
            // })->count();

            // $issiungtodaysum = issuing::whereDate('issuing_date', Carbon::today())->where('companies_id', Auth::user()->companies_id)->orwhereHas('offices',  function ($query) {
            //     $query->where('companies_id', Auth::user()->companies_id)
            //     ->whereDate('issuing_date', Carbon::today()); // Add where clause if necessary

            // })->sum('insurance_total');


            // $issiungmonthsum = issuing::whereMonth('issuing_date', Carbon::now()->month)->where('companies_id', Auth::user()->companies_id)->orwhereHas('offices',  function ($query) {
            //     $query->where('companies_id', Auth::user()->companies_id)
            //     ->whereMonth('issuing_date', Carbon::now()->month);

            // })->sum('insurance_total');
            // $issiungsum = issuing::where('companies_id', Auth::user()->companies_id)->orwhereHas('offices',  function ($query) {
            //     $query->where('companies_id', Auth::user()->companies_id);
            // })->sum('insurance_total');

            // $chartissiung = [
            //     'chart_title' => 'عدد البطاقات التي تم اصدارها ',
            //     'report_type' => 'group_by_date',
            //     'model' => 'App\Models\issuing',
            //     'group_by_field' => 'created_at',
            //     'group_by_period' => 'month',
            //     'chart_type' => 'bar',
            //     'chart_color' => '170, 89, 64',
            //     'chart_backgroundColor' => '170, 89, 64',
            //     'orWhereHas' => [
            //         'relationship_name' => function ($query) {
            //             $query->where('related_field', 'condition');
            //         }
            //     ],
            //     'orWhere' => [
            //         'companies_id =' . Auth::user()->companies_id,

            //     ]
            // ];

            // $issiungchart = new LaravelChart($chartissiung);



            // $Totalissuingmonth = [
            //     'chart_title' => 'اجمالي قيمة البطاقات التي تم اصدارها',
            //     'report_type' => 'group_by_date',
            //     'model' => 'App\Models\issuing',
            //     // Assuming your income model class is App\Models\Income
            //     'group_by_field' => 'created_at', // Group data by income creation date
            //     'expression' => '{monthName(created_at)}|{total_income:sum(insurance_total)}',  // Use monthName function            'aggregate_function' => 'sum',  // Use SUM for total income  // Redundant here, removed
            //     'group_by_period' => 'month',   // Group by month for monthly income
            //     'aggregate_function' => 'sum',
            //     'aggregate_field' => 'insurance_total', // Optional chart color (orange)

            //     'chart_type' => 'bar',           // Display data in a bar chart
            //     'chart_color' => '255, 165, 0',  // Optional chart color (orange)
            //     'chart_backgroundColor' => '255, 165, 0',
            //     // Optional chart background color (orange)
            //     // 'where_raw'          => 'companies_id =' . Auth::user()->companies_id,
            //     'orWhereHas' => [
            //         'offices' => function ($query) {
            //             $query->where('companies_id', Auth::user()->companies_id);
            //         }
            //     ],
            //     'orWhere' => [
            //         'companies_id =' . Auth::user()->companies_id,

            //     ]
            // ];

            // $sumchartmonth = new LaravelChart($Totalissuingmonth);

            $cardsstock = DB::table('cards')
                ->select(
                    DB::raw('COUNT(*) AS total_cards'),
                    DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                    DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'),
                    DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                    // Count active cards
                    'cards.id',
                    'cardstautes.id as cardstautes_id',
                    'companies.name as companiesname'
                ) // Use alias for offices.id
                ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
                ->join('companies', 'companies.id', '=', 'cards.companies_id')
                ->where('companies.id',  Auth::user()->companies_id)
                ->groupBy('companiesname')
                ->first();


            return view('comapny.home')
                ->with('cardsstock', $cardsstock)
                // ->with('issiungchart', $issiungchart)

                // ->with('issiungchart', $issiungchart)
                // ->with('sumchartmonth', $sumchartmonth)

                // ->with('issiung', $issiung)
                // ->with('issiungmonth', $issiungmonth)
                // ->with('issiungtoday', $issiungtoday)

                // ->with('issiungsum', $issiungsum)
                // ->with('issiungtodaysum', $issiungtodaysum)
                // ->with('issiungmonthsum', $issiungmonthsum)
                ;
        } else {

            $issiungtoday = issuing::whereDate('issuing_date', Carbon::today())->where('company_users_id', Auth::user()->id)->count();

            $issiungmonth = issuing::whereMonth('issuing_date', Carbon::now()->month)->where('company_users_id', Auth::user()->id)->count();
            $issiung = issuing::where('company_users_id', Auth::user()->id)->count();

            $issiungtodaysum = issuing::whereDate('issuing_date', Carbon::today())->where('company_users_id', Auth::user()->id)->sum('insurance_total');


            $issiungmonthsum = issuing::whereMonth('issuing_date', Carbon::now()->month)->where('company_users_id', Auth::user()->id)->sum('insurance_total');
            $issiungsum = issuing::where('company_users_id', Auth::user()->id)->sum('insurance_total');



            $chartissiung = [
                'chart_title' => 'عدد البطاقات التي تم اصدارها ',
                'report_type' => 'group_by_date',
                'model' => 'App\Models\issuing',
                'group_by_field' => 'created_at',
                'group_by_period' => 'month',
                'chart_type' => 'bar',
                'chart_color' => '170, 89, 64',
                'chart_backgroundColor' => '170, 89, 64',

                'where_raw'          => 'company_users_id =' . Auth::user()->id

            ];

            $issiungchart = new LaravelChart($chartissiung);



            $Totalissuingmonth = [
                'chart_title' => 'اجمالي قيمة البطاقات التي تم اصدارها',
                'report_type' => 'group_by_date',
                'model' => 'App\Models\issuing',
                // Assuming your income model class is App\Models\Income
                'group_by_field' => 'created_at', // Group data by income creation date
                'expression' => '{monthName(created_at)}|{total_income:sum(insurance_total)}',  // Use monthName function            'aggregate_function' => 'sum',  // Use SUM for total income  // Redundant here, removed
                'group_by_period' => 'month',   // Group by month for monthly income
                'aggregate_function' => 'sum',
                'aggregate_field' => 'insurance_total', // Optional chart color (orange)

                'chart_type' => 'bar',           // Display data in a bar chart
                'chart_color' => '255, 165, 0',  // Optional chart color (orange)
                'chart_backgroundColor' => '255, 165, 0',
                // Optional chart background color (orange)
                'where_raw'          => 'company_users_id =' . Auth::user()->id

            ];

            $sumchartmonth = new LaravelChart($Totalissuingmonth);



            return view('comapny.homeuser')
                ->with('issiungchart', $issiungchart)

                ->with('issiungchart', $issiungchart)
                ->with('sumchartmonth', $sumchartmonth)

                ->with('issiung', $issiung)
                ->with('issiungmonth', $issiungmonth)
                ->with('issiungtoday', $issiungtoday)

                ->with('issiungsum', $issiungsum)
                ->with('issiungtodaysum', $issiungtodaysum)
                ->with('issiungmonthsum', $issiungmonthsum);
        }
    }




public function indexx()
{
    $user = Auth::user();
    $currentMonth = Carbon::now()->month;
    $today = Carbon::today();
    
    if ($user->user_type_id == 1) {
        $companyId = $user->companies_id;

        // استعلام واحد يجمع جميع الإحصائيات دفعة واحدة
        $issuings = Issuing::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN DATE(issuing_date) = ? THEN 1 ELSE 0 END) as today_count,
            SUM(CASE WHEN MONTH(issuing_date) = ? THEN 1 ELSE 0 END) as month_count,
            SUM(insurance_total) as total_sum,
            SUM(CASE WHEN DATE(issuing_date) = ? THEN insurance_total ELSE 0 END) as today_sum,
            SUM(CASE WHEN MONTH(issuing_date) = ? THEN insurance_total ELSE 0 END) as month_sum
        ", [$today, $currentMonth, $today, $currentMonth])
        ->where('companies_id', $companyId)
        ->first();

        // مخزون البطاقات
        $cardsstock = DB::table('cards')
            ->selectRaw("
                COUNT(*) AS total_cards,
                COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards,
                COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold,
                COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel
            ")
            ->where('companies_id', $companyId)
            ->first();

        // إعداد بيانات الرسم البياني
        $chartIssuingConfig = [
            'chart_title'        => 'عدد البطاقات التي تم اصدارها',
            'report_type'        => 'group_by_date',
            'model'              => 'App\Models\Issuing',
            'group_by_field'     => 'created_at',
            'group_by_period'    => 'month',
            'chart_type'         => 'bar',
            'chart_color'        => '170, 89, 64',
            'chart_backgroundColor' => '170, 89, 64',
            'where_raw'          => "companies_id = $companyId",
        ];
        $chartIssuing = new LaravelChart($chartIssuingConfig);

        $sumChartMonthConfig = [
            'chart_title'        => 'اجمالي قيمة البطاقات التي تم اصدارها',
            'report_type'        => 'group_by_date',
            'model'              => 'App\Models\Issuing',
            'group_by_field'     => 'created_at',
            'group_by_period'    => 'month',
            'aggregate_function' => 'sum',
            'aggregate_field'    => 'insurance_total',
            'chart_type'         => 'bar',
            'chart_color'        => '255, 165, 0',
            'chart_backgroundColor' => '255, 165, 0',
            'where_raw'          => "companies_id = $companyId",
        ];
        $sumChartMonth = new LaravelChart($sumChartMonthConfig);

        return view('comapny.home', compact('cardsstock', 'chartIssuing', 'sumChartMonth', 'issuings'));
    } else {
        $userId = $user->id;

        // استعلام واحد يجمع جميع الإحصائيات للمستخدم
        $issuings = Issuing::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN DATE(issuing_date) = ? THEN 1 ELSE 0 END) as today_count,
            SUM(CASE WHEN MONTH(issuing_date) = ? THEN 1 ELSE 0 END) as month_count,
            SUM(insurance_total) as total_sum,
            SUM(CASE WHEN DATE(issuing_date) = ? THEN insurance_total ELSE 0 END) as today_sum,
            SUM(CASE WHEN MONTH(issuing_date) = ? THEN insurance_total ELSE 0 END) as month_sum
        ", [$today, $currentMonth, $today, $currentMonth])
        ->where('company_users_id', $userId)
        ->first();

        // إعداد بيانات الرسم البياني
        $chartIssuingConfig = [
            'chart_title'        => 'عدد البطاقات التي تم اصدارها',
            'report_type'        => 'group_by_date',
            'model'              => 'App\Models\Issuing',
            'group_by_field'     => 'created_at',
            'group_by_period'    => 'month',
            'chart_type'         => 'bar',
            'chart_color'        => '170, 89, 64',
            'chart_backgroundColor' => '170, 89, 64',
            'where_raw'          => "company_users_id = $userId",
        ];
        $chartIssuing = new LaravelChart($chartIssuingConfig);

        $sumChartMonthConfig = [
            'chart_title'        => 'اجمالي قيمة البطاقات التي تم اصدارها',
            'report_type'        => 'group_by_date',
            'model'              => 'App\Models\Issuing',
            'group_by_field'     => 'created_at',
            'group_by_period'    => 'month',
            'aggregate_function' => 'sum',
            'aggregate_field'    => 'insurance_total',
            'chart_type'         => 'bar',
            'chart_color'        => '255, 165, 0',
            'chart_backgroundColor' => '255, 165, 0',
            'where_raw'          => "company_users_id = $userId",
        ];
        $sumChartMonth = new LaravelChart($sumChartMonthConfig);

        return view('comapny.homeuser', compact('chartIssuing', 'sumChartMonth', 'issuings'));
    }
}


    public function showChangePasswordForm()
    {

        return view('comapny.change_form');
    }

    public function changePassword(Request $request)
    {
        $messages = [

            'current-password.required' => trans('users.current-password_r'),
            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' => trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'current-password' => ['required', 'string', 'min:6'],
            'new-password' => ['required', 'string', 'min:6'],
            'new-password-confirm' => ['required', 'same:new-password', 'string', 'min:6'],
        ], $messages);
        if (!(Hash::check($request->input('current-password'), Auth::user()->password))) {
            Alert::warning(trans('users.passwordnotmatcheing'));
            return redirect()->back();
        }
        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Log password change
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور للمستخدم',
            'user_name' => $user->name ?? $user->username,
            'performed_by' => Auth::user()->name ?? Auth::user()->username,
            'target_user' => $user->name ?? $user->username,
            'activity_date' => now(),
            'status' => 'success',
            'reason' => null,
        ]);

        Alert::success(trans('users.changesecc'));
        return redirect()->back();
    }
}
