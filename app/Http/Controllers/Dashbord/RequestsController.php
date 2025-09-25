<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Apiuser;
use App\Models\car;
use App\Models\Card;
use Carbon\Carbon;

use App\Models\Requests as Req;
use App\Services\LifoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class RequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        ActivityLogger::activity("عرض كافة الطلب المكتب الموحد");

        return view('dashbord.requests.index');
    }

    public function indexco()
    {
        ActivityLogger::activity("عرض كافة طلبات شركات التامين");

        return view('dashbord.requests.indexco');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        ActivityLogger::activity("اضافة  طلب صفحة");
        return view('dashbord.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => "الرجاء ادخل الاسم",
            'cards_number.required' => "الرجاء ادخل عدد البطاقات",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:250', 'unique:cars'],
            'cards_number' => ['required', 'numeric', 'max:50000'],

        ], $messages);
        try {


            $lifos = new LifoApiService();
            $userid = Config::get('apilifo.user_api_name');
            $userpass = Config::get('apilifo.user_api_password');
            $atuh = $lifos->getAuth($userid, $userpass);
            $body = $atuh->getBody();
            $response = json_decode($body->getContents());
            if ($response->status == 2000) {
                $key = $response->data;

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $key
                ];

                $body = [
                    "RQ_UO_CODE" => "$userid",
                    "RQ_CERT_COUNT" => "$request->cards_number",
                    "RQ_REASON" =>  "New Stock",
                    "RQ_USER_ID" => "$userid",
                ];

                $newrequest = $lifos->newrequestadmin($headers, $body);
                $bodyca = $newrequest->getBody();
                $responseca = json_decode($bodyca->getContents());
                $code = $responseca->status;
                if ($code == 8058) {


                    DB::transaction(function () use ($request, $responseca) {

                        $req = new req();
                        $req->request_number = $responseca->data;
                        $req->cards_number = $request->cards_number;
                        $req->companies_id = null;
                        $req->company_users_id = null;
                        $req->users_id = Auth::user()->id;

                        $req->request_statuses_id = 1;
                        $req->save();
                    });
                    Alert::success("تمت عملية اضافة طلب بنجاح");
                    ActivityLogger::activity("تمت عملية طلب  بنجاح");

                    return redirect()->route('cardrequests');
                } else if ($response->status == 8051) {

                    Alert::warning("معلمات الطلب غير مكتملة");
                    ActivityLogger::activity("معلمات الطلب غير مكتملة");
                    return redirect()->back();
                } else if ($response->status == 8052) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");
                    return redirect()->back();
                } else if ($response->status == 8053) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");

                    return redirect()->back();
                } else if ($response->status ==  2501) {


                    Alert::warning("لم يتم العثور على المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2502) {


                    Alert::warning("المستخدم غير نشط");
                    ActivityLogger::activity("المستخدم غير نشط");

                    return redirect()->back();
                } else if ($response->status ==  2503) {
                    Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");
                    ActivityLogger::activity("لا يتمتع المستخدم بامتياز إجراء العملية");

                    return redirect()->back();
                } else if ($response->status ==  2504) {

                    Alert::warning("لم يتم العثور على طرف المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على طرف المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2505) {

                    Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");
                    ActivityLogger::activity("لم يتم تمكين امتياز المستخدم للمستخدم");

                    return redirect()->back();
                }
                // dd($responseca);
            } else if ($response->status == 2001) {
                Alert::warning("فشلت مصادقة المستخدم");
                ActivityLogger::activity("فشلت مصادقة المستخدم");

                return redirect()->back();
            } else if ($response->status == 8051) {
                Alert::warning("معلمات الطلب غير مكتملة");
                ActivityLogger::activity("معلمات الطلب غير مكتملة ");

                return redirect()->back();
            } else if ($response->status == 8052) {

                Alert::warning("غير قادر على بدء الطلب");
                ActivityLogger::activity("غير قادر على بدء الطلب");

                return redirect()->back();
            }
        } catch (\Exception $e) {
            Alert::warning($e->getMessage() . "فشل اضافة طلب");
            ActivityLogger::activity($e->getMessage() . "فشل اضافة طلب");

            return redirect()->route('cardrequests');
        }
    }

     /**
     * Display the specified resource.
     */
    public function ALLreqest()
    {

        $Req = Req::with(
            'companies',
            'users',
            'company_users',
            'request_statuses'
        )->whereNull('companies_id')
                  ->orderBy('created_at', 'DESC');  // ← here

        return datatables()->of($Req)

            ->addColumn('companies_name', function ($Req) {

                $companies_name = $Req->companies_id;
                if ($companies_name) {
                    $companies_name = $Req->companies->name;
                } else {

                    $companies_name = 'الاتحاد الليبي للتآمين';
                    # code...
                }

                return $companies_name;
            })
            ->addColumn('requesby', function ($Req) {

                $requesby = $Req->company_users_id;
                if ($requesby) {
                    $requesby = $Req->company_users->username;
                } else {

                    $requesby = $Req->users->username;
                }

                return $requesby;
            })
            ->addColumn('changeStatus', function ($Req) {
                $companies_name = $Req->companies_id;

                if ($companies_name) {
                    return '';
                } else {
                    $Req_id = encrypt($Req->id);

                    return '<a href="' . route('cardrequests/updatestates', $Req_id) . '"><i  class="fa  fa-refresh"> </i></a>';
                }
            })
            ->addColumn('uplode', function ($Req) {

                if ($Req->request_statuses_id == 2) {
                    if ($Req->uploded == 0) {
                        $Req_id = encrypt($Req->id);

                        return '<a href="' . route('cardrequests/uplodecards', $Req_id) . '"><img src="' . asset('uplode.png') . '" style="width: 30px;"></a>';
                    } else {

                        return 'تم التنزيل';
                    }
                } else {
                    return '';
                }
            })



            ->rawColumns(['requesby', 'uplode', 'companies_name', 'changeStatus'])


            ->make(true);
    }
