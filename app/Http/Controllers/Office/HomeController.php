<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\issuing;
use App\Models\OfficeUser;
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
        $this->middleware('auth:officess');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
         if (Auth::user()->user_type_id == 1) {


            $issiungtoday=issuing::where('offices_id',Auth::user()->offices_id)->whereDate('issuing_date', Carbon::today()) ->count();
            
            $issiungmonth=issuing::where('offices_id',Auth::user()->offices_id) ->whereMonth('issuing_date', Carbon::now()->month)->count();
            $issiung=issuing::where('offices_id',Auth::user()->offices_id)->count();
            
            $issiungtodaysum=issuing::where('offices_id',Auth::user()->offices_id)->whereDate('issuing_date', Carbon::today())->sum('insurance_total');
            $issiungmonthsum=issuing::where('offices_id',Auth::user()->offices_id) ->whereMonth('issuing_date', Carbon::now()->month)->sum('insurance_total');
            $issiungsum=issuing::where('offices_id',Auth::user()->offices_id)->sum('insurance_total');

            $chartissiung = [
                'chart_title' => 'عدد البطاقات التي تم اصدارها ',
                'report_type' => 'group_by_date',
                'model' => 'App\Models\issuing',
                'group_by_field' => 'created_at',
                'group_by_period' => 'month',
                'chart_type' => 'bar',
                'chart_color' => '170, 89, 64',
                'chart_backgroundColor' => '170, 89, 64',
                'where_raw'          => 'offices_id =' . Auth::user()->offices_id


            ];


            $issiungchart = new LaravelChart($chartissiung);


       
            $Totalissuingmonth= [
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
                'where_raw'          => 'offices_id =' . Auth::user()->offices_id,
    
            ];
            
            $cardsstock = DB::table('cards')
            ->select(
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'), 
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                // Count active cards
            'cards.id', 'cardstautes.id as cardstautes_id', 'offices.name as officesname') // Use alias for offices.id
            ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
            ->join('offices', 'offices.id', '=', 'cards.offices_id')
            ->where('offices.id',  Auth::user()->offices_id)
            ->groupBy('officesname')
            ->first();
            $sumchartmonth = new LaravelChart($Totalissuingmonth);
            $distributions=Distribution::where('offices_id',Auth::user()->offices_id)->first();
            if (!is_null($distributions)) {

              
                $cardsstockkk = DB::table('cards')
                ->select(
                                        DB::raw('COUNT(*) AS total_cards'),

                    DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 1 THEN 1 END) AS active_cards'),
                    DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) AS sold'), 
                    DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) AS cancel'),  // Count active cards
                    // Count active cards
                'cards.id', 'cardstautes.id as cardstautes_id', 'offices.name as officesname') // Use alias for offices.id
                ->join('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
                ->join('offices', 'offices.id', '=', 'cards.offices_id')
                ->where('offices.id',  Auth::user()->offices_id)
                ->groupBy('officesname')
                ->first();


            }else{


            $cardsstockkk = DB::table('cards')
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
            ->where('companies.id',  Auth::user()->offices->companies_id)
            ->groupBy('companiesname')
            ->first();
       
              
               


            }
            

            return view('office.home')
            ->with('cardsstock',$cardsstock)
            ->with('issiungchart',$issiungchart)
            ->with('cardsstockkk',$cardsstockkk)

            ->with('issiungchart',$issiungchart)
            ->with('sumchartmonth',$sumchartmonth)

            ->with('issiung',$issiung)
            ->with('issiungmonth',$issiungmonth)
            ->with('issiungtoday',$issiungtoday)

            ->with('issiungsum',$issiungsum)
            ->with('issiungtodaysum',$issiungtodaysum)
            ->with('issiungmonthsum',$issiungmonthsum);
        } else {

            $issiungtoday=issuing::where('office_users_id',Auth::user()->id)->whereDate('issuing_date', Carbon::today()) ->count();
            
            $issiungmonth=issuing::where('office_users_id',Auth::user()->id) ->whereMonth('issuing_date', Carbon::now()->month)->count();
            $issiung=issuing::where('office_users_id',Auth::user()->id)->count();
            $issiungtodaysum=issuing::where('office_users_id',Auth::user()->id)->whereDate('issuing_date', Carbon::today())->sum('insurance_total');
            $issiungmonthsum=issuing::where('office_users_id',Auth::user()->id) ->whereMonth('issuing_date', Carbon::now()->month)->sum('insurance_total');
            $issiungsum=issuing::where('office_users_id',Auth::user()->id)->sum('insurance_total');
   
            $chartissiung = [
                'chart_title' => 'عدد البطاقات التي تم اصدارها ',
                'report_type' => 'group_by_date',
                'model' => 'App\Models\issuing',
                'group_by_field' => 'created_at',
                'group_by_period' => 'month',
                'chart_type' => 'bar',
                'chart_color' => '170, 89, 64',
                'chart_backgroundColor' => '170, 89, 64',
                'where_raw'          => 'office_users_id =' . Auth::user()->id


            ];


            $issiungchart = new LaravelChart($chartissiung);


       
            $Totalissuingmonth= [
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
                'where_raw'          => 'office_users_id =' . Auth::user()->id,
    
            ];
            
            $sumchartmonth = new LaravelChart($Totalissuingmonth);




            return view('office.homeofficeuer')

            ->with('issiungchart',$issiungchart)

            ->with('issiungchart',$issiungchart)
            ->with('sumchartmonth',$sumchartmonth)

            ->with('issiung',$issiung)
            ->with('issiungmonth',$issiungmonth)
            ->with('issiungtoday',$issiungtoday)
            ->with('issiungsum',$issiungsum)
            ->with('issiungtodaysum',$issiungtodaysum)
            ->with('issiungmonthsum',$issiungmonthsum);
        }
 


    }


    public function show($id)
    {
        $user_id = decrypt($id);
        $user = OfficeUser::find($user_id);

        return view('office.profile')->with('user', $user);
    }

    public function showChangePasswordForm()
    {

        return view('office.change_form');
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
            'detailed_description' => 'تم تغيير كلمة المرور لمستخدم المكتب',
            'user_name' => $user->name ?? $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->name ?? $user->username,
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تحديث أمني',
        ]);

        Alert::success(trans('users.changesecc'));
        return redirect()->back();
    }
}
