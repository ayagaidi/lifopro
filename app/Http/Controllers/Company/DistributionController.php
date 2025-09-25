<?php

namespace App\Http\Controllers\Company;

use App\Models\Distribution;

use App\Http\Controllers\Controller;
use App\Models\car;
use App\Models\Card;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Yajra\DataTables\DataTables;

class DistributionController extends Controller
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
                ->where('company_user_permissions_id', 6)
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


        return view('comapny.distribution.index');
    }




    public function distributions()
    {
        $distributions = DB::table('distributions')
            ->select(DB::raw('sum(numerofcard) as total_cards'), 'offices.id as office_id', 'offices.name as offices_name')
            ->join('offices', 'distributions.offices_id', '=', 'offices.id')
            ->join('companies', 'companies.id', '=', 'offices.companies_id')
            ->where('companies.id', Auth::user()->companies_id)
            ->groupBy('offices.id')
            ->get();
    
        return datatables()->of($distributions)
            ->addColumn('show', function ($distributions) {
                $office_id = encrypt($distributions->office_id);
                return '<a style="color: gray;" href="' . route('company/distribution/detail', $office_id) . '"><i class="fa fa-file"></i></a>';
            })
            ->addColumn('delete', function ($distributions) {
                $deleteUrl = route('company/distribution/destroy', $distributions->office_id);
                return '<button class="btn btn-danger btn-sm delete-distribution" 
                            data-url="' . $deleteUrl . '" 
                            style="background-color: white;border: white;color: red;font-size: 20px;">
                            <i class="fa fa-trash"></i>
                        </button>';
            })
            ->rawColumns(['show', 'delete'])
            ->make(true);
    }
    


    public function indexdetail($office_id)
    {
        $id = decrypt($office_id);


        $office = Office::find($id);

        return view('comapny.distribution.indexdetail')
            ->with('office', $office)
            ->with('office_id', $office_id);
    }


    public function detailall($office_id)
    {

        $id = decrypt($office_id);

        $distributions = Distribution::with(['offices', 'offices.companies'])
            ->whereHas('offices.companies', function ($query) {
                $query->where('id', Auth::user()->companies_id);
            })
            ->where('offices_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables()->of($distributions)



            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Offices = Office::where('companies_id', Auth::user()->companies_id)->get();
        return view('comapny.distribution.create')
            ->with('Offices', $Offices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'numerofcard.required' => 'ادخل  عدد البطاقات',
            'offices_id.required' => 'اختر المكتب',
        ];

        $this->validate($request, [
            'offices_id' => 'required',
            'numerofcard' => 'required|integer',
        ], $messages);

        try {
            $cardsCount = Card::where('companies_id', Auth::user()->companies_id)
                ->where('cardstautes_id', 1)
                ->where('card_on_hold', 0)
                ->whereNull('offices_id')
                ->count();

            if ($cardsCount >= $request->numerofcard) {
                DB::transaction(function () use ($request) {
                    $distribution = new Distribution();
                    $distribution->numerofcard = $request->numerofcard;
                    $distribution->offices_id = $request->offices_id;
                    $distribution->company_users_id = Auth::user()->id;
                    $distribution->save();

                    $cards = Card::where('companies_id', Auth::user()->companies_id)
                        ->where('cardstautes_id', 1)
                        ->where('card_on_hold', 0)
                        ->whereNull('offices_id')
                        ->limit($request->numerofcard)
                        ->get();

                    foreach ($cards as $card) {

                        $card->offices_id = $request->offices_id;
                        $card->save();
                    }
                });

                Alert::success('تمت اضافة توزبعة بنجاح');
                return redirect()->route('company/distribution');
            } else {
                // Handle insufficient cards case (e.g., show an error message)
                Alert::warning('لا يوجد عدد كافي من البطاقات');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error($e);

            Alert::warning($e . 'فشل اضافة توزبعة');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Distribution $distribution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distribution $distribution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Distribution $distribution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     try {
    //         DB::transaction(function () use ($id) {
    //             // العثور على التوزيعة
    //             $distribution = Distribution::findOrFail($id);

    //             // حذف التوزيعة
    //             $distribution->delete();
    //         });

    //         Alert::success('تم حذف التوزيعة بنجاح');
    //         return redirect()->route('company/distribution');
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         Alert::error('فشل في حذف التوزيعة');
    //         return redirect()->back();
    //     }
    // }


    public function destroy($office_id)
{
    try {
        DB::transaction(function () use ($office_id) {
            Distribution::where('offices_id', $office_id)->delete();
             Card::with(['users', 'companies', 'offices', 'cardstautes', 'requests'])
            ->where('offices_id', $office_id)->where('cardstautes_id',1)
            ->update(['offices_id' => null, 'companies_id' => Auth::user()->companies_id]); // Return them to the company
       
        });

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error($e);
        return response()->json(['success' => false], 500);
    }
}

}