public function ALLreqestcom()
{
    $Req = Req::with(
        'companies',
        'users',
        'company_users',
        'request_statuses'
    )->whereNull('users_id')
     ->orderBy('created_at', 'DESC');

    // التعامل مع paging من DataTables
    $start = request()->get('start', 0);
    $length = request()->get('length', 10);

    // عدد كل السجلات
    $total = $Req->count();

    // تطبيق paging
    $ReqData = $Req->skip($start)->take($length)->get();

    // تجهيز البيانات
    $data = $ReqData->map(function ($Req) {
        return [
            'request_number' => $Req->request_number,
            'companies_name' => $Req->companies_id ? $Req->companies->name : 'الاتحاد الليبي للتآمين', 
            'requesby' => $Req->company_users_id ? $Req->company_users->username : ($Req->companies_id ? $Req->companies->name : ''),
            'cards_number' => $Req->cards_number,
            'request_statuses_name' => $Req->request_statuses->name,
            'created_at' => $Req->created_at,
            'accept' => $Req->request_statuses_id == 1 && $Req->uploded == 0 ? 
                '<a type="button" class="button" data-toggle="tooltip" data-placement="top" title="قبول الطلب" style="color: red;" data-id="' . encrypt($Req->id) . '">
                    <img src="' . asset('checked.png') . '" style="width: 30px;">
                </a>' : 
                ($Req->request_statuses_id == 1 ? 'تم التنزيل' : ''),
            'uploded_datetime' => $Req->uploded_datetime,
        ];
    });

    return response()->json([
        'draw' => intval(request()->get('draw')), // مهم جداً أن يكون رقم
        'recordsTotal' => $total,
        'recordsFiltered' => $total, // إذا لم يكن هناك فلترة
        'data' => $data
    ]);
}


    

    public function ALLreqestcomx()
    {
        // استعلام لجلب البيانات مع الترتيب حسب id تصاعدياً
        $Req = Req::with(
            'companies',
            'users',
            'company_users',
            'request_statuses'
        )->whereNull('users_id')->orderBy('id', 'DESC');
    
        // إنشاء جدول البيانات
        return datatables()->of($Req)
            ->addColumn('companies_name', function ($Req) {
                $companies_name = $Req->companies_id;
    
                if ($companies_name) {
                    return $Req->companies->name; // اسم الشركة المرتبطة
                } else {
                    return 'الاتحاد الليبي للتآمين'; // القيمة الافتراضية
                }
            })
            ->addColumn('requesby', function ($Req) {
                $requesby = $Req->company_users_id;
    
                if ($requesby) {
                    return $Req->company_users->username; // اسم المستخدم في الشركة
                } else {
                    return $Req->users->username; // اسم المستخدم العام
                }
            })
            ->addColumn('accept', function ($Req) {
                // تحقق من حالة الطلب
                if ($Req->request_statuses_id == 1) {
                    if ($Req->uploded == 0) {
                        $Req_id = encrypt($Req->id);
    
                        return '<a type="button" class="button" data-toggle="tooltip" data-placement="top" title="قبول الطلب" style="color: red;" data-id="' . $Req_id . '">
                                    <img src="' . asset('checked.png') . '" style="width: 30px;">
                                </a>';
                    } else {
                        return 'تم التنزيل'; // تم تنزيل الطلب بالفعل
                    }
                } else {
                    return ''; // الطلب ليس في حالة قبول
                }
            })
            ->rawColumns(['companies_name', 'requesby', 'accept'])
            ->make(true);
    }
    

    public function updatestates($id)
    {


        try {
            $req = decrypt($id);
            $reques = Req::find($req);

            $lifos = new LifoApiService();
            $userid = Config::get('apilifo.user_api_name');
            $userpass = Config::get('apilifo.user_api_password');
            $atuh = $lifos->getAuth($userid, $userpass);
            $body = $atuh->getBody();
            $responsed = json_decode($body->getContents());

            if ($responsed->status == 2000) {
                $key = $responsed->data;

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $key
                ];

                $body = [

                    "RQ_REQ_NO" => "$reques->request_number",
                    "RQ_USER_ID" => "$userid",
                ];

                $newrequest = $lifos->requeststatusadmin($headers, $body);
                $bodyca = $newrequest->getBody();
                $response = json_decode($bodyca->getContents());

                $code = $response->status;

                if ($code == 8075) {




                    DB::transaction(function () use ($id, $response) {

                        $req = decrypt($id);
                        $reques = Req::find($req);


                        $reques->request_statuses_id = 2;
                        $reques->save();
                    });
                    Alert::success("تمت عملية تحديث طلب بنجاح");
                    ActivityLogger::activity("تمت عملية تحديث  بنجاح");

                    return redirect()->route('cardrequests');
                } else if ($response->status == 8062) {


                    $statusMessage = $response->statusMessage;

                    $status = $lifos->extractStatus($statusMessage);
                    if ($status) {
                        // Do something with the extracted status, e.g.,
                        if ($status === 'Rejected') {
                            DB::transaction(function () use ($id, $response) {

                                $req = decrypt($id);
                                $reques = Req::find($req);


                                $reques->request_statuses_id = 3;
                                $reques->save();
                            });
                            Alert::success("تمت عملية تحديث طلب بنجاح");
                            ActivityLogger::activity("تمت عملية تحديث  بنجاح");

                            return redirect()->route('cardrequests');
                        } else {
                            DB::transaction(function () use ($id, $response) {

                                $req = decrypt($id);
                                $reques = Req::find($req);


                                $reques->request_statuses_id = 1;
                                $reques->save();
                            });
                            Alert::success("تمت عملية تحديث طلب بنجاح");
                            ActivityLogger::activity("تمت عملية تحديث  بنجاح");

                            return redirect()->route('cardrequests');
                        }
                    }
                } elseif ($response->status == 8051) {

                    Alert::warning("معلمات الطلب غير مكتملة");
                    ActivityLogger::activity("معلمات الطلب غير مكتملة");
                    return redirect()->back();
                } else if ($response->status == 8052) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");
                    return redirect()->back();
                } else if ($response->status == 8053) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");

                    return redirect()->back();
                } else if ($response->status ==  2501) {


                    Alert::warning("لم يتم العثور على المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2502) {


                    Alert::warning("المستخدم غير نشط");
                    ActivityLogger::activity("المستخدم غير نشط");

                    return redirect()->back();
                } else if ($response->status ==  8061) {
                    Alert::warning("رقم الطلب غير موجود");
                    ActivityLogger::activity("رقم الطلب غير موجود");

                    return redirect()->back();
                } else if ($response->status ==  2503) {
                    Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");
                    ActivityLogger::activity("لا يتمتع المستخدم بامتياز إجراء العملية");

                    return redirect()->back();
                } else if ($response->status ==  2504) {

                    Alert::warning("لم يتم العثور على طرف المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على طرف المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2505) {

                    Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");
                    ActivityLogger::activity("لم يتم تمكين امتياز المستخدم للمستخدم");

                    return redirect()->back();
                }
                // dd($responseca);
            } else if ($responsed->status == 2001) {
                Alert::warning("فشلت مصادقة المستخدم");
                ActivityLogger::activity("فشلت مصادقة المستخدم");

                return redirect()->back();
            } else if ($responsed->status == 8051) {
                Alert::warning("معلمات الطلب غير مكتملة");
                ActivityLogger::activity("معلمات الطلب غير مكتملة ");

                return redirect()->back();
            } else if ($responsed->status == 8052) {

                Alert::warning("غير قادر على بدء الطلب");
                ActivityLogger::activity("غير قادر على بدء الطلب");

                return redirect()->back();
            } else if ($responsed->status == 2501) {

                Alert::warning("لم يتم العثور على المستخدم في النظام");
                ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                return redirect()->back();
            } else if ($responsed->status == 2502) {

                Alert::warning("المستخدم غير نشط");
                ActivityLogger::activity("المستخدم غير نشط");

                return redirect()->back();
            }
        } catch (\Exception $e) {

            Alert::warning($e->getMessage() . "فشل تحديث طلب");
            ActivityLogger::activity($e->getMessage() . "فشل تحديث طلب");

            return redirect()->route('cardrequests');
        }
    }



    public function acceptrequestold($id)
    {


        try {
            $req = decrypt($id);
            $reques = Req::find($req);

            $lifos = new LifoApiService();
            $userid = Config::get('apilifo.user_api_name');
            $userpass = Config::get('apilifo.user_api_password');
            $atuh = $lifos->getAuth($userid, $userpass);
            $body = $atuh->getBody();
            $responsed = json_decode($body->getContents());
            if ($responsed->status == 2000) {
                $key = $responsed->data;

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $key
                ];

                $RQ_USER_ID = Apiuser::where('companies_id', $reques->companies_id)->first();
                $cards_number = $reques->cards_number;

                $get_cards_data = Card::whereNull('companies_id')->limit($cards_number)->get();

                $formattedData = $get_cards_data->map(function ($card) {
                    return [
                        'CB_CERT_UID' => 0,
                        'CB_CERT_NO' => $card['card_number'],
                        'CB_BOOK_ID' => 0,
                        'CB_INS_COMP_CODE' => 0,
                    ];
                })->values()->toJson();
                $body = [

                    "RQ_USER_ID" => "$userid",
                    "RQ_REQ_NO" =>  "$reques->request_number",
                    "RQ_DATA" => "$formattedData",
                ];
                $newrequest = $lifos->postInsCompCertificateBook($headers, $body);

                $bodyca = $newrequest->getBody();

                $response = json_decode($bodyca->getContents());
                $code = $response->status;

                if ($code == 8076) {
                    DB::transaction(function () use ($id, $get_cards_data) {
                        foreach ($get_cards_data as $carddata) {

                            $req = decrypt($id);
                            $reques = Req::find($req);
                            $reques->uploded = 1;
                            $reques->request_statuses_id = 2;
                            $reques->uploded_datetime = now()->addHour()->format('Y-m-d H:i:s');
                            $reques->save();
                            $card = Card::where('card_serial', $carddata->card_serial)->first();

                            $card->cardstautes_id = 1;
                            $card->requests_id = $reques->id;
                            $card->companies_id = $reques->companies_id;

                            $card->save();
                        }
                    });


                    Alert::success("تمت عملية قبول   الطلب  وتنزيل البسطاقات بنجاح");
                    ActivityLogger::activity("تمت عملية عملية  الطلب وتنزيل البطاقات  بنجاح");

                    return redirect()->back();
                } else if ($response->status == 8088) {
                    // dd($response);

                    Alert::warning("تم قبول الطلب بالفعل");
                    ActivityLogger::activity("تم قبول الطلب بالفعل");
                    return redirect()->back();
                } else if ($response->status == 8077) {

                    Alert::warning("فشل تنزيل الطلب   ");
                    ActivityLogger::activity("فشل تنزيل الطلب   ");
                    return redirect()->back();
                } else if ($response->status == 8062) {

                    Alert::warning("لايمكن تنزيل الطلب   ");
                    ActivityLogger::activity("لايمكن تنزيل الطلب   ");
                    return redirect()->back();
                } elseif ($response->status == 8051) {

                    Alert::warning("معلمات الطلب غير مكتملة");
                    ActivityLogger::activity("معلمات الطلب غير مكتملة");
                    return redirect()->back();
                } else if ($response->status == 8052) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");
                    return redirect()->back();
                } else if ($response->status == 8053) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");

                    return redirect()->back();
                } else if ($response->status ==  2501) {


                    Alert::warning("لم يتم العثور على المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2502) {


                    Alert::warning("المستخدم غير نشط");
                    ActivityLogger::activity("المستخدم غير نشط");

                    return redirect()->back();
                } else if ($response->status ==  8061) {
                    Alert::warning("رقم الطلب غير موجود");
                    ActivityLogger::activity("رقم الطلب غير موجود");

                    return redirect()->back();
                } else if ($response->status ==  2503) {
                    Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");
                    ActivityLogger::activity("لا يتمتع المستخدم بامتياز إجراء العملية");

                    return redirect()->back();
                } else if ($response->status ==  2504) {

                    Alert::warning("لم يتم العثور على طرف المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على طرف المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2505) {

                    Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");
                    ActivityLogger::activity("لم يتم تمكين امتياز المستخدم للمستخدم");

                    return redirect()->back();
                }
                // dd($responseca);
            } else if ($responsed->status == 2001) {
                Alert::warning("فشلت مصادقة المستخدم");
                ActivityLogger::activity("فشلت مصادقة المستخدم");

                return redirect()->back();
            } else if ($responsed->status == 8051) {
                Alert::warning("معلمات الطلب غير مكتملة");
                ActivityLogger::activity("معلمات الطلب غير مكتملة ");

                return redirect()->back();
            } else if ($responsed->status == 8052) {

                Alert::warning("غير قادر على بدء الطلب");
                ActivityLogger::activity("غير قادر على بدء الطلب");

                return redirect()->back();
            } else if ($responsed->status == 2501) {

                Alert::warning("لم يتم العثور على المستخدم في النظام");
                ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                return redirect()->back();
            } else if ($responsed->status == 2502) {

                Alert::warning("المستخدم غير نشط");
                ActivityLogger::activity("المستخدم غير نشط");

                return redirect()->back();
            }
        } catch (\Exception $e) {

            Alert::warning($e->getMessage() . "فشل تنزيل بطاقات");
            ActivityLogger::activity($e->getMessage() . "فشل تنزيل بطاقات");

            return redirect()->route('cardrequests');
        }
    }


    public function acceptrequest($id)
    {
         
        try {
            $req = decrypt($id);
            $reques = Req::find($req);

            $lifos = new LifoApiService();
            $userid = Config::get('apilifo.user_api_name');
            $userpass = Config::get('apilifo.user_api_password');
            $atuh = $lifos->getAuth($userid, $userpass);
            $body = $atuh->getBody();
            $responsed = json_decode($body->getContents());

            if ($responsed->status == 2000) {
                $key = $responsed->data;

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $key
                ];

                $RQ_USER_ID = Apiuser::where('companies_id', $reques->companies_id)->first();
                $cards_number = $reques->cards_number;

                $get_cards_data = Card::whereNull('companies_id')->limit($cards_number)->get();
                $formattedData = $get_cards_data->map(function ($card) {
                    return [
                        'CB_CERT_UID' => 0,
                        'CB_CERT_NO' => $card['card_number'],
                        'CB_BOOK_ID' => 0,
                        'CB_INS_COMP_CODE' => 0,
                    ];
                })->values()->toJson();

                $body = [
                    "RQ_USER_ID" => "$userid",
                    "RQ_REQ_NO" =>  "$reques->request_number",
                    "RQ_DATA" => "$formattedData",
                ];

                $newrequest = $lifos->postInsCompCertificateBook($headers, $body);
                $bodyca = $newrequest->getBody();
                $response = json_decode($bodyca->getContents());
                $code = $response->status;


                if ($code == 8076) {
                    DB::transaction(function () use ($id, $get_cards_data) {
                        foreach ($get_cards_data as $carddata) {
                            $req = decrypt($id);
                            $reques = Req::find($req);
                            $reques->uploded = 1;
                            $reques->request_statuses_id = 2;
                            $reques->uploded_datetime = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
                            // $reques->uploded_datetime = now()->format('Y-m-d H:i:s');
                            $reques->save();

                            $card = Card::where('card_serial', $carddata->card_serial)->first();
                            $card->cardstautes_id = 1;
                            $card->requests_id = $reques->id;
                            $card->companies_id = $reques->companies_id;
                            $card->save();
                        }
                    });

                    return response()->json([
                        'status' => 'success',
                        'message' => 'تمت عملية قبول الطلب وتنزيل البطاقات بنجاح'
                    ]);
                } else {
                    return $this->handleErrorResponse($response->status);
                }
            } else {
                return $this->handleErrorResponse($responsed->status);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() . "فشل تنزيل بطاقات"
            ]);
        }
    }

    private function handleErrorResponse($status)
    {
        $messages = [
            8088 => "تم قبول الطلب بالفعل",
            8077 => "فشل تنزيل الطلب",
            8062 => "لايمكن تنزيل الطلب",
            8051 => "معلمات الطلب غير مكتملة",
            8052 => "غير قادر على تقديم الطلب",
            // Add more statuses as needed
        ];

        return response()->json([
            'status' => 'warning',
            'message' => $messages[$status] ?? "حدث خطأ غير معروف"
        ]);
    }


    public function rejectrequest($id)
    {


        try {
            $req = decrypt($id);
            $reques = Req::find($req);
            $reques->request_statuses_id = 3;
            $reques->save();
            Alert::success("تمت عملية رفض   الطلب    بنجاح");
            ActivityLogger::activity("تمت عملية   رفض  الطلب  بنجاح");

            return redirect()->back();
        } catch (\Exception $e) {

            Alert::warning($e->getMessage() . "فشل رفض الطلب ");
            ActivityLogger::activity($e->getMessage() . "فشل رفض الطلب");

            return redirect()->route('cardrequests');
        }
    }




    public function uplodecards($id)
    {
// dd(sys_get_temp_dir(), ini_get('upload_tmp_dir'));


        try {
            $req = decrypt($id);
            $reques = Req::find($req);

            $lifos = new LifoApiService();
            $userid = Config::get('apilifo.user_api_name');
            $userpass = Config::get('apilifo.user_api_password');
            $atuh = $lifos->getAuth($userid, $userpass);
            $body = $atuh->getBody();
            $responsed = json_decode($body->getContents());

            if ($responsed->status == 2000) {
                $key = $responsed->data;

                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $key
                ];

                $body = [

                    "RQ_REQ_NO" => "$reques->request_number",
                    "RQ_USER_ID" => "$userid",
                ];

                $newrequest = $lifos->addcardsadmin($headers, $body);

                $bodyca = $newrequest->getBody();

                $response = json_decode($bodyca->getContents());
                $code = $response->status;

                if ($code == 1336) {
                    $card_data = json_decode($response->data);


                    DB::transaction(function () use ($id, $card_data) {
                        foreach ($card_data as $carddata) {

                            $req = decrypt($id);
                            $reques = Req::find($req);
                            $reques->uploded = 1;
                            $reques->uploded_datetime = now()->format('Y-m-d H:i:s');
                            $reques->save();

                            $card = new Card();
                            $card->card_serial = $carddata->CERT_SRL_NO;
                            $card->card_number = $carddata->CERT_NO;
                            $card->BOOK_ID = $carddata->BOOK_ID;
                            $card->card_insert_date =  now()->format('Y-m-d H:i:s');
                            $card->cardstautes_id = 0;
                            $card->requests_id = $reques->id;
                            $card->users_id = Auth::user()->id;
                            $card->save();
                        }
                    });
                    Alert::success("تمت عملية تنزيل البطاقات  بنجاح");
                    ActivityLogger::activity("تمت عملية تنزيل  البطاقات  بنجاح");

                    return redirect()->route('cardrequests');
                } else if ($response->status == 8062) {

                    Alert::warning("لايمكن تنزيل الطلب   ");
                    ActivityLogger::activity("لايمكن تنزيل الطلب   ");
                } elseif ($response->status == 8051) {

                    Alert::warning("معلمات الطلب غير مكتملة");
                    ActivityLogger::activity("معلمات الطلب غير مكتملة");
                    return redirect()->back();
                } else if ($response->status == 8052) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");
                    return redirect()->back();
                } else if ($response->status == 8053) {
                    Alert::warning(" غير قادر على تقديم الطلب");
                    ActivityLogger::activity(" غير قادر على تقديم الطلب");

                    return redirect()->back();
                } else if ($response->status ==  2501) {


                    Alert::warning("لم يتم العثور على المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2502) {


                    Alert::warning("المستخدم غير نشط");
                    ActivityLogger::activity("المستخدم غير نشط");

                    return redirect()->back();
                } else if ($response->status ==  8061) {
                    Alert::warning("رقم الطلب غير موجود");
                    ActivityLogger::activity("رقم الطلب غير موجود");

                    return redirect()->back();
                } else if ($response->status ==  2503) {
                    Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");
                    ActivityLogger::activity("لا يتمتع المستخدم بامتياز إجراء العملية");

                    return redirect()->back();
                } else if ($response->status ==  2504) {

                    Alert::warning("لم يتم العثور على طرف المستخدم في النظام");
                    ActivityLogger::activity("لم يتم العثور على طرف المستخدم في النظام");

                    return redirect()->back();
                } else if ($response->status ==  2505) {

                    Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");
                    ActivityLogger::activity("لم يتم تمكين امتياز المستخدم للمستخدم");

                    return redirect()->back();
                }
                // dd($responseca);
            } else if ($responsed->status == 2001) {
                Alert::warning("فشلت مصادقة المستخدم");
                ActivityLogger::activity("فشلت مصادقة المستخدم");

                return redirect()->back();
            } else if ($responsed->status == 8051) {
                Alert::warning("معلمات الطلب غير مكتملة");
                ActivityLogger::activity("معلمات الطلب غير مكتملة ");

                return redirect()->back();
            } else if ($responsed->status == 8052) {

                Alert::warning("غير قادر على بدء الطلب");
                ActivityLogger::activity("غير قادر على بدء الطلب");

                return redirect()->back();
            } else if ($responsed->status == 2501) {

                Alert::warning("لم يتم العثور على المستخدم في النظام");
                ActivityLogger::activity("لم يتم العثور على المستخدم في النظام");

                return redirect()->back();
            } else if ($responsed->status == 2502) {

                Alert::warning("المستخدم غير نشط");
                ActivityLogger::activity("المستخدم غير نشط");

                return redirect()->back();
            }
        } catch (\Exception $e) {
            ActivityLogger::activity($e->getMessage() . "فشل تنزيل بطاقات");

            return redirect()->route('cardrequests');
        }
    }
}
