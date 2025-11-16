<?php


namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Apiuser;
use App\Models\car;
use App\Models\Card;
use App\Models\Requests as Req;
use App\Services\LifoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RequestsController extends Controller
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
                ->where('company_user_permissions_id', 3)
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

        return view('comapny.requests.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comapny.requests.create');
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
            'name' => ['required'],
            'cards_number' => ['required', 'numeric', 'max:5000'],
        ], $messages);
        try {


            $lifos = new LifoApiService();
            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {


                $userid = $api->username;
                $userpass = decrypt($api->password);

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
                        "RQ_IC_CODE" => "$userid",
                        "RQ_CERT_COUNT" => "$request->cards_number",
                        "RQ_REASON" =>  "New Stock",
                        "RQ_USER_ID" => "$userid",
                    ];

                    $newrequest = $lifos->newrequest($headers, $body);
                    $bodyca = $newrequest->getBody();
                    $responseca = json_decode($bodyca->getContents());
                    $code = $responseca->status;
                    if ($code == 8058) {


                        DB::transaction(function () use ($request, $responseca) {

                            $req = new req();
                            $req->request_number = $responseca->data;
                            $req->cards_number = $request->cards_number;
                            $req->companies_id = Auth::user()->companies_id;
                            $req->company_users_id = Auth::user()->id;
                            $req->users_id = null;

                            $req->request_statuses_id = 1;
                            $req->save();
                        });
                        Alert::success("تمت عملية اضافة طلب بنجاح");

                        return redirect()->route('company/cardrequests');
                    } else if ($response->status == 8051) {

                        Alert::warning("معلمات الطلب غير مكتملة");
                        return redirect()->back();
                    } else if ($response->status == 8052) {
                        Alert::warning(" غير قادر على تقديم الطلب");
                        return redirect()->back();
                    } else if ($response->status == 8053) {
                        Alert::warning(" غير قادر على تقديم الطلب");

                        return redirect()->back();
                    } else if ($response->status ==  2501) {


                        Alert::warning("لم يتم العثور على المستخدم في النظام");

                        return redirect()->back();
                    } else if ($response->status ==  2502) {


                        Alert::warning("المستخدم غير نشط");

                        return redirect()->back();
                    } else if ($response->status ==  2503) {
                        Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");

                        return redirect()->back();
                    } else if ($response->status ==  2504) {

                        Alert::warning("لم يتم العثور على طرف المستخدم في النظام");

                        return redirect()->back();
                    } else if ($response->status ==  2505) {

                        Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");

                        return redirect()->back();
                    }
                  
                } else if ($response->status == 2001) {
                    Alert::warning("فشلت مصادقة المستخدم");

                    return redirect()->back();
                } else if ($response->status == 8051) {
                    Alert::warning("معلمات الطلب غير مكتملة");

                    return redirect()->back();
                } else if ($response->status == 8052) {

                    Alert::warning("غير قادر على بدء الطلب");

                    return redirect()->back();
                }
            } else {
                Alert::warning("فشل اضافة طلب");

                return redirect()->route('company/cardrequests');
            }
        } catch (\Exception $e) {

            Alert::warning($e . "فشل اضافة طلب");

            return redirect()->route('company/cardrequests');
        }
    }

    /**
     * Display the specified resource.
     */
    public function ALLreqest()
    {

        $Req = Req::with('companies', 'users', 'company_users', 'request_statuses')
            ->where('companies_id', Auth::user()->companies_id)->orderBy('created_at', 'DESC');
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

                    $requesby = $Req->companies->name;
                }

                return $requesby;
            })
            ->addColumn('changeStatus', function ($Req) {
                $Req_id = encrypt($Req->id);

                return '<a href="' . route('company/cardrequests/updatestates', $Req_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })
            ->addColumn('uplode', function ($Req) {

                if ($Req->request_statuses_id == 2) {
                    if ($Req->uploded == 0) {
                        $Req_id = encrypt($Req->id);

                        // return '<a href="' . route('company/cardrequests/uplodecards', $Req_id) . '"><img src="' . asset('uplode.png') . '" style="width: 30px;"></a>';
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




    public function updatestates($id)
    {


        try {
            $req = decrypt($id);
            $reques = Req::find($req);

            $lifos = new LifoApiService();
            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {


                $userid = $api->username;
                $userpass = decrypt($api->password);

                $atuh = $lifos->getAuth($userid, $userpass);
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

                    $newrequest = $lifos->requeststatus($headers, $body);
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

                        return redirect()->route('company/cardrequests');
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

                                return redirect()->route('company/cardrequests');
                            } else {
                                DB::transaction(function () use ($id, $response) {

                                    $req = decrypt($id);
                                    $reques = Req::find($req);


                                    $reques->request_statuses_id = 1;
                                    $reques->save();
                                });
                                Alert::success("تمت عملية تحديث طلب بنجاح");

                                return redirect()->route('company/cardrequests');
                            }
                        }
                    } elseif ($response->status == 8051) {

                        Alert::warning("معلمات الطلب غير مكتملة");
                        return redirect()->back();
                    } else if ($response->status == 8052) {
                        Alert::warning(" غير قادر على تقديم الطلب");
                        return redirect()->back();
                    } else if ($response->status == 8053) {
                        Alert::warning(" غير قادر على تقديم الطلب");

                        return redirect()->back();
                    } else if ($response->status ==  2501) {


                        Alert::warning("لم يتم العثور على المستخدم في النظام");

                        return redirect()->back();
                    } else if ($response->status ==  2502) {


                        Alert::warning("المستخدم غير نشط");

                        return redirect()->back();
                    } else if ($response->status ==  8061) {
                        Alert::warning("رقم الطلب غير موجود");

                        return redirect()->back();
                    } else if ($response->status ==  2503) {
                        Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");

                        return redirect()->back();
                    } else if ($response->status ==  2504) {

                        Alert::warning("لم يتم العثور على طرف المستخدم في النظام");

                        return redirect()->back();
                    } else if ($response->status ==  2505) {

                        Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");

                        return redirect()->back();
                    }
                    // dd($responseca);
                } else if ($responsed->status == 2001) {
                    Alert::warning("فشلت مصادقة المستخدم");

                    return redirect()->back();
                } else if ($responsed->status == 8051) {
                    Alert::warning("معلمات الطلب غير مكتملة");

                    return redirect()->back();
                } else if ($responsed->status == 8052) {

                    Alert::warning("غير قادر على بدء الطلب");

                    return redirect()->back();
                } else if ($responsed->status == 2501) {

                    Alert::warning("لم يتم العثور على المستخدم في النظام");

                    return redirect()->back();
                } else if ($responsed->status == 2502) {

                    Alert::warning("المستخدم غير نشط");

                    return redirect()->back();
                }
            } else {
                Alert::warning("فشل تحديث طلب");

                return redirect()->route('company/cardrequests');
            }
        } catch (\Exception $e) {

            Alert::warning("فشل تحديث طلب");

            return redirect()->route('company/cardrequests');
        }
    }



    public function uplodecards($id)
    {


        try {
            $req = decrypt($id);
            $reques = Req::find($req);

            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {

                $lifos = new LifoApiService();

                $userid = $api->username;
                $userpass = decrypt($api->password);

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

                    $newrequest = $lifos->addcards($headers, $body);

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
                                dd($carddata->CERT_SRL_NO);
                                $card = Card::where('card_serial', $carddata->CERT_SRL_NO)->first();

                                $card->cardstautes_id = 1;
                                $card->requests_id = $reques->id;
                                $card->companies_id = Auth::user()->companies_id;

                                $card->save();
                            }
                        });
                        Alert::success("تمت عملية تنزيل البطاقات  بنجاح");

                        return redirect()->route('company/cardrequests');
                    } else if ($response->status == 8062) {

                        Alert::warning("لايمكن تنزيل الطلب   ");
                    } elseif ($response->status == 8051) {

                        Alert::warning("معلمات الطلب غير مكتملة");
                        return redirect()->back();
                    } else if ($response->status == 8052) {
                        Alert::warning(" غير قادر على تقديم الطلب");
                        return redirect()->back();
                    } else if ($response->status == 8053) {
                        Alert::warning(" غير قادر على تقديم الطلب");

                        return redirect()->back();
                    } else if ($response->status ==  2501) {


                        Alert::warning("لم يتم العثور على المستخدم في النظام");

                        return redirect()->back();
                    } else if ($response->status ==  2502) {


                        Alert::warning("المستخدم غير نشط");

                        return redirect()->back();
                    } else if ($response->status ==  8061) {
                        Alert::warning("رقم الطلب غير موجود");

                        return redirect()->back();
                    } else if ($response->status ==  2503) {
                        Alert::warning("لا يتمتع المستخدم بامتياز إجراء العملية");

                        return redirect()->back();
                    } else if ($response->status ==  2504) {

                        Alert::warning("لم يتم العثور على طرف المستخدم في النظام");

                        return redirect()->back();
                    } else if ($response->status ==  2505) {

                        Alert::warning("لم يتم تمكين امتياز المستخدم للمستخدم");

                        return redirect()->back();
                    }
                } else if ($responsed->status == 2001) {
                    Alert::warning("فشلت مصادقة المستخدم");

                    return redirect()->back();
                } else if ($responsed->status == 8051) {
                    Alert::warning("معلمات الطلب غير مكتملة");

                    return redirect()->back();
                } else if ($responsed->status == 8052) {

                    Alert::warning("غير قادر على بدء الطلب");

                    return redirect()->back();
                } else if ($responsed->status == 2501) {

                    Alert::warning("لم يتم العثور على المستخدم في النظام");

                    return redirect()->back();
                } else if ($responsed->status == 2502) {

                    Alert::warning("المستخدم غير نشط");

                    return redirect()->back();
                }
            } else {
                Alert::warning("فشل تنزيل بطاقات");

                return redirect()->route('company/cardrequests');
            }
        } catch (\Exception $e) {

            Alert::warning($e . "فشل تنزيل بطاقات");

            return redirect()->route('company/cardrequests');
        }
    }
}
