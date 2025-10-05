<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Offices;
use App\Models\Apiuser;
use App\Models\Card;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\issuing;
use App\Models\Office;
use App\Models\OfficeUser;
use App\Models\Requests;
use App\Models\RequestStatus;
use App\Services\LifoApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Runner\Baseline\Issue;
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\IssuingsExport;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;

use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;

class ReportController extends Controller
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
    public function indexsummaryarchives()
    {


        $Company = Company::get();
        return view('dashbord.report.searchsummaryarchev')
            ->with('Company', $Company);
    }



    public function searchbychives(Request $request)
    {
        $today = Carbon::now();
        $startOfYear = $today->copy()->startOfYear();

        $issuing = issuing::with([
            'cards',
            'vehicle_nationalities',
            'companies',
            'offices.companies',
            'company_users',
            'office_users',
            'cars'
        ])
            ->whereDate('issuing_date', '<', $startOfYear);

        // الفلاتر باستخدام when
        $issuing->when($request->filled('companies_id'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('companies_id', $request->companies_id)
                    ->orWhereHas('offices', function ($q2) use ($request) {
                        $q2->where('companies_id', $request->companies_id);
                    });
            });
        });

        $issuing->when($request->filled('offices_id'), function ($query) use ($request) {
            $query->where('offices_id', $request->offices_id);
        });

        $issuing->when($request->filled('company_users_id'), function ($query) use ($request) {
            $query->where('company_users_id', $request->company_users_id);
        });

        $issuing->when($request->filled('office_users_id'), function ($query) use ($request) {
            $query->where('office_users_id', $request->office_users_id);
        });

        $issuing->when($request->filled('card_number'), function ($query) use ($request) {
            $query->whereHas('cards', function ($q) use ($request) {
                $q->where('card_number', $request->card_number);
            });
        });

        $issuing->when($request->filled('insurance_name'), function ($query) use ($request) {
            $query->where('insurance_name', 'like', '%' . $request->insurance_name . '%');
        });

        $issuing->when($request->filled('plate_number'), function ($query) use ($request) {
            $query->where('plate_number', $request->plate_number);
        });

        $issuing->when($request->filled('chassis_number'), function ($query) use ($request) {
            $query->where('chassis_number', $request->chassis_number);
        });

        // التاريخ مع تحقق من السنة
        if ($request->filled('fromdate') && $request->filled('todate')) {
            $from = Carbon::parse($request->fromdate)->startOfDay();
            $to = Carbon::parse($request->todate)->endOfDay();

            // تحقق صارم من أن التاريخ لا يشمل السنة الحالية أو ما بعدها
            if ($from->gte($startOfYear) || $to->gte($startOfYear)) {
                return response()->json([
                    'code' => 0,
                    'status' => false,
                    'message' => 'يرجى تحديد تواريخ قبل بداية السنة الحالية فقط.',
                ], 422);
            }

            $issuing->whereBetween('issuing_date', [$from, $to]);
        }

        $results = $issuing->orderBy('created_at', 'desc')->get();

        if ($results->isEmpty()) {
            return response()->json([
                'code' => 2,
                'status' => false,
                'message' => 'لايوجد عمليات اصدار',
            ]);
        }

        return response()->json([
            'code' => 1,
            'status' => true,
            'message' => 'تم عرض البيانات بنجاح',
            'data' => $results,
        ]);
    }



    public function searchbychivespdf(Request $request)
    {
        $messages = [
            'fromdate.required' => "اختر الفترة من  ",
            'todate.required' => "اختر الفترة الي  ",
        ];

        $this->validate($request, [
            'fromdate' => ['required'],
            'todate' => ['required'],
        ], $messages);

        $from = Carbon::parse($request->fromdate)->startOfDay();
        $to = Carbon::parse($request->todate)->endOfDay();

        $startOfCurrentYear = Carbon::now()->startOfYear();

        // تحقق من أن التواريخ لا تقع في السنة الحالية أو بعدها
        if ($from >= $startOfCurrentYear || $to >= $startOfCurrentYear) {
            Alert::warning("يجب اختيار تاريخ قبل السنة الحالية فقط");
            return redirect()->back();
        }

        // بناء الاستعلام
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
            ->whereDate('issuing_date', '<', $startOfCurrentYear);

        // تطبيق الفلاتر
        if (!empty($request->companies_id)) {
            $issuing->where(function ($query) use ($request) {
                $query->where('companies_id', $request->companies_id)
                    ->orWhereHas('offices', function ($q) use ($request) {
                        $q->where('companies_id', $request->companies_id);
                    });
            });
        }

        if (!empty($request->offices_id)) {
            $issuing->where('offices_id', $request->offices_id);
        }

        if (!empty($request->company_users_id)) {
            $issuing->where('company_users_id', $request->company_users_id);
        }

        if (!empty($request->office_users_id)) {
            $issuing->where('office_users_id', $request->office_users_id);
        }

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

        if (!empty($request->chassis_number)) {
            $issuing->where('chassis_number', $request->chassis_number);
        }

        // نطاق التاريخ داخل السنوات الماضية فقط
        $issuing->whereBetween('issuing_date', [$from, $to]);

        // تنفيذ الاستعلام
        $results = $issuing->orderBy('created_at', 'DESC')->get();

        if ($results->isEmpty()) {
            Alert::warning("لايوجد بطاقات");
            return redirect()->back();
        }

        $total = $results->sum('insurance_total');

        return view('dashbord.report.result')
            ->with('fromdate', $request->fromdate)
            ->with('todate', $request->todate)
            ->with('issuing', $results)
            ->with('total', $total);
    }





    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexsummary()
    {


        $Company = Company::get();
        return view('dashbord.report.searchsummary')
            ->with('Company', $Company);
    }
    public function index(Request $request)
    {


        $Company = Company::get();
        return view('dashbord.report.search')
            ->with('Company', $Company);
    }





    // {
    //     $from = Carbon::parse($request->fromdate)->startOfDay();
    //     $to = Carbon::parse($request->todate)->endOfDay();

    //     $currentYearStart = Carbon::now()->startOfYear();
    //     $nextYearEnd = Carbon::now()->addYear()->endOfYear();

    //     if ($from->lt($currentYearStart) || $to->gt($nextYearEnd)) {
    //         return response()->json([
    //             'code' => 3,
    //             'status' => false,
    //             'message' => 'التاريخ يجب أن يكون ضمن السنة الحالية أو السنة القادمة فقط',
    //         ], 400);
    //     }

    //     $query = Issuing::query()
    //         ->with([
    //             'cards:id,card_number,id', 
    //             'companies:id,name', 
    //             'offices:id,name,companies_id', 
    //             'offices.companies:id,name',
    //             'company_users:id,username', 
    //             'office_users:id,username',
    //             'cars:id,name'
    //         ])
    //         ->select([
    //             'id', 'cards_id', 'companies_id', 'offices_id', 'company_users_id', 'office_users_id',
    //             'insurance_name', 'issuing_date', 'insurance_installment', 'insurance_tax',
    //             'insurance_stamp', 'insurance_supervision', 'insurance_version', 'insurance_total',
    //             'insurance_day_from', 'nsurance_day_to', 'insurance_days_number', 'plate_number',
    //             'chassis_number', 'motor_number', 'cars_id', 'created_at'
    //         ])
    //         ->whereBetween('issuing_date', [$from, $to]);

    //     $query->when($request->filled('companies_id'), function ($q) use ($request) {
    //         $q->where(function ($sub) use ($request) {
    //             $sub->where('companies_id', $request->companies_id)
    //                 ->orWhereHas('offices', function ($q2) use ($request) {
    //                     $q2->where('companies_id', $request->companies_id);
    //                 });
    //         });
    //     });

    //     $query->when($request->filled('offices_id'), fn($q) => $q->where('offices_id', $request->offices_id));
    //     $query->when($request->filled('company_users_id'), fn($q) => $q->where('company_users_id', $request->company_users_id));
    //     $query->when($request->filled('office_users_id'), fn($q) => $q->where('office_users_id', $request->office_users_id));
    //     $query->when($request->filled('card_number'), function ($q) use ($request) {
    //         $q->whereHas('cards', fn($q2) => $q2->where('card_number', $request->card_number));
    //     });
    //     $query->when($request->filled('insurance_name'), fn($q) => $q->where('insurance_name', 'like', '%' . $request->insurance_name . '%'));
    //     $query->when($request->filled('plate_number'), fn($q) => $q->where('plate_number', $request->plate_number));
    //     $query->when($request->filled('chassis_number'), fn($q) => $q->where('chassis_number', $request->chassis_number));

    //       // ✅ استخدم paginate بدل get()
    // $perPage = $request->get('per_page', 20);   
    // $results = $query->orderBy('created_at', 'desc')->paginate($perPage);
    // $totals = $query->clone()->selectRaw("
    //     SUM(insurance_installment) as total_installment,
    //     SUM(insurance_tax) as total_tax,
    //     SUM(insurance_stamp) as total_stamp,
    //     SUM(insurance_supervision) as total_supervision,
    //     SUM(insurance_version) as total_version,
    //     SUM(insurance_total) as total_insurance
    // ")->first();
    // // dd($results->count());
    //     if ($results->isEmpty()) {
    //         return response()->json([
    //             'code' => 2,
    //             'status' => false,
    //             'message' => 'لايوجد عمليات اصدار',
    //         ]);
    //     }

    // return response()->json([
    //         'code' => 1,
    //         'status' => true,
    //         'message' => 'تم عرض البيانات',
    //         'data' => $results->items(),
    //         'pagination' => [
    //             'current_page' => $results->currentPage(),
    //             'last_page' => $results->lastPage(),
    //             'per_page' => $results->perPage(),
    //             'total' => $results->total(),
    //         ],
    //             'totals' => $totals,
    //     ]);
    //     // return response()->json([
    //     //     'code' => 1,
    //     //     'status' => true,
    //     //     'message' => 'تم عرض البيانات',
    //     //     'data' => $results, // فقط النتائج الحالية

    //     // ]);
    // }public function searchby(Request $request)



    public function searchby(Request $request)
    {
        $from = Carbon::parse($request->fromdate)->startOfDay();
        $to   = Carbon::parse($request->todate)->endOfDay();

        // تقييد البحث بالسنة الحالية أو القادمة
        $currentYearStart = Carbon::now()->startOfYear();
        $nextYearEnd      = Carbon::now()->addYear()->endOfYear();

        if ($from->lt($currentYearStart) || $to->gt($nextYearEnd)) {
            return response()->json([
                'code' => 3,
                'status' => false,
                'message' => 'التاريخ يجب أن يكون ضمن السنة الحالية أو السنة القادمة فقط',
            ], 400);
        }

        $query = Issuing::query()
            ->with([
                'cards:id,card_number,id',
                'companies:id,name',
                'offices:id,name,companies_id',
                'offices.companies:id,name',
                'company_users:id,username',
                'office_users:id,username',
                'cars:id,name'
            ])
            ->select([
                'id',
                'cards_id',
                'companies_id',
                'offices_id',
                'company_users_id',
                'office_users_id',
                'insurance_name',
                'issuing_date',
                'insurance_installment',
                'insurance_tax',
                'insurance_stamp',
                'insurance_supervision',
                'insurance_version',
                'insurance_total',
                'insurance_day_from',
                'nsurance_day_to',
                'insurance_days_number',
                'plate_number',
                'chassis_number',
                'motor_number',
                'cars_id',
                'created_at'
            ])
            ->whereBetween('issuing_date', [$from, $to]);

        $query->when($request->filled('companies_id'), function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('companies_id', $request->companies_id)
                    ->orWhereHas('offices', function ($q2) use ($request) {
                        $q2->where('companies_id', $request->companies_id);
                    });
            });
        });

        $query->when($request->filled('offices_id'), fn($q) => $q->where('offices_id', $request->offices_id));
        $query->when($request->filled('company_users_id'), fn($q) => $q->where('company_users_id', $request->company_users_id));
        $query->when($request->filled('office_users_id'), fn($q) => $q->where('office_users_id', $request->office_users_id));
        $query->when($request->filled('card_number'), function ($q) use ($request) {
            $q->whereHas('cards', fn($q2) => $q2->where('card_number', $request->card_number));
        });
        $query->when($request->filled('insurance_name'), fn($q) => $q->where('insurance_name', 'like', '%' . $request->insurance_name . '%'));
        $query->when($request->filled('plate_number'), fn($q) => $q->where('plate_number', $request->plate_number));
        $query->when($request->filled('chassis_number'), fn($q) => $q->where('chassis_number', $request->chassis_number));

        // ====== حد منطقي للطباعة/الإظهار دفعة واحدة ======
        $maxPrintLimit = (int) env('PRINT_MAX_LIMIT', 100000);   // سقف صارم
        $warnPrintLimit = (int) env('PRINT_WARN_LIMIT', 100000); // تحذير مبكرة

        // نحتاج نسخة بدون eager loads لعدّ سريع
        $countQuery = Issuing::query()
            ->whereBetween('issuing_date', [$from, $to]);

        // نفس الفلاتر الأساسية للعد
        $countQuery->when($request->filled('companies_id'), function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('companies_id', $request->companies_id)
                    ->orWhereHas('offices', fn($q2) => $q2->where('companies_id', $request->companies_id));
            });
        });
        $countQuery->when($request->filled('offices_id'), fn($q) => $q->where('offices_id', $request->offices_id));
        $countQuery->when($request->filled('company_users_id'), fn($q) => $q->where('company_users_id', $request->company_users_id));
        $countQuery->when($request->filled('office_users_id'), fn($q) => $q->where('office_users_id', $request->office_users_id));
        $countQuery->when($request->filled('card_number'), function ($q) use ($request) {
            $q->whereHas('cards', fn($q2) => $q2->where('card_number', $request->card_number));
        });
        $countQuery->when($request->filled('insurance_name'), fn($q) => $q->where('insurance_name', 'like', '%' . $request->insurance_name . '%'));
        $countQuery->when($request->filled('plate_number'), fn($q) => $q->where('plate_number', $request->plate_number));
        $countQuery->when($request->filled('chassis_number'), fn($q) => $q->where('chassis_number', $request->chassis_number));

        $count = $countQuery->count();

        if ($count > $maxPrintLimit) {
            // حساب مدة مقترحة
            $days = max(1, $from->diffInDays($to) + 1);
            $perDay = $count / $days;
            $suggestDays = max(1, (int) floor($maxPrintLimit / max(1, $perDay)));
            $suggestTo = (clone $from)->addDays($suggestDays - 1)->format('Y-m-d');

            return response()->json([
                'code' => 4,
                'status' => false,
                'message' => "عدد النتائج كبير جداً ($count). الحد الأقصى للإظهار/الطباعة دفعة واحدة هو {$maxPrintLimit}. " .
                    "يرجى تقليص الفترة. مثال: من {$from->format('Y-m-d')} إلى {$suggestTo} (حوالي {$suggestDays} يوم).",
                'meta' => [
                    'count' => $count,
                    'max_limit' => $maxPrintLimit,
                    'suggest_to' => $suggestTo,
                    'suggest_days' => $suggestDays,
                ]
            ], 400);
        }

        $perPage = (int) $request->get('per_page', 20);
        $results = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // اجماليات مبنية على نفس الفلترة
        $totals = (clone $countQuery)->selectRaw("
        SUM(insurance_installment) as total_installment,
        SUM(insurance_tax) as total_tax,
        SUM(insurance_stamp) as total_stamp,
        SUM(insurance_supervision) as total_supervision,
        SUM(insurance_version) as total_version,
        SUM(insurance_total) as total_insurance
    ")->first();

        if ($results->isEmpty()) {
            return response()->json([
                'code' => 2,
                'status' => false,
                'message' => 'لايوجد عمليات اصدار',
            ]);
        }

        // تحذير مبكر (اختياري) لو اقتربنا من السقف
        $warning = null;
        if ($count > $warnPrintLimit) {
            $warning = "تنبيه: عدد النتائج مرتفع ($count). قد تؤثر الطباعة/العرض على الأداء.";
        }

        return response()->json([
            'code' => 1,
            'status' => true,
            'message' => 'تم عرض البيانات',
            'warning' => $warning,
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
            ],
            'totals' => $totals,
        ]);
    }





    public function exportXlsx(Request $request)
    {
        // (اختياري) إعادة استخدام قيد السنة الحالية/القادمة
        $from = \Carbon\Carbon::parse($request->fromdate)->startOfDay();
        $to   = \Carbon\Carbon::parse($request->todate)->endOfDay();

        $currentYearStart = now()->startOfYear();
        $nextYearEnd      = now()->addYear()->endOfYear();

        if ($from->lt($currentYearStart) || $to->gt($nextYearEnd)) {
            return back()->with('error', 'التاريخ يجب أن يكون ضمن السنة الحالية أو السنة القادمة فقط');
        }

        $fromStr = $request->fromdate ? str_replace('-', '', $request->fromdate) : '';
        $toStr   = $request->todate   ? str_replace('-', '', $request->todate)   : '';
        $name    = $fromStr && $toStr ? "sales_all_{$fromStr}_{$toStr}.xlsx" : "sales_all.xlsx";

        return (new IssuingsExport($request))->download($name);
    }

    // public function exportAllPdf(Request $request)
    // {
    //     $filters = $request->only([
    //         'offices_id','companies_id','office_users_id','insurance_name','card_number',
    //         'chassis_number','plate_number','company_users_id','fromdate','todate'
    //     ]);

    //     abort_unless($filters['fromdate'] && $filters['todate'], 422, 'الرجاء اختيار تاريخ البدء والنهاية');

    //     $rows = Issuing::with(['offices.companies','company_users','office_users','cards','cars'])
    //         ->when($filters['companies_id'], fn($q,$v)=>$q->where('companies_id',$v))
    //         ->when($filters['company_users_id'], fn($q,$v)=>$q->where('company_users_id',$v))
    //         ->when($filters['offices_id'], fn($q,$v)=>$q->where('offices_id',$v))
    //         ->when($filters['office_users_id'], fn($q,$v)=>$q->where('office_users_id',$v))
    //         ->when($filters['insurance_name'], fn($q,$v)=>$q->where('insurance_name','like',"%$v%"))
    //         ->when($filters['card_number'], fn($q,$v)=>$q->whereHas('cards', fn($qq)=>$qq->where('card_number','like',"%$v%")))
    //         ->when($filters['plate_number'], fn($q,$v)=>$q->where('plate_number','like',"%$v%"))
    //         ->when($filters['chassis_number'], fn($q,$v)=>$q->where('chassis_number','like',"%$v%"))
    //         ->whereBetween('issuing_date', [$filters['fromdate'],$filters['todate']])
    //         ->orderBy('issuing_date','asc')
    //         ->get();

    //     $totals = [
    //         'total_installment' => round((float)$rows->sum('insurance_installment'), 3),
    //         'total_tax'         => round((float)$rows->sum('insurance_tax'), 3),
    //         'total_stamp'       => round((float)$rows->sum('insurance_stamp'), 3),
    //         'total_supervision' => round((float)$rows->sum('insurance_supervision'), 3),
    //         'total_version'     => round((float)$rows->sum('insurance_version'), 3),
    //         'total_insurance'   => round((float)$rows->sum('insurance_total'), 3),
    //     ];

    //     $meta = [
    //         'from'     => $filters['fromdate'],
    //         'to'       => $filters['todate'],
    //         'username' => auth()->user()->username ?? '',
    //         'today'    => now('Africa/Tripoli')->format('Y-m-d'),
    //     ];

    //     // لاحظ: ما فيش setPaper() ولا getMpdf() هنا
    //     $pdf = \PDF::loadView('dashbord.report.issuing_all_pdf',
    //         compact('rows','totals','meta'),
    //         [],
    //         [
    //             'format'      => 'A4',
    //             'orientation' => 'L',
    //             'default_font'=> 'amiri',
    //             // هنا بنوصل لكائن mPDF نفسه ونضبط RTL وخيارات اللغات
    //             'instanceConfigurator' => function ($mpdf) {
    //                 $mpdf->autoScriptToLang = true;
    //                 $mpdf->autoLangToFont   = true;
    //                 $mpdf->SetDirectionality('rtl'); // وثّقها mPDF هنا. 
    //             },
    //         ]
    //     );

    //     return $pdf->download("sales_all_{$meta['from']}_{$meta['to']}.pdf");
    // }

    //  private function cleanFilters(array $filters): array
    //     {
    //         array_walk($filters, function (&$v) {
    //             if (is_string($v)) $v = trim($v);
    //             if ($v === '' || $v === ' ') $v = null;
    //         });
    //         return $filters;
    //     }



    // private function baseQuery(array $filters)
    //     {
    //         $q = Issuing::with(['offices.companies','company_users','office_users','cards','cars'])
    //             ->when(isset($filters['companies_id'])      && $filters['companies_id']      !== null, fn($qq)=>$qq->where('companies_id',      $filters['companies_id']))
    //             ->when(isset($filters['company_users_id'])  && $filters['company_users_id']  !== null, fn($qq)=>$qq->where('company_users_id',  $filters['company_users_id']))
    //             ->when(isset($filters['offices_id'])        && $filters['offices_id']        !== null, fn($qq)=>$qq->where('offices_id',        $filters['offices_id']))
    //             ->when(isset($filters['office_users_id'])   && $filters['office_users_id']   !== null, fn($qq)=>$qq->where('office_users_id',   $filters['office_users_id']))
    //             ->when(isset($filters['insurance_name'])    && $filters['insurance_name']    !== null, fn($qq)=>$qq->where('insurance_name','like',"%{$filters['insurance_name']}%"))
    //             ->when(isset($filters['card_number'])       && $filters['card_number']       !== null, fn($qq)=>$qq->whereHas('cards', fn($w)=>$w->where('card_number','like',"%{$filters['card_number']}%")))
    //             ->when(isset($filters['plate_number'])      && $filters['plate_number']      !== null, fn($qq)=>$qq->where('plate_number','like',"%{$filters['plate_number']}%"))
    //             ->when(isset($filters['chassis_number'])    && $filters['chassis_number']    !== null, fn($qq)=>$qq->where('chassis_number','like',"%{$filters['chassis_number']}%"));


    //         return $q;
    //     }


    //     public function exportAllPdf(Request $request)
    //     {
    //         $request->validate([
    //             'fromdate' => ['required','date'],
    //             'todate'   => ['required','date','after_or_equal:fromdate'],
    //         ], [], [
    //             'fromdate' => 'تاريخ البدء',
    //             'todate'   => 'تاريخ النهاية',
    //         ]);

    //         $filters = $this->cleanFilters($request->only([
    //             'offices_id','companies_id','office_users_id','insurance_name','card_number',
    //             'chassis_number','plate_number','company_users_id','fromdate','todate'
    //         ]));


    // $from = Carbon::parse($filters['fromdate'])->startOfDay();
    // $to   = Carbon::parse($filters['todate'])->endOfDay();

    // $rows = $this->baseQuery($filters)
    //     ->whereBetween('issuing_date', [$from, $to])
    //     ->orderBy('issuing_date', 'asc')
    //     ->get();





    //      $totals = [
    //     'total_installment' => $rows->sum('insurance_installment'),
    //     'total_tax'         => $rows->sum('insurance_tax'),
    //     'total_stamp'       => $rows->sum('insurance_stamp'),
    //     'total_supervision' => $rows->sum('insurance_supervision'),
    //     'total_version'     => $rows->sum('insurance_version'),
    //     'total_insurance'   => $rows->sum('insurance_total'),
    // ];

    //         $meta = [
    //             'from'     => $filters['fromdate'],
    //             'to'       => $filters['todate'],
    //             'username' => auth()->user()->username ?? '',
    //             'today'    => now('Africa/Tripoli')->format('Y-m-d'),
    //         ];

    // ini_set('pcre.backtrack_limit', '20000000'); // 10M
    // ini_set('pcre.recursion_limit', '10000000');  // 1M
    // set_time_limit(0);
    // ini_set('memory_limit', '768M');
    //         $pdf = \PDF::loadView('dashbord.report.issuing_all_pdf',
    //             compact('rows','totals','meta'),
    //             [],
    //             [
    //                 'format'      => 'A4',
    //                 'orientation' => 'L',
    //                 'default_font'=> 'amiri',
    //                 'instanceConfigurator' => function ($mpdf) {
    //                     $mpdf->autoScriptToLang = true;
    //                     $mpdf->autoLangToFont   = true;
    //                     $mpdf->SetDirectionality('rtl');
    //                 },
    //             ]
    //         );

    //         return $pdf->download("sales_all_{$meta['from']}_{$meta['to']}.pdf");
    //     }




    private function cleanFilters(array $filters): array
    {
        array_walk($filters, function (&$v) {
            if (is_string($v)) $v = trim($v);
            if ($v === '' || $v === ' ') $v = null;
        });
        return $filters;
    }

    private function baseQuery(array $filters)
    {
        $q = Issuing::with(['offices.companies', 'company_users', 'office_users', 'cards', 'cars'])
            ->when(isset($filters['companies_id'])      && $filters['companies_id']      !== null, fn($qq) => $qq->where('companies_id',      $filters['companies_id']))
            ->when(isset($filters['company_users_id'])  && $filters['company_users_id']  !== null, fn($qq) => $qq->where('company_users_id',  $filters['company_users_id']))
            ->when(isset($filters['offices_id'])        && $filters['offices_id']        !== null, fn($qq) => $qq->where('offices_id',        $filters['offices_id']))
            ->when(isset($filters['office_users_id'])   && $filters['office_users_id']   !== null, fn($qq) => $qq->where('office_users_id',   $filters['office_users_id']))
            ->when(isset($filters['insurance_name'])    && $filters['insurance_name']    !== null, fn($qq) => $qq->where('insurance_name', 'like', "%{$filters['insurance_name']}%"))
            ->when(isset($filters['card_number'])       && $filters['card_number']       !== null, fn($qq) => $qq->whereHas('cards', fn($w) => $w->where('card_number', 'like', "%{$filters['card_number']}%")))
            ->when(isset($filters['plate_number'])      && $filters['plate_number']      !== null, fn($qq) => $qq->where('plate_number', 'like', "%{$filters['plate_number']}%"))
            ->when(isset($filters['chassis_number'])    && $filters['chassis_number']    !== null, fn($qq) => $qq->where('chassis_number', 'like', "%{$filters['chassis_number']}%"));

        return $q;
    }


    public function exportAllPdf(Request $request)
    {
        // 1) التحقق الأساسي من التواريخ
        $request->validate([
            'fromdate' => ['required', 'date'],
            'todate'   => ['required', 'date', 'after_or_equal:fromdate'],
        ], [], [
            'fromdate' => 'تاريخ البدء',
            'todate'   => 'تاريخ النهاية',
        ]);

        // 2) تنظيف الفلاتر (حسب طريقتك)
        $filters = $this->cleanFilters($request->only([
            'offices_id',
            'companies_id',
            'office_users_id',
            'insurance_name',
            'card_number',
            'chassis_number',
            'plate_number',
            'company_users_id',
            'fromdate',
            'todate'
        ]));

        $from = Carbon::parse($filters['fromdate'])->startOfDay();
        $to   = Carbon::parse($filters['todate'])->endOfDay();

        // 3) بناء الاستعلام الأساسي مرة واحدة
        $baseQuery = $this->baseQuery($filters)->whereBetween('issuing_date', [$from, $to]);

        // 4) حد أقصى للسجلات
        $MAX_ROWS = 30000;
        $count = (clone $baseQuery)->count();

        if ($count === 0) {
            // لا توجد بيانات ضمن الفترة
            Alert::info('لا توجد بيانات', 'لا توجد سجلات ضمن الفترة المحددة.')->persistent('حسنًا');
            return redirect()->back()->withInput();

            // throw ValidationException::withMessages([
            //     'fromdate' => 'لا توجد بيانات ضمن الفترة المحددة.',
            // ]);
        }

        if ($count > $MAX_ROWS) {
            // أظهر تنبيه للمستخدم ثم امنع التصدير برسالة تحقق
            Alert::warning(
                'البيانات كبيرة',
                "الفترة المختارة تحتوي {$count} سجلًا، والحد الأقصى المسموح به للتصدير هو {$MAX_ROWS}. الرجاء اختيار مدة أقصر."
            )->persistent('حسنًا');
            return redirect()->back()->withInput();

            // throw ValidationException::withMessages([
            //     'todate' => 'الفترة المختارة كبيرة. الرجاء اختيار مدة أقصر.',
            // ]);
        }

        // 5) إجماليات باستخدام SUM() بدون تحميل كل الصفوف للذاكرة
        $totals = (clone $baseQuery)->selectRaw("
            COALESCE(SUM(insurance_installment),0) AS total_installment,
            COALESCE(SUM(insurance_tax),0)         AS total_tax,
            COALESCE(SUM(insurance_stamp),0)       AS total_stamp,
            COALESCE(SUM(insurance_supervision),0) AS total_supervision,
            COALESCE(SUM(insurance_version),0)     AS total_version,
            COALESCE(SUM(insurance_total),0)       AS total_insurance
        ")->first()->toArray();

        // 6) بيانات إضافية للهيدر
        $meta = [
            'from'       => $filters['fromdate'],
            'to'         => $filters['todate'],
            'username'   => auth()->user()->username ?? '',
            'today'      => now('Africa/Tripoli')->format('Y-m-d'),
            'rows_count' => $count,
        ];

        // 7) إعدادات للتعامل مع ذاكرة/وقت التنفيذ
        ini_set('pcre.backtrack_limit', '10000000');
        ini_set('pcre.recursion_limit', '1000000');
        set_time_limit(0);
        ini_set('memory_limit', '768M');

        // 8) مجلد مؤقت لـ mPDF لتقليل الضغط على الذاكرة
        $tempDir = storage_path('app/mpdf-temp');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }

        // 9) تهيئة mPDF
        $mpdf = new Mpdf([
            'format'        => 'A4',
            'orientation'   => 'L',
            'default_font'  => 'amiri',
            'tempDir'       => $tempDir,
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 10,
            'margin_bottom' => 10,
        ]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;
        $mpdf->SetDirectionality('rtl');

        // 10) الهيدر المشترك لكل الصفحات
        $headerHtml = view('dashbord.report.issuing_all_pdf_header', compact('meta', 'totals'))->render();

        // 11) حجم الدفعة لكل صفحة (عدّل بحسب حجم صفك)
        $chunkSize = 500;

        // 12) كتابة الصفحات تباعًا باستخدام chunk على الاستعلام (أوفر ذاكرة)
        $pageIndex = 0;
        (clone $baseQuery)
            ->orderBy('issuing_date', 'asc')
            ->chunk($chunkSize, function ($chunk) use ($mpdf, $headerHtml, $meta, $totals, &$pageIndex) {
                if ($pageIndex > 0) {
                    $mpdf->AddPage();
                }

                // رأس الصفحة + عناوين الأعمدة
                $mpdf->WriteHTML($headerHtml, HTMLParserMode::DEFAULT_MODE);

                // جسم الصفحة: صفوف هذه الدفعة فقط
                $bodyHtml = view('dashbord.report.issuing_all_pdf_rows', [
                    'rows'   => $chunk,
                    'meta'   => $meta,
                    'totals' => $totals,
                ])->render();

                $mpdf->WriteHTML($bodyHtml, HTMLParserMode::HTML_BODY);
                $pageIndex++;
            });

        // 13) إخراج كملف تنزيل
        $filename = "sales_all_{$meta['from']}_{$meta['to']}.pdf";
        return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * ملاحظة: يفترض وجود الدالتين أدناه في مشروعك:
     * - baseQuery($filters): تُرجع Query Builder مفلتر حسب القيم.
     * - cleanFilters(array $data): تنظيف/تطبيع قيم الفلاتر.
     */

    //   public function exportAllPdf(Request $request)
    // {
    //     $request->validate([
    //         'fromdate' => ['required','date'],
    //         'todate'   => ['required','date','after_or_equal:fromdate'],
    //     ], [], [
    //         'fromdate' => 'تاريخ البدء',
    //         'todate'   => 'تاريخ النهاية',
    //     ]);

    //     $filters = $this->cleanFilters($request->only([
    //         'offices_id','companies_id','office_users_id','insurance_name','card_number',
    //         'chassis_number','plate_number','company_users_id','fromdate','todate'
    //     ]));

    //     $from = Carbon::parse($filters['fromdate'])->startOfDay();
    //     $to   = Carbon::parse($filters['todate'])->endOfDay();

    //     $rows = $this->baseQuery($filters)
    //         ->whereBetween('issuing_date', [$from, $to])
    //         ->orderBy('issuing_date', 'asc')
    //         ->get();

    //     $totals = [
    //         'total_installment' => $rows->sum('insurance_installment'),
    //         'total_tax'         => $rows->sum('insurance_tax'),
    //         'total_stamp'       => $rows->sum('insurance_stamp'),
    //         'total_supervision' => $rows->sum('insurance_supervision'),
    //         'total_version'     => $rows->sum('insurance_version'),
    //         'total_insurance'   => $rows->sum('insurance_total'),
    //     ];

    //     $meta = [
    //         'from'     => $filters['fromdate'],
    //         'to'       => $filters['todate'],
    //         'username' => auth()->user()->username ?? '',
    //         'today'    => now('Africa/Tripoli')->format('Y-m-d'),
    //     ];

    //     // Optional limits (chunking is the real fix)
    //     ini_set('pcre.backtrack_limit', '10000000');
    //     ini_set('pcre.recursion_limit', '1000000');
    //     set_time_limit(0);
    //     ini_set('memory_limit', '768M');

    //     // Ensure a temp directory exists for mPDF (reduces memory pressure)
    //     $tempDir = storage_path('app/mpdf-temp');
    //     if (!is_dir($tempDir)) {
    //         @mkdir($tempDir, 0775, true);
    //     }

    //     $mpdf = new Mpdf([
    //         'format'      => 'A4',
    //         'orientation' => 'L',
    //         'default_font'=> 'amiri',
    //         'tempDir'     => $tempDir,
    //         'margin_left'   => 10,
    //         'margin_right'  => 10,
    //         'margin_top'    => 10,
    //         'margin_bottom' => 10,
    //     ]);

    //     $mpdf->autoScriptToLang = true;
    //     $mpdf->autoLangToFont   = true;
    //     $mpdf->SetDirectionality('rtl');

    //     // Render the header/table-head HTML once (used on every page)
    //     $headerHtml = view('dashbord.report.issuing_all_pdf_header', compact('meta','totals'))->render();

    //     // Chunk rows to keep each page’s HTML small; tune 300–800 depending on your row size
    //     $chunkSize = 500;

    //     $i = 0;
    //     foreach ($rows->chunk($chunkSize) as $chunk) {
    //         if ($i > 0) {
    //             $mpdf->AddPage();
    //         }
    //         // Print header & opening table for this page
    //         $mpdf->WriteHTML($headerHtml, HTMLParserMode::DEFAULT_MODE);

    //         // Print only the rows for this page and close the table/html
    //         $bodyHtml = view('dashbord.report.issuing_all_pdf_rows', [
    //             'rows'   => $chunk,
    //             'meta'   => $meta,
    //             'totals' => $totals,
    //         ])->render();

    //         $mpdf->WriteHTML($bodyHtml, HTMLParserMode::HTML_BODY);
    //         $i++;
    //     }

    //     // Return the PDF as a download response
    //     $filename = "sales_all_{$meta['from']}_{$meta['to']}.pdf";
    //     return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN))
    //         ->header('Content-Type', 'application/pdf')
    //         ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    // }

    // public function exportAllPdf(Request $request)
    //     {
    //         $request->validate([
    //             'fromdate' => ['required','date'],
    //             'todate'   => ['required','date','after_or_equal:fromdate'],
    //         ], [], [
    //             'fromdate' => 'تاريخ البدء',
    //             'todate'   => 'تاريخ النهاية',
    //         ]);

    //         $filters = $this->cleanFilters($request->only([
    //             'offices_id','companies_id','office_users_id','insurance_name','card_number',
    //             'chassis_number','plate_number','company_users_id','fromdate','todate'
    //         ]));

    //         $from = Carbon::parse($filters['fromdate'])->startOfDay();
    //         $to   = Carbon::parse($filters['todate'])->endOfDay();

    //         $rows = $this->baseQuery($filters)
    //             ->whereBetween('issuing_date', [$from, $to])
    //             ->orderBy('issuing_date', 'asc')
    //             ->get();

    //         $totals = [
    //             'total_installment' => $rows->sum('insurance_installment'),
    //             'total_tax'         => $rows->sum('insurance_tax'),
    //             'total_stamp'       => $rows->sum('insurance_stamp'),
    //             'total_supervision' => $rows->sum('insurance_supervision'),
    //             'total_version'     => $rows->sum('insurance_version'),
    //             'total_insurance'   => $rows->sum('insurance_total'),
    //         ];

    //         $meta = [
    //             'from'     => $filters['fromdate'],
    //             'to'       => $filters['todate'],
    //             'username' => auth()->user()->username ?? '',
    //             'today'    => now('Africa/Tripoli')->format('Y-m-d'),
    //         ];

    //         // Optional limits (chunking is the real fix)
    //         ini_set('pcre.backtrack_limit', '10000000');
    //         ini_set('pcre.recursion_limit', '1000000');
    //         set_time_limit(0);
    //         ini_set('memory_limit', '768M');

    //         // Ensure a temp directory exists for mPDF (reduces memory pressure)
    //         $tempDir = storage_path('app/mpdf-temp');
    //         if (!is_dir($tempDir)) {
    //             @mkdir($tempDir, 0775, true);
    //         }

    //         $mpdf = new Mpdf([
    //             'format'      => 'A4',
    //             'orientation' => 'L',
    //             'default_font'=> 'amiri',
    //             'tempDir'     => $tempDir,
    //             'margin_left'   => 10,
    //             'margin_right'  => 10,
    //             'margin_top'    => 10,
    //             'margin_bottom' => 10,
    //         ]);

    //         $mpdf->autoScriptToLang = true;
    //         $mpdf->autoLangToFont   = true;
    //         $mpdf->SetDirectionality('rtl');

    //         // Render the header/table-head HTML once (used on every page)
    //         $headerHtml = view('dashbord.report.issuing_all_pdf_header', compact('meta','totals'))->render();

    //         // Chunk rows to keep each page’s HTML small; tune 300–800 depending on your row size
    //         $chunkSize = 500;

    //         $i = 0;
    //         foreach ($rows->chunk($chunkSize) as $chunk) {
    //             if ($i > 0) {
    //                 $mpdf->AddPage();
    //             }
    //             // Print header & opening table for this page
    //             $mpdf->WriteHTML($headerHtml, HTMLParserMode::DEFAULT_MODE);

    //             // Print only the rows for this page and close the table/html
    //             $bodyHtml = view('dashbord.report.issuing_all_pdf_rows', [
    //                 'rows'   => $chunk,
    //                 'meta'   => $meta,
    //                 'totals' => $totals,
    //             ])->render();

    //             $mpdf->WriteHTML($bodyHtml, HTMLParserMode::HTML_BODY);
    //             $i++;
    //         }

    //         // Return the PDF as a download response
    //         $filename = "sales_all_{$meta['from']}_{$meta['to']}.pdf";
    //         return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN))
    //             ->header('Content-Type', 'application/pdf')
    //             ->header('Content-Disposition', 'attachment
    // }
    // }}
    public function searchpdf(Request $request)
    {
        $messages = [
            'fromdate.required' => "اختر الفترة من",
            'todate.required'   => "اختر الفترة إلى",
        ];

        $this->validate($request, [
            'fromdate' => 'required',
            'todate'   => 'required',
        ], $messages);

        $from = Carbon::parse($request->fromdate)->startOfDay();
        $to   = Carbon::parse($request->todate)->endOfDay();

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
        ])->select('*');

        if (!empty($request->companies_id)) {
            $issuing->where(function ($query) use ($request) {
                $query->where('companies_id', $request->companies_id)
                    ->orWhereHas('offices', function ($subQuery) use ($request) {
                        $subQuery->where('companies_id', $request->companies_id);
                    });
            });
        }
        if (!empty($request->offices_id))        $issuing->where('offices_id', $request->offices_id);
        if (!empty($request->company_users_id))  $issuing->where('company_users_id', $request->company_users_id);
        if (!empty($request->office_users_id))   $issuing->where('office_users_id', $request->office_users_id);
        if (!empty($request->card_number)) {
            $issuing->whereHas('cards', fn($q) => $q->where('card_number', $request->card_number));
        }
        if (!empty($request->insurance_name))    $issuing->where('insurance_name', 'like', '%' . $request->insurance_name . '%');
        if (!empty($request->plate_number))      $issuing->where('plate_number', $request->plate_number);

        $issuing->whereBetween('issuing_date', [$from, $to]);

        // ====== حد منطقي للطباعة عبر المتصفح ======
        $maxPrintLimit  = (int) env('PRINT_MAX_LIMIT', 50000);   // سقف صارم
        $warnPrintLimit = (int) env('PRINT_WARN_LIMIT', 50000);  // تحذير مبكر

        $count = (clone $issuing)->count();
        if ($count > $maxPrintLimit) {
            $days = max(1, $from->diffInDays($to) + 1);
            $perDay = $count / $days;
            $suggestDays = max(1, (int) floor($maxPrintLimit / max(1, $perDay)));
            $suggestTo = (clone $from)->addDays($suggestDays - 1)->format('Y-m-d');

            Alert::warning("عدد النتائج كبير جداً ($count). الحد الأقصى للطباعة دفعة واحدة هو {$maxPrintLimit} سجل. " .
                "الرجاء تقليص الفترة. مثال: من {$from->format('Y-m-d')} إلى {$suggestTo} (حوالي {$suggestDays} يوم).");
            return redirect()->back()->withInput();
        }

        $issuing = $issuing->orderBy('created_at', 'DESC')->get();

        if ($issuing->isEmpty()) {
            Alert::warning("لايوجد بطاقات");
            return redirect()->back()->withInput();
        }

        $total = $issuing->sum('insurance_total');

        // تحذير مبكر اختياري
        if ($count > $warnPrintLimit) {
            Alert::info("تنبيه", "عدد السجلات مرتفع ($count). قد تستغرق الطباعة وقتًا أطول.");

            return redirect()->back()->withInput();
        }

        return view('dashbord.report.result', [
            'fromdate' => $request->fromdate,
            'todate'   => $request->todate,
            'issuing'  => $issuing,
            'total'    => $total,
        ]);
    }

    // public function searchpdf(Request $request)
    // {
    //     $messages = [
    //         'fromdate.required' => "اختر الفترة من  ",
    //         'todate.required' => "اختر الفترة الي  ",
    //     ];

    //     $this->validate($request, [
    //         'fromdate' => 'required',
    //         'todate' => 'required',
    //     ], $messages);

    //     $from = Carbon::parse($request->fromdate)->format('Y-m-d');
    //     $to = Carbon::parse($request->todate)->format('Y-m-d');

    //     $issuing = Issuing::with([
    //         'cards',
    //         'vehicle_nationalities',
    //         'companies',
    //         'offices',
    //         'offices.companies',
    //         'company_users',
    //         'office_users',
    //         'users',
    //         'cars',
    //         'countries'
    //     ])->select('*');

    //     if (!empty($request->companies_id)) {
    //         $issuing->where(function ($query) use ($request) {
    //             $query->where('companies_id', $request->companies_id)
    //                   ->orWhereHas('offices', function ($subQuery) use ($request) {
    //                       $subQuery->where('companies_id', $request->companies_id);
    //                   });
    //         });
    //     }

    //     if (!empty($request->offices_id)) {
    //         $issuing->where('offices_id', $request->offices_id);
    //     }

    //     if (!empty($request->company_users_id)) {
    //         $issuing->where('company_users_id', $request->company_users_id);
    //     }

    //     if (!empty($request->office_users_id)) {
    //         $issuing->where('office_users_id', $request->office_users_id);
    //     }

    //     if (!empty($request->card_number)) {
    //         $issuing->whereHas('cards', function ($query) use ($request) {
    //             $query->where('card_number', $request->card_number);
    //         });
    //     }

    //     if (!empty($request->insurance_name)) {
    //         $issuing->where('insurance_name', 'like', '%' . $request->insurance_name . '%');
    //     }

    //     if (!empty($request->plate_number)) {
    //         $issuing->where('plate_number', $request->plate_number);
    //     }

    //     if (!empty($from) && !empty($to)) {
    //         $issuing->whereBetween('issuing_date', [$from . " 00:00:00", $to . " 23:59:59"]);
    //     }

    //     $issuing = $issuing->orderBy('created_at', 'DESC')->get();

    //     if ($issuing->isEmpty()) {
    //         Alert::warning("لايوجد بطاقات");
    //         return redirect()->back();
    //     }

    //     $total = $issuing->sum('insurance_total');
    // // $pdf = PDF::loadView('dashbord.report.result', [
    // //     'issuing' => $issuing,
    // //     'fromdate' => $request->fromdate,
    // //     'todate' => $request->todate,
    // //     'total' => $total
    // // ]);

    // // return $pdf->download('تقرير_التأمين.pdf');
    //     return view('dashbord.report.result', [
    //         'fromdate' => $request->fromdate,
    //         'todate' => $request->todate,
    //         'issuing' => $issuing,
    //         'total' => $total,
    //     ]);
    // }


    // public function searchpdf00(Request $request)
    // {


    //     $messages = [
    //         'fromdate.required' => "اختر الفترة من  ",
    //         'todate.required' => "اختر الفترة الي  ",

    //     ];
    //     $this->validate($request, [
    //         'fromdate' => ['required'],
    //         'todate' => ['required'],

    //     ], $messages);
    //     // Parse and format dates
    //     $from = Carbon::parse($request->fromdate)->format('Y-m-d');
    //     $to = Carbon::parse($request->todate)->format('Y-m-d');

    //     // Initialize query with eager loading
    //     $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');



    //     if (!empty($request->companies_id)) {

    //         $issuing
    //         ->where(function ($query) use ($request) {
    //             $query->where('companies_id', $request->companies_id)
    //                   ->orWhereHas('offices', function ($subQuery) use ($request) {
    //                       $subQuery->where('companies_id', $request->companies_id);
    //                   });
    //         });
    //     }


    //     if (!empty($request->offices_id)) {
    //         $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');

    //         $issuing->where('offices_id', $request->offices_id);
    //     }

    //     if (!empty($request->company_users_id)) {
    //         $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');
    //         $issuing->orWhere('company_users_id', $request->company_users_id);
    //     }

    //     if (!empty($request->office_users_id)) {

    //         $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');

    //         $issuing->where('office_users_id', $request->office_users_id);
    //     }
    //     // Apply search criteria
    //     if (!empty($request->card_number)) {
    //         $issuing->whereHas('cards', function ($query) use ($request) {
    //             $query->where('card_number', $request->card_number);
    //         });
    //     }

    //     if (!empty($request->insurance_name)) {

    //         $issuing->where('insurance_name', 'like', $request->insurance_name);
    //     }

    //     if (!empty($request->plate_number)) {
    //         $issuing->where('plate_number', $request->plate_number);
    //     }

    //     // if (!empty($request->fromdate) && !empty($request->todate)) {
    //     //     $issuing = $issuing->whereBetween('issuing_date', [$from . " 00:00:00", $to . " 23:59:59"]);
    //     // }

    //     if ($request->fromdate && $request->todate) {

    //         // When from and to are different, use whereBetween
    //         $issuing
    //             ->whereBetween('issuing_date', [$from. " 00:00:00", $to . " 23:59:59"]);
    //     }

    //     // Apply date range filtering (if provided)


    //     // Order and retrieve results
    //     $issuing = $issuing->orderBy('created_at', 'DESC')->get();
    //     // Check if results are empty
    //     if ($issuing->isEmpty()) {
    //         Alert::warning(" لايوجد بطاقات");

    //         return redirect()->back();
    //     } else {


    //         $total = $issuing->sum('insurance_total');

    //         return view('dashbord.report.result')
    //         ->with('fromdate', $request->fromdate)
    //         ->with('todate', $request->todate)

    //             ->with('issuing', $issuing)
    //             ->with('total', $total);
    //     }
    // }


    public function searchpdfsummery(Request $request)
    {
        $from = Carbon::parse($request->fromdate)->format('Y-m-d');
        $to = Carbon::parse($request->todate)->format('Y-m-d');

        // Initialize query with eager loading
        $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');



        if (!empty($request->companies_id)) {
            $issuing->where('companies_id', $request->companies_id)

                ->orWhereHas('offices', function ($query) use ($request) {
                    $query->where('companies_id', $request->companies_id);
                });
        }


        if (!empty($request->offices_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');

            $issuing->where('offices_id', $request->offices_id);
        }

        if (!empty($request->company_users_id)) {
            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');
            $issuing->orWhere('company_users_id', $request->company_users_id);
        }

        if (!empty($request->office_users_id)) {

            $issuing = Issuing::with(['cards', 'vehicle_nationalities', 'companies', 'offices', 'offices.companies', 'company_users', 'cards', 'office_users', 'users', 'cars', 'countries'])->select('*');

            $issuing->where('office_users_id', $request->office_users_id);
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
            Alert::warning(" لايوجد بطاقات");

            return redirect()->back();
        } else {


            $total = $issuing->sum('insurance_total');

            return view('dashbord.report.resultsummary')
                ->with('fromdate', $request->fromdate)
                ->with('todate', $request->todate)

                ->with('issuing', $issuing)
                ->with('total', $total);
        }
    }

    public function offices($id)
    {
        $Offices = Office::where('companies_id', $id)->get();

        if (!$Offices) {
            return response()->json(0);
        }

        return response()->json($Offices);
    }


    public function officesuser($id)
    {
        $Officeuser = OfficeUser::where('offices_id', $id)->get();

        if (!$Officeuser) {
            return response()->json(0);
        }

        return response()->json($Officeuser);
    }


    public function companyUser($id)
    {
        $companyUser = CompanyUser::where('companies_id', $id)->get();

        if (!$companyUser) {
            return response()->json(0);
        }

        return response()->json($companyUser);
    }




    public function indexSalesCount(Request $request)
    {
        // تحقق أساسي
        $request->validate([
            'companies_id' => ['nullable', 'integer', 'exists:companies,id'],
            'fromdate'     => ['nullable', 'date'],
            'todate'       => ['nullable', 'date', 'after_or_equal:fromdate'],
        ]);

        // مصفاة القيم المختارة لنعرضها في الواجهة
        $filters = [
            'companies_id' => $request->input('companies_id'),
            'fromdate'     => $request->input('fromdate'),
            'todate'       => $request->input('todate'),
        ];

        // ابني الاستعلام مع الفلاتر الشرطية
        $insurance_totalt = DB::table('requests')

            ->join('companies', 'companies.id', '=', 'requests.companies_id')
            ->where('requests.request_statuses_id', '=', 2)

            // فلتر الشركة (إن وُجد)
            ->when($filters['companies_id'], function ($q) use ($filters) {
                $q->where('companies.id', $filters['companies_id']);
            })
            // فلتر التاريخ (إن وُجد)
            ->when($filters['fromdate'] && $filters['todate'], function ($q) use ($filters) {
                // غيّر الحقل إلى الحقل الصحيح في جدول issuings (مثلاً issue_date أو created_at)
                $q->whereBetween(DB::raw('DATE(requests.uploded_datetime)'), [
                    $filters['fromdate'],
                    $filters['todate']
                ]);
            })
            ->select(
                'companies.id as company_id',
                'companies.name as companies_name',
                DB::raw('sum(requests.cards_number) AS counts')
            )
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc('counts')
            ->get();

        // إجمالي الكل
        $insurance_total = $insurance_totalt->sum('counts');

        // قائمة الشركات للاختيار
        $companies = DB::table('companies')->select('id', 'name')->orderBy('name')->get();

        return view('dashbord.report.salescountindex', compact(
            'insurance_total',
            'insurance_totalt',
            'companies',
            'filters'
        ));
    }



    public function indexsalespdf()
    {
        $user = Auth::user();

        // Get total per company (grouped by ID)
        $insurance_totalt = DB::table('cards')
            ->select(
                'companies.id as company_id',
                'companies.name AS companies_name',
                DB::raw('SUM(issuings.insurance_total) AS total_insurance')
            )
            ->join('companies', 'companies.id', '=', 'cards.companies_id')
            ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id')
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc(DB::raw('total_insurance'))
            ->get();

        // Overall total (summed from the above result, more efficient)
        $insurance_total = $insurance_totalt->sum('total_insurance');

        return view('dashbord.report.salesindexpdf', compact('user', 'insurance_total', 'insurance_totalt'));
    }



    public function indexsales()
    {
        // Get total insurance per company
        $insurance_totalt = DB::table('cards')
            ->select(
                'companies.id as company_id',
                'companies.name AS companies_name',
                DB::raw('SUM(issuings.insurance_total) AS total_insurance')
            )
            ->join('companies', 'companies.id', '=', 'cards.companies_id')
            ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id')
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc(DB::raw('total_insurance'))
            ->get();

        // Overall total insurance (using same table)
        $insurance_total = $insurance_totalt->sum('total_insurance');

        return view('dashbord.report.salesindex', compact('insurance_total', 'insurance_totalt'));
    }




    //  public function indexstock()
    // {
    //     // Use subquery to pre-aggregate total count of active cards (only once)
    //     $cardcount = Card::count();
    //     $cardcountactive = Card::whereNotNull('companies_id')->count();

    //     // Build aggregated query efficiently
    //     $cardsStock = DB::table('cards')
    //         ->select(
    //             'companies.id AS company_id',
    //             'companies.name AS companies_name',
    //             DB::raw('COUNT(DISTINCT cards.id) AS total_cards'),
    //             DB::raw("SUM(CASE WHEN cards.cardstautes_id = 1 THEN 1 ELSE 0 END) AS active_cards"),
    //             DB::raw("SUM(CASE WHEN cards.cardstautes_id = 2 THEN 1 ELSE 0 END) AS sold"),
    //             DB::raw("SUM(CASE WHEN cards.cardstautes_id = 3 THEN 1 ELSE 0 END) AS cancelled"),
    //             DB::raw("SUM(issuings.insurance_total) AS total_insurance"),
    //             DB::raw("ROUND(COUNT(cards.id) / {$cardcountactive} * 100, 2) AS percentage")
    //         )
    //         ->join('companies', 'companies.id', '=', 'cards.companies_id')
    //         ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id')
    //         ->groupBy('companies.id', 'companies.name')
    //         ->get();

    //     // Pre-count cancelled cards (to avoid summing in PHP if not necessary)
    //     $cardscancelled = Card::where('cardstautes_id', 3)->count();

    //     // Reduce with totals
    //     $totals = [
    //         'total_cards'     => 0,
    //         'sold'            => 0,
    //         'active_cards'    => 0,
    //         'cancelled'       => $cardscancelled,
    //         'total_insurance' => 0,
    //         'percentage'      => 0,
    //     ];

    //     foreach ($cardsStock as $item) {
    //         $totals['total_cards']     += (int)$item->total_cards;
    //         $totals['sold']            += (int)$item->sold;
    //         $totals['active_cards']    += (int)$item->active_cards;
    //         $totals['total_insurance'] += (float)$item->total_insurance;
    //         $totals['percentage']      += (float)$item->percentage;
    //     }

    //     return view('dashbord.report.stockindex', [
    //         'cardsStock' => $cardsStock,
    //         'totals'     => $totals,
    //         'cardcount'  => $cardcount,
    //     ]);
    // }

    public function indexstock()
    {
        // الحصول على عدد البطاقات الكلي وعدد البطاقات المخصصة لشركات (active)
        $cardcount = Card::distinct('id')->count();
        $cardcountActive = Card::whereNotNull('companies_id')->distinct('id')->count();
        $cardCancelled = Card::where('cardstautes_id', 3)->distinct('id')->count();

        // الاستعلام الأساسي: تجميع حسب الشركة
        $cardsStock = DB::table('cards')
            ->select(
                'companies.id AS company_id',
                'companies.name AS companies_name',
                DB::raw('COUNT(DISTINCT cards.id) AS total_cards'),
                DB::raw("COUNT(DISTINCT IF(cards.cardstautes_id = 1, cards.id, NULL)) AS active_cards"),
                DB::raw("COUNT(DISTINCT IF(cards.cardstautes_id = 2, cards.id, NULL)) AS sold"),
                DB::raw("COUNT(DISTINCT IF(cards.cardstautes_id = 3, cards.id, NULL)) AS cancelled"),
                DB::raw("SUM(issuings.insurance_total) AS total_insurance")
            )
            ->join('companies', 'companies.id', '=', 'cards.companies_id')
            ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id')
            ->groupBy('companies.id', 'companies.name')
            ->get();

        // جمع القيم الكلية
        $totals = [
            'total_cards'     => 0,
            'sold'            => 0,
            'active_cards'    => 0,
            'cancelled'       => $cardCancelled,
            'total_insurance' => 0,
            'percentage'      => 0,
        ];

        foreach ($cardsStock as $item) {
            $totals['total_cards']     += (int) $item->total_cards;
            $totals['sold']            += (int) $item->sold;
            $totals['active_cards']    += (int) $item->active_cards;
            $totals['total_insurance'] += (float) $item->total_insurance;

            // احسب النسبة لكل شركة هنا حسب عدد البطاقات النشطة العامة
            $item->percentage = $cardcountActive > 0
                ? round(($item->total_cards / $cardcountActive) * 100, 2)
                : 0;

            $totals['percentage'] += $item->percentage;
        }

        return view('dashbord.report.stockindex', [
            'cardsStock' => $cardsStock,
            'totals'     => $totals,
            'cardcount'  => $cardcount,
        ]);
    }




    public function indexcanelcardpdf(Request $request)
    {
        $from = $request->filled('fromdate') ? Carbon::parse($request->fromdate)->startOfDay() : null;
        $to = $request->filled('todate') ? Carbon::parse($request->todate)->endOfDay() : null;

        // Initialize query with required relationships
        $cardQuery = Card::with(['users', 'companies', 'cardstautes', 'requests', 'issuing'])
            ->where('cardstautes_id', 3); // Only cancelled cards

        // Filter by request number
        if ($request->filled('request_number')) {
            $cardQuery->whereHas('requests', function ($q) use ($request) {
                $q->where('request_number', $request->request_number);
            });
        }

        // Filter by company
        if ($request->companies_id === "0") {
            $cardQuery->whereNull('companies_id');
        } elseif (!empty($request->companies_id)) {
            $cardQuery->where('companies_id', $request->companies_id);
        }

        // Filter by card number
        if ($request->filled('card_number')) {
            $cardQuery->where('card_number', $request->card_number);
        }

        // Filter by date range
        if ($from && $to) {
            $cardQuery->whereBetween('card_delete_date', [$from, $to]);
        }

        // Final result with ordering
        $cards = $cardQuery->orderBy('card_delete_date', 'asc')->get();

        $user = auth()->user(); // currently logged-in user

        return view('dashbord.report.searchcancepdf', [
            'cards' => $cards,
            'filters' => $request->all(),
            'user' => $user
        ]);
    }






    public function indexcanelcard()
    {
        $Company = Company::select('id', 'name')->get();

        return view('dashbord.report.searchcance', compact('Company'));
    }


    public function searchcacel(Request $request)
    {
        $request->validate([
            'fromdate' => 'nullable|date',
            'todate' => 'nullable|date|after_or_equal:fromdate',
            'companies_id' => 'nullable',
            'request_number' => 'nullable|string',
            'card_number' => 'nullable|string',
        ]);

        $from = $request->filled('fromdate') ? Carbon::parse($request->fromdate)->startOfDay() : null;
        $to = $request->filled('todate') ? Carbon::parse($request->todate)->endOfDay() : null;

        $cards = Card::with(['users', 'companies:id,name', 'cardstautes:id,name', 'requests:id,request_number', 'issuing:id,cards_id,issuing_date'])
            ->where('cardstautes_id', 3)
            ->when(
                $request->request_number,
                fn($q) =>
                $q->whereHas(
                    'requests',
                    fn($sub) =>
                    $sub->where('request_number', $request->request_number)
                )
            )
            ->when(
                $request->card_number,
                fn($q) =>
                $q->where('card_number', $request->card_number)
            )
            ->when(
                $request->companies_id !== null && $request->companies_id !== '',
                function ($q) use ($request) {
                    if ($request->companies_id === "0") {
                        $q->whereNull('companies_id');
                    } else {
                        $q->where('companies_id', $request->companies_id);
                    }
                }
            )
            ->when(
                $from && $to,
                fn($q) =>
                $q->whereBetween('card_delete_date', [$from, $to])
            )
            ->get();

        if ($cards->isEmpty()) {
            return response()->json([
                'code' => 2,
                'status' => false,
                'message' => 'لا يوجد بطاقات.',
            ]);
        }

        return response()->json([
            'code' => 1,
            'status' => true,
            'message' => 'تم جلب البطاقات الملغية.',
            'data' => $cards
        ]);
    }






    public function indexRequestCompanyPdf(Request $request)
    {
        $companies = Company::all();
        $request_statuses = RequestStatus::all();
        $user = auth()->user(); // المستخدم الحالي

        $query = Requests::with(['companies', 'company_users', 'users', 'request_statuses']);

        if ($request->filled('request_number')) {
            $query->where('request_number', $request->request_number);
        }

        if ($request->filled('cards_number')) {
            $query->where('cards_number', $request->cards_number);
        }

        if (!empty($request->companies_id) && $request->companies_id !== "0") {
            $query->where('companies_id', $request->companies_id);
        }

        if (!empty($request->request_statuses_id) && $request->request_statuses_id !== "0") {
            $query->where('request_statuses_id', $request->request_statuses_id);
        }

        if ($request->filled('fromdate') && $request->filled('todate')) {
            $from = Carbon::parse($request->fromdate)->startOfDay();
            $to = Carbon::parse($request->todate)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $requests = $query->whereNotNull('companies_id')
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('dashbord.report.requestcompanypdf', [
            'requests' => $requests,
            'request_statuses' => $request_statuses,
            'Company' => $companies,
            'searchParams' => $request->all(),
            'user' => $user
        ]);
    }





    public function indexreqiestcompany()
    {
        $companies = Company::all();
        $requestStatuses = RequestStatus::all();

        return view('dashbord.report.requestcompanysearch', [
            'request_statuses' => $requestStatuses,
            'Company' => $companies
        ]);
    }


    public function searchrequest(Request $request)
    {
        $query = Requests::with(['companies', 'company_users', 'users', 'request_statuses'])
            ->whereNotNull('companies_id');

        if ($request->filled('request_number')) {
            $query->where('request_number', $request->request_number);
        }

        if ($request->filled('cards_number')) {
            $query->where('cards_number', $request->cards_number);
        }

        if (!empty($request->companies_id) && $request->companies_id !== "0") {
            $query->where('companies_id', $request->companies_id);
        }

        if (!empty($request->request_statuses_id) && $request->request_statuses_id !== "0") {
            $query->where('request_statuses_id', $request->request_statuses_id);
        }

        if ($request->filled('fromdate') && $request->filled('todate')) {
            $from = Carbon::parse($request->fromdate)->startOfDay();
            $to = Carbon::parse($request->todate)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $requests = $query->orderBy('created_at', 'DESC')->get();

        if ($requests->isEmpty()) {
            return response()->json([
                'code' => 2,
                'status' => false,
                'message' => 'لايوجد طلبات',
            ]);
        }

        return response()->json([
            'code' => 1,
            'status' => true,
            'message' => 'يتم عرض الطلبات ',
            'data' => $requests
        ]);
    }




    public function viewdocument($card_id)
    {

        try {
            $lifos = new LifoApiService();


            $cards = Card::find($card_id);
            $card_number = $cards->card_number;
            $users = config('apilifo', 'user_api_name');
            $userid = $users["user_api_name"];
            $userpass = $users["user_api_password"];

            $atuh = $lifos->getAuth($userid, $userpass);
            $body = $atuh->getBody();

            $responsee = json_decode($body->getContents());
            if ($responsee->status == 2000) {
                $key = $responsee->data;

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $key
                ];
                $bodey = array(
                    "RQ_OC_NO" => "$card_number",
                    "RQ_USER_ID" => "$userid"
                );


                $cancel = $lifos->printcard($headers, $bodey);
                $bodyca = $cancel->getBody();
                $responseca = json_decode($bodyca->getContents());

                $code = $responseca->status;
                // dd( $responseca);
                if ($code == 1336) {

                    // $ex = base64_decode(explode("base64,", $responseca->data)[1]);
                    $ex = base64_decode(explode("base64,", $responseca->data)[1]);

                    // Generate a unique identifier for the PDF
                    // $pdfId = uniqid('pdf_');
                    // $ex = base64_decode(explode("base64,", $responseca->data)[1]);

                    // header("Content-Type" , "application/pdf") ;
                    header("Cache-Control: maxage=1");
                    header("Pragma: public");
                    header("Content-type: application/pdf");
                    header("Content-Disposition: inline; filename=12");
                    header("Content-Description: PHP Generated Data");
                    header("Content-Transfer-Encoding: binary");
                    echo $ex;

                    // Create a JavaScript script to open a new tab and load the PDF
                    //             echo '<script>
                    //     window.open("data:application/pdf;base64,' . base64_encode($ex) . '");
                    // </script>';
                } else if ($code == 8051) {
                    Alert::warning("معلمات الطلب غير مكتملة");
                    return redirect()->back();
                } else if ($code == 8072) {
                    Alert::warning("he policy corresponding to the entered OC number is not in approved state.");
                    return redirect()->back();
                } else if ($code == 8052) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    return redirect()->back();
                } else if ($code == 8069) {

                    Alert::warning("عنوان URL غير موجود");
                    return redirect()->back();
                } else if ($code == 8070) {

                    Alert::warning("رقم الشهادة غير موجود أو غير موجود لدى المستخدم الذي قام بتسجيل الدخول.");
                    return redirect()->back();
                } else if ($code == 8053) {
                    Alert::warning("غير قادر على تقديم الطلب");
                    return redirect()->back();
                } else if ($code == 2501) {
                    Alert::warning("لم يتم العثور على المستخدم في النظام");
                    return redirect()->back();
                } else if ($code == 2502) {
                    Alert::warning("المستخدم غير نشط");
                    return redirect()->back();
                }
            } else if ($responsee->status == 2001) {
                Alert::warning("فشلت مصادقة المستخدم");

                return redirect()->back();
            } else if ($responsee->status == 8051) {
                Alert::warning("معلمات الطلب غير مكتملة");

                return redirect()->back();
            } else if ($responsee->status == 8052) {

                Alert::warning("غير قادر على بدء الطلب");

                return redirect()->back();
            }
        } catch (\Exception $e) {


            Alert::warning($e . "فشل عرض وثيقة");

            return redirect()->back();
        }
    }


    public function stockpdf()
    {
        // Count total cards and active cards (only once)
        $cardcount = Card::count();
        $cardcountactive = Card::whereNotNull('companies_id')->count();

        // Prevent division by zero
        $percentageBase = $cardcountactive > 0 ? $cardcountactive : 1;

        // Efficient aggregate query
        $cardsStock = DB::table('cards')
            ->select(
                'companies.id AS company_id',
                'companies.name AS companies_name',
                DB::raw('COUNT(cards.id) AS total_cards'),
                DB::raw("SUM(CASE WHEN cards.cardstautes_id = 1 THEN 1 ELSE 0 END) AS active_cards"),
                DB::raw("SUM(CASE WHEN cards.cardstautes_id = 2 THEN 1 ELSE 0 END) AS sold"),
                DB::raw("SUM(CASE WHEN cards.cardstautes_id = 3 THEN 1 ELSE 0 END) AS cancelled"),
                DB::raw('SUM(issuings.insurance_total) AS total_insurance'),
                DB::raw("ROUND(COUNT(cards.id) / {$percentageBase} * 100, 2) AS percentage")
            )
            ->join('companies', 'companies.id', '=', 'cards.companies_id')
            ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id')
            ->groupBy('companies.id', 'companies.name')
            ->get();

        // Calculate totals
        $totals = [
            'total_cards'     => 0,
            'sold'            => 0,
            'active_cards'    => 0,
            'cancelled'       => 0,
            'total_insurance' => 0.0,
            'percentage'      => 0,
        ];

        foreach ($cardsStock as $item) {
            $totals['total_cards']     += (int)$item->total_cards;
            $totals['sold']            += (int)$item->sold;
            $totals['active_cards']    += (int)$item->active_cards;
            $totals['cancelled']       += (int)$item->cancelled;
            $totals['total_insurance'] += (float)$item->total_insurance;
            $totals['percentage']      += (float)$item->percentage;
        }

        return view('dashbord.report.stock', [
            'cardsStock' => $cardsStock,
            'totals'     => $totals,
            'cardcount'  => $cardcount
        ]);
    }


    ///officeStats

    public function officeStats()
    {
        // استعلام إحصائيات الإصدارات لكل مكتب
        $officeStats = DB::table('issuings')
            ->join('offices', 'issuings.offices_id', '=', 'offices.id')
            ->select('offices.name', DB::raw('COUNT(issuings.id) as total_issuings'))
            ->groupBy('offices.id', 'offices.name')
            ->get();

        // استخراج الأسماء والأعداد في مصفوفات منفصلة للعرض في الرسم البياني
        $officeLabels = $officeStats->pluck('name');
        $officeData = $officeStats->pluck('total_issuings');

        return view('dashbord.report.office_stats', compact('officeLabels', 'officeData'));
    }



    public function countryissuingsstats()
    {
        // جلب الإحصائيات: عدد الإصدارات لكل دولة
        $countryStats = DB::table('issuings')
            ->join('countries', 'issuings.countries_id', '=', 'countries.id')
            ->select('countries.name as country_name', DB::raw('COUNT(issuings.id) as total_issuings'))
            ->groupBy('countries.id', 'countries.name')
            ->orderByDesc('total_issuings')
            ->get();

        // تجهيز البيانات للعرض في الرسم البياني
        $countryLabels = $countryStats->pluck('country_name');
        $countryData = $countryStats->pluck('total_issuings');

        // تمرير البيانات إلى صفحة العرض
        return view('dashbord.report.country_issuings_stats', compact('countryLabels', 'countryData'));
    }



    // إحصائية: عدد إصدارات كل شركة، مع احتساب جميع الإصدارات من المكاتب التابعة لها
    public function totalCompanyIssuingStats()
    {
        $companies = DB::table('issuings')
            ->join('offices', 'issuings.offices_id', '=', 'offices.id')
            ->join('companies', 'offices.companies_id', '=', 'companies.id')
            ->select('companies.name as company_name', DB::raw('COUNT(issuings.id) as total_issuings'))
            ->groupBy('companies.id', 'companies.name')
            ->orderBy('total_issuings', 'desc')
            ->get();

        $companyLabels = $companies->pluck('company_name');
        $companyData = $companies->pluck('total_issuings');

        return view('dashbord.report.total_company_issuings', compact('companyLabels', 'companyData'));
    }


    // مقارنة إصدارات مستخدمي المكاتب

    public function officeUsersStats()
    {
        $users = DB::table('issuings')
            ->join('office_users', 'issuings.office_users_id', '=', 'office_users.id')
            ->select('office_users.username', DB::raw('COUNT(issuings.id) as total'))
            ->groupBy('office_users.id', 'office_users.username')
            ->orderByDesc('total')
            ->get();

        $labels = $users->pluck('username');
        $data = $users->pluck('total');

        return view('dashbord.report.office_users_stats', compact('labels', 'data'));
    }




    public function officeSummaryReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $action = $request->input('action'); // 'view' أو 'print'

        $query = DB::table('issuings')
            ->join('offices', 'issuings.offices_id', '=', 'offices.id')
            ->join('cards', 'issuings.cards_id', '=', 'cards.id')
            ->select(
                'offices.name as office_name',
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) as issued_count'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) as canceled_count'),
                DB::raw('SUM(issuings.insurance_installment) as net_premium'),
                DB::raw('SUM(issuings.insurance_tax) as tax'),
                DB::raw('SUM(issuings.insurance_stamp) as stamp'),
                DB::raw('SUM(issuings.insurance_supervision) as supervision'),
                DB::raw('SUM(issuings.insurance_version) as issuing_fee'),
                DB::raw('SUM(issuings.insurance_total) as total')
            )
            ->groupBy('offices.id', 'offices.name')
            ->orderByDesc('total');

        if ($startDate && $endDate) {
            $query->whereBetween('issuings.created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        $data = $query->get();

        if ($action === 'print') {
            // عرض نسخة للطباعة في صفحة جديدة
            return view('dashbord.report.office_summary', compact('data', 'startDate', 'endDate'));
        }

        // عرض التقرير العادي
        return view('dashbord.report.office_summarypdf', compact('data', 'startDate', 'endDate'));
    }




    public function companySummaryReportpdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $companyId = $request->input('company_id');
        $user = auth()->user();

        if (!$startDate && !$endDate && !$companyId) {
            return view('dashbord.report.company_summary', [
                'data' => [],
                'companies' => DB::table('companies')->get(),
                'startDate' => null,
                'endDate' => null,
                'companyId' => null,
                'user' => $user
            ]);
        }

        $query = DB::table('issuings')
            ->join('cards', 'issuings.cards_id', '=', 'cards.id')
            ->join('offices', 'issuings.offices_id', '=', 'offices.id')
            ->join('companies', 'offices.companies_id', '=', 'companies.id')
            ->select(
                'companies.name as company_name',
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) as issued_count'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) as canceled_count'),
                DB::raw('SUM(issuings.insurance_installment) as net_premium'),
                DB::raw('SUM(issuings.insurance_tax) as tax'),
                DB::raw('SUM(issuings.insurance_stamp) as stamp'),
                DB::raw('SUM(issuings.insurance_supervision) as supervision'),
                DB::raw('SUM(issuings.insurance_version) as issuing_fee'),
                DB::raw('SUM(issuings.insurance_total) as total')
            )
            ->groupBy('companies.id', 'companies.name');

        if ($startDate && $endDate) {
            $query->whereBetween('issuings.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($companyId) {
            $query->where('companies.id', $companyId);
        }

        $data = $query->get();

        return view('dashbord.report.company_summarypdf', [
            'data' => $data,
            'companies' => DB::table('companies')->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companyId' => $companyId,
            'user' => $user
        ]);
    }

    public function companySummaryReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $companyId = $request->input('company_id');
        $user = auth()->user();


        // السماح بعرض التقرير إذا وُجدت تواريخ أو شركة
        if (
            (empty($startDate) || empty($endDate)) && empty($companyId)
        ) {
            return view('dashbord.report.company_summary', [
                'data' => [],
                'companies' => DB::table('companies')->get(),
                'startDate' => null,
                'endDate' => null,
                'companyId' => null,
                'user' => $user
            ]);
        }

        $query = DB::table('issuings')
            ->join('cards', 'issuings.cards_id', '=', 'cards.id')
            ->join('offices', 'issuings.offices_id', '=', 'offices.id')
            ->join('companies', 'offices.companies_id', '=', 'companies.id')
            ->select(
                'companies.name as company_name',
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 2 THEN 1 END) as issued_count'),
                DB::raw('COUNT(CASE WHEN cards.cardstautes_id = 3 THEN 1 END) as canceled_count'),
                DB::raw('SUM(issuings.insurance_installment) as net_premium'),
                DB::raw('SUM(issuings.insurance_tax) as tax'),
                DB::raw('SUM(issuings.insurance_stamp) as stamp'),
                DB::raw('SUM(issuings.insurance_supervision) as supervision'),
                DB::raw('SUM(issuings.insurance_version) as issuing_fee'),
                DB::raw('SUM(issuings.insurance_total) as total')
            )
            ->groupBy('companies.id', 'companies.name');

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('issuings.created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        if (!empty($companyId)) {
            $query->where('companies.id', $companyId);
        }

        $data = $query->get();

        return view('dashbord.report.company_summary', [
            'data' => $data,
            'companies' => DB::table('companies')->where('id', '!=', 1)->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companyId' => $companyId,
            'user' => $user
        ]);
    }
}
