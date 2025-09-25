<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\issuing;
use App\Models\Office;
use App\Models\OfficeUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   public function index()
{
    $company = Company::count();
    $CompanyUser = CompanyUser::count();
    $Office = Office::count();
    $OfficeUser = OfficeUser::count();

    $today = Carbon::today();
    $month = Carbon::now()->month;

    $issuings = issuing::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN DATE(issuing_date) = ? THEN 1 ELSE 0 END) as today_count,
            SUM(CASE WHEN MONTH(issuing_date) = ? THEN 1 ELSE 0 END) as month_count,
            SUM(CASE WHEN DATE(issuing_date) = ? THEN insurance_total ELSE 0 END) as today_sum,
            SUM(CASE WHEN MONTH(issuing_date) = ? THEN insurance_total ELSE 0 END) as month_sum,
            SUM(insurance_total) as total_sum
        ", [$today, $month, $today, $month])->first();

    $cards = Card::selectRaw("
        COUNT(*) as all_cards,
        SUM(CASE WHEN cardstautes_id = 1 THEN 1 ELSE 0 END) as active_cards,
        SUM(CASE WHEN cardstautes_id = 2 THEN 1 ELSE 0 END) as sold,
        SUM(CASE WHEN cardstautes_id = 3 THEN 1 ELSE 0 END) as cancel,
        SUM(CASE WHEN cardstautes_id = 1 AND companies_id IS NOT NULL THEN 1 ELSE 0 END) as companystock,
        SUM(CASE WHEN cardstautes_id = 0 AND companies_id IS NULL THEN 1 ELSE 0 END) as companystocksyill,
        SUM(CASE WHEN companies_id IS NOT NULL THEN 1 ELSE 0 END) as totalcompanystock
    ")->first();
    
            $unionCards = Card::whereNull('companies_id')->whereNull('offices_id')->count();


    return view('home', compact(
        'company',
        'CompanyUser',
        'Office',
        'OfficeUser',
        'issuings',
        'cards',
        'unionCards'
    ))->with('unionCards',$unionCards);
}

}
