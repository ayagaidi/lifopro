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
use Illuminate\Support\Facades\Auth;


class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::check()) {
            // Redirect to the user's page (adjust the route name as needed)
            return redirect()->route('home'); 
        }else if (Auth::guard('companys')->check()) {
            return redirect()->route('company/report/issuing'); // your default page for company
        }else
    
        if (Auth::guard('officess')->check()) {
            return redirect()->route('office/report/issuing'); // your default page for office
        }
    

        return view('index');
    }
}
