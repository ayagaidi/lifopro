<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Apiuser;
use App\Models\car;
use App\Models\Card;
use App\Models\Company;
use App\Models\Country;
use App\Models\InsuranceClause;
use App\Models\issuing;
use App\Models\Price;
use App\Models\VehicleNationality;
use App\Services\LifoApiService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Config;

class IssuingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:companys');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $cars = car::where('active', 1)->get();
        $VehicleNationality = VehicleNationality::where('active', 1)->get();
        $Countries = Country::where('active', 1)->get();

        $insurance_clauses = InsuranceClause::get();
  date_default_timezone_set('Africa/Tripoli');
        $todatdate=Carbon::now()->format('Y-m-d');
        $price = Price::find(1);
        return view('comapny.Issuing.index')
            ->with('Countries', $Countries)
            ->with('price', $price)
            ->with('todatdate', $todatdate)

            ->with('insurance_clauses', $insurance_clauses)

            ->with('VehicleNationality', $VehicleNationality)
            ->with('cars', $cars);
    }

    public function country($id)
    {
        $country = Country::find($id);

        // Check if country exists
        if (!$country) {
            return response()->json(0);
        }

        return response()->json($country);
    }
    public function issuingtax()
    {
        $Price = Price::find(1);

        // Check if country exists
        if (!$Price) {
            return response()->json(0);
        }

        return response()->json($Price);
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
        $messages = [
            'insurance_name.required' => "الرجاء ادخل  اسم المؤمن ",
            'insurance_location.required' => "الرجاء ادخل العنوان",
            'insurance_phone.required' => "الرجاء ادخل الهاتف",

            'motor_number.required' => "الرجاء ادخل  رقم المحرك ",
            'chassis_number.required' => "الرجاء ادخل  رقم الهيكل ",
            'plate_number.required' => "الرجاء ادخل  اللوحة المعدنية ",
            'car_made_date.required' => "الرجاء ادخل  تاريخ الصنع ",
            'cars_id.required' => "الرجاء ادخل النوع",
            'vehicle_nationalities_id.required' => "الرجاء ادخل  جنسية المركبة ",
            'countries_id.required' => "الرجاء ادخل  جنسية المركبة ",


            'insurance_day_from.required' => "الرجاء ادخل  من يوم ",
            'insurance_days_number.required' => "الرجاء ادخل  عدد الايام ",
            'nsurance_day_to.required' => "الرجاء ادخل  الي يوم ",


            'insurance_clauses_id.required' => "الرجاء ادخل البند",
            'insurance_country_number.required' => "الرجاء ادخل  عدد الدول ",
            'insurance_installment_daily.required' => "الرجاء ادخل  القسط اليومي ",


            'insurance_installment.required' => "الرجاء ادخل القسط",
            'insurance_tax.required' => "الرجاء ادخل الضريبة",
            'insurance_supervision.required' => "الرجاء ادخل الإشراف",
            'insurance_version.required' => "الرجاء ادخل الإصدار",
            'insurance_stamp.required' => "الرجاء ادخل  دمغة المحررات ",
            'insurance_total.required' => "الرجاء ادخل الاجمالي",

        ];
        $this->validate($request, [
            'insurance_location' => ['required'],
            'insurance_name' => ['required'],
            'insurance_phone' => ['required'],


            // 'motor_number' => ['required'],
            'chassis_number' => ['required'],
            'plate_number' => ['required'],
            'car_made_date' => ['required'],
            'cars_id' => ['required'],
            'vehicle_nationalities_id' => ['required'],
            'countries_id' => ['required'],

            'insurance_day_from' => ['required', 'date', 'after_or_equal:today'],


            // 'insurance_day_from' => ['required'],
            'insurance_days_number' => ['required'],
            'nsurance_day_to' => ['required'],

            'insurance_clauses_id' => ['required'],
            'insurance_country_number' => ['required'],
            'insurance_installment_daily' => ['required'],


            'insurance_installment' => ['required'],
            'insurance_tax' => ['required'],
            'insurance_supervision' => ['required'],
            'insurance_version' => ['required'],
            'insurance_stamp' => ['required'],
            'insurance_total' => ['required'],


        ], $messages);


        try {


            $lifos = new LifoApiService();
            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {


                $userid = $api->username;

                $userpass = decrypt($api->password);

                $atuh = $lifos->getAuth($userid, $userpass);
                $body = $atuh->getBody();

                $responsee = json_decode($body->getContents());
                if ($responsee->status == 2000) {
                    $key = $responsee->data;

                    $headers = [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $key
                    ];

                    $new_card = Card::where('companies_id', Auth::user()->companies_id)->where('cardstautes_id', 1)->where('card_on_hold', 0)->whereNull('offices_id')->first();

                    if (empty($new_card)) {

                        Alert::warning("لايوجد بطاقات مخزونك قد اكتمل");

                        return redirect()->back();
                    } else {
                        $card_id = $new_card->id;
                        $card_serial     = $new_card->card_serial;
                        $card_number     = $new_card->card_number;


                        $car = car::find($request->cars_id);
                        $Company = Company::find(Auth::user()->companies_id);
                        $Country = Country::find($request->countries_id);
                         $minimumDays = [
    'TUN' => 7,
    'EGY' => 15,
];

$maximumDays = 90;

$symbol = $Country->symbol;

if (isset($minimumDays[$symbol])) {
    $requiredDays = $minimumDays[$symbol];

    if ($request->insurance_days_number < $requiredDays) {
        Alert::warning("يجب أن يكون عدد الأيام {$requiredDays} أو أكثر");
        return redirect()->back();
    }
}

if ($request->insurance_days_number > $maximumDays) {
    Alert::warning("يجب أن يكون عدد الأيام {$maximumDays} أو أقل");
    return redirect()->back();
}


                        date_default_timezone_set('Africa/Tripoli');

                        $today = date("Y-m-d");
                        if ($today == $_POST["insurance_day_from"]) {
                            $time = date("H:i:s");
                        } else {
                            $time = "12:00";
                        }
                        $formatted_date = date('d-m-Y H:i:s');
                        $insurance_from = Carbon::parse($request->insurance_day_from . ' ' . $time);
                                                                          // $insurance_to = Carbon::parse($request->nsurance_day_to . ' ' . $time);

                        // Add hours equivalent to days (days * 24 hours) to the end date
                        $insurance_to = Carbon::parse($request->insurance_day_from . ' ' . $time)->addHours($request->insurance_days_number * 24);
                        // $insurance_date        = date("d-m-Y H:i:s", strtotime($insurance_date));
                        $insurance_day_from    = date("d-m-Y H:i:s", strtotime($insurance_from));
                        $nsurance_day_to      = date("d-m-Y H:i:s", strtotime($insurance_to));
                        $formatted_issuing_date = Carbon::now()->format('d-m-Y H:i:s');
                            $insurance_total = round($request->insurance_total, 1);

                        $bodyy = [
                            "POL_USER_ID"                => "$userid", // اليوزر الخاص بالمكتب الموحد
                            "POL_IC_CODE"                => "$userid", // يوزر الشركة التي ستصدر الوثيقة  ، لابد ان يبدا إل واي مع رقم 
                            "POL_NO"                     => "P/904/21/10020/20", // حقل غير ضروري 
                            "POL_ISS_DT"                 => $formatted_issuing_date,  // تاريخ اصدار الوثيقة
                            "POL_FM_DT"                  => $insurance_day_from,
                            "POL_TO_DT"                  => $nsurance_day_to,
                            "POL_OC_PREM"                => $request->insurance_total,  // القسط التاميني الشهري حقل ليس ضروري 
                            "POL_PREM"                   => "", // يتبع القسط حقل سعر بيع الوثيقة غير ضروري 
                            "POL_INSURED_NAME"           => $request->insurance_name, //  اسم الزبون 
                            "POL_INSURED_TYPE"           => "", //  نوع التأمين غير ضروري  100 يعني تامين فرد 
                            "POL_CIVIL_ID"               => "", //  رقم بطاقة الهوية  ليست ضرورية
                            "POL_INSURED_ADDRESS"        => $request->insurance_location, // عنوان المؤمن غير ضروري
                            "POL_INSURED_POBOX"          => "", //  رقم صندوق البريد غير ضروري
                            "POL_INSURED_OCCUPATION"     => "", // رقم الفاكس غير ضروري
                            "POL_INSURED_COUNTRY"        => "LBY", // جنسية المؤمن غير ضروري
                            "POL_PHONE"                  => "", // هاتف المؤمن غير ضروري
                            "POL_INSURED_MOBILE"         => $request->insurance_phone, //  لا ادري ما الفرق بينهم
                            "POL_FAX"                    => "00", //
                            "POL_EMAIL"                  => "$Company->email", //
                            "POL_IS_SAME_OWNER_DRIVER"   => "Y", // 
                            "POL_DRIVER_NAME"            => $request->insurance_name,
                            "POL_DRIVER_ID"              => "",
                            "POL_DRIVER_NATION"          => "",
                            "POL_SI"                     => "",
                            /// بيانات السيارة الاجبارية 
                            "POL_VEH_MAKE"               => "$car->symbol", // صنع السيارة لا ادري ما هو ليس التاريخ التاريخ بالاسفل
                            "POL_VEH_MODEL"              => "103", // اسم السيارة التايب ،، اجباري تم تثبيته على الرقم 103 قصدا ليتم إنشاء قاعدة بينات السياريات على هذه القيمة مستقبلا
                            "POL_VEH_BODY_TYPE"          => "SD", // حقل غير ضروري
                            "POL_VEH_TYPE"               => $request->insurance_clauses_id, // نوع التامين الخاص بالسيارة تجارية او خاصة دائما ستكون خاصة حقل اجباري ولكن نثبته على قيمة واحدة 
                            "POL_VEH_VARIANT"            => "", // فئة السيارة غير ضرورية 
                            "POL_VEH_ENGINE"             => "-", // رقم المحرك الزامي
                            "POL_VEH_CHASSIS_NO"         => "$request->chassis_number", //   رقم الهيكل إلزامي
                            "POL_VEH_REGN_NO"            => "$request->plate_number", // رقم لوحة السيارة اجباري
                            "POL_VEH_COLOR"              => "", //  لون السيارة ليس اجباري يفضل ارساله
                            "POL_VEH_CC"                 => "", //  قوة المحرك ليس ضروري
                            "POL_VEH_MAKE_YEAR"          => $request->car_made_date, // سنة صنع السيارة ضروري
                            // بيانات الدو
                            "POL_APPL_COUNTRIES"         => "$Country->symbol", //  رمز الدولة ضروري
                            "POL_APPRV_DT"               => "", // تاريخ قبول الوثيقة غير ضروري

                            "POL_APPRV_BY"               => "$userid", // الشخص الذي قام بعمل ابروف الوثيقة  غير ضروري
                            "POL_APPRV_COMMENTS"         => "", // ترك تعليق ،، غير ضرورية
                            "POL_APPRV_REASON"           => "", // السبب غير ضروري 
                            // سيتم اخذهم عن طريق اي بي آي آخر  ،، هذا الحقل الزامي
                            "POL_OC_SRL"                 => "$card_serial", //  
                            "POL_OC_NO"                  => "$card_number" //
                        ];

                        if ($Country->symbol == "TUN") {
                            if ($request->insurance_days_number < 7) {
                                Alert::warning("يجب أن يكون عدد الأيام 7 أو أكثر");

                                return redirect()->back();
                            }
                        }
                        if ($Country->symbol == "EGY") {
                            if ($request->insurance_days_number < 15) {
                                Alert::warning("يجب أن يكون عدد الأيام 15 أو أكثر");

                                return redirect()->back();
                            }
                        }
                        

// $insurance_day_from = Carbon::createFromFormat('d-m-Y H:i:s', $insurance_from);


if ($insurance_from->lt(Carbon::now()->startOfDay())) {
    Alert::warning("تاريخ بداية التأمين يجب أن يكون اليوم أو في المستقبل");
    return redirect()->back();
}


// $now = Carbon::now()->format('Y-m-d H:i'); // الوقت الحالي حتى الدقيقة فقط

   

//     $exists = Issuing::where('plate_number', $plate)
//         ->where('chassis_number', $chassis)
//         ->whereDate('insurance_day_from', $insuranceDate)
//         ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$now])
//         ->exists();

//     if ($exists) {
//         Alert::warning("تنبيه", "لا يمكن إصدار وثيقة بنفس البيانات خلال نفس الدقيقة. يرجى الانتظار دقيقة واحدة.");
//         return redirect()->back()->withInput();
//     }

$now = Carbon::now();
$from = $now->copy()->subMinutes(2)->format('Y-m-d H:i:s');
$to   = $now->copy()->format('Y-m-d H:i:s');
  $plate = $request->plate_number;
   $chassis = $request->chassis_number;
    $insuranceDate = $request->insurance_day_from;
$exists = Issuing::where('plate_number', $plate)
    ->where('chassis_number', $chassis)
    ->whereDate('insurance_day_from', $insuranceDate)
    ->whereBetween('created_at', [$from, $to])
    ->exists();

if ($exists) {
    Alert::warning("تنبيه", "لا يمكن إصدار وثيقة بنفس البيانات خلال دقيقتين متتاليتين. يرجى الانتظار.");
    return redirect()->back()->withInput();
}

                        $newpolicy = $lifos->issuingPolicy($headers, $bodyy);

                        $bodyplo = $newpolicy->getBody();

                        $response = json_decode($bodyplo->getContents());


                        $codeee = $response->code;

                        if ($codeee == 7004) {
                            DB::transaction(function () use ($request, $card_id,   $insurance_day_from, $nsurance_day_to) {


                                $cards = Card::find($card_id);
                                $cards->card_on_hold = 1;
                                $cards->cardstautes_id = 2;


                                $cards->save();


                                $issuing_date = new DateTime(); // Get current datetime
                                $formatted_issuing_date = $issuing_date->format('Y-m-d H:i:s');
                                $issuing = new issuing();
                                $issuing->issuing_date = $issuing_date;
                                $issuing->insurance_name = $request->insurance_name;
                                $issuing->insurance_location = $request->insurance_location;
                                $issuing->insurance_phone = $request->insurance_phone;

                                $issuing->motor_number ="-";
                                $issuing->plate_number = $request->plate_number;
                                $issuing->chassis_number = $request->chassis_number;
                                $issuing->car_made_date = $request->car_made_date;
                                $issuing->cars_id = $request->cars_id;
                                $issuing->vehicle_nationalities_id = $request->vehicle_nationalities_id;

                                $issuing->insurance_day_from =  Carbon::parse($insurance_day_from);
                                $issuing->insurance_days_number = $request->insurance_days_number;
                                $issuing->nsurance_day_to = Carbon::parse($nsurance_day_to);
                                $issuing->insurance_country_number = $request->insurance_country_number;
                                $issuing->insurance_installment_daily = $request->insurance_installment_daily;
                                $issuing->insurance_installment = $request->insurance_installment;

                                $issuing->insurance_supervision = $request->insurance_supervision;
                                $issuing->insurance_tax = $request->insurance_tax;
                                $issuing->insurance_version = $request->insurance_version;
                                $issuing->insurance_stamp = $request->insurance_stamp;
                                $issuing->insurance_total = $request->insurance_total;
                                $issuing->cards_id = $card_id;

                                $issuing->countries_id = $request->countries_id;
                                $issuing->insurance_clauses_id = $request->insurance_clauses_id;
                                $issuing->companies_id = Auth::user()->companies_id;
                                $issuing->company_users_id = Auth::user()->id;

                                $issuing->save();
                            });
                            $bodey = array(
                                "RQ_OC_NO" => "$card_number",
                                "RQ_USER_ID" => "$userid"
                            );


                            $cancel = $lifos->printcard($headers, $bodey);
                            $bodyca = $cancel->getBody();
                            $responseca = json_decode($bodyca->getContents());

                            $code = $responseca->status;
                            if ($code == 1336) {

                                $ex = base64_decode(explode("base64,", $responseca->data)[1]);

                               

                                
                                Alert::success("تمت عملية الاصدار   شركة بنجاح");
                                // return view('comapny.Issuing.doc')->with('ex',$ex);
                                return redirect()->route('company/document', $card_id);
                        
                            } else if ($code == 8051) {
                                Alert::warning("معلمات الطلب غير مكتملة");
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
                            else if ($code == 6014) {
                                Alert::warning("هذه البطاقة تم اصدارها من قبل");
                                return redirect()->back();
                            }
                        } else if ($codeee == 6014) {
                                Alert::warning("هذه البطاقة تم اصدارها من قبل");
                                return redirect()->back();
                            }
                        else 
                        {
                            dd($response);

                            Alert::warning($responsee);

                            return redirect()->back();
                        }
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
            } else {
                Alert::warning("فشل اصدار وثيقة");

                return redirect()->back();
            }
        } catch (\Exception $e) {


            Alert::warning($e . "فشل اصدار وثيقة");

            return redirect()->back();
        }
    }


    public function document($card_id)
    {

        try {
            $lifos = new LifoApiService();

            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {

                $cards = Card::find($card_id);
                $card_number = $cards->card_number;
                $userid = $api->username;
                $userpass = decrypt($api->password);

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


                        $ex = base64_decode(explode('base64,', $responseca->data)[1]);

                        // Generate a unique filename (optional for better organization)
                        $filename = $card_id . '.pdf';
                        $tempFilePath = public_path('doc/'.$filename);
                        
                        // Check if the file exists before trying to delete it
                        if (file_exists($tempFilePath)) {
                            unlink($tempFilePath); // Delete the existing file
                        }
                        
                        // $fileObject->move('public/doc/', $filename);
                        // Create a temporary PDF file on the server
                        // $tempFilePath = public_path('doc' , $filename);
                        file_put_contents($tempFilePath, $ex);
                        // Return the view with the PDF file path
                        return view('comapny.Issuing.doc', ['file' => asset('public/doc/' . $filename)])
                        ->with('card_id',$card_id);

                        // Return the view with the PDF file path

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
            } else {
                Alert::warning("فشل عرض وثيقة");

                return redirect()->back();
            }
        } catch (\Exception $e) {


            Alert::warning($e . "فشل عرض وثيقة");

            return redirect()->back();
        }
    }


    public function viewdocument($card_id)
    {

        try {
            $lifos = new LifoApiService();
            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {

                $cards = Card::find($card_id);
                $card_number = $cards->card_number;
                $userid = $api->username;
                $userpass = decrypt($api->password);

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
            } else {
                Alert::warning("فشل عرض وثيقة");

                return redirect()->back();
            }
        } catch (\Exception $e) {


            Alert::warning($e . "فشل عرض وثيقة");

            return redirect()->back();
        }
    }

    public function cancelPolicy($card_id)
    {
        try {
            $lifos = new LifoApiService();
            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();

            if ($api) {
                $cards = Card::find($card_id);
                $card_number = $cards->card_number;
                $userid = $api->username;
                $userpass = decrypt($api->password);

                $atuh = $lifos->getAuth($userid, $userpass);
                $body = $atuh->getBody();
                $responsee = json_decode($body->getContents());

                if ($responsee->status == 2000) {
                    $key = $responsee->data;
                    $headers = [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $key
                    ];

                    $card_numbcancle = Card::where('cardstautes_id', 3)->where('id', $card_number)->first();

                    if (!empty($card_numbcancle)) {
                        return response()->json([
                            'status' => 'warning',
                            'message' => 'هذه البطاقة ملغاة'
                        ], 200);
                    } else {
                         $issuing = Issuing::where('cards_id', $card_id)
    ->where('issuing_date', '>=', Carbon::now()->subHours(24))
    ->first();

                        if ($issuing) {
                            $body = [
                                "POL_USER_ID" => $userid,
                                "POL_OC_NO" => $card_number,
                                "POL_NO" => "P/904/21/10020/20",
                            ];

                            $cancel = $lifos->cancelPolicy($headers, $body);
                            $bodyca = $cancel->getBody();
                            $responseca = json_decode($bodyca->getContents());
                            $code = $responseca->message->code;

                            switch ($code) {
                                case 7506:
                                    $cards->cardstautes_id = 3;
                                    $cards->card_delete_date=now();

                                    $cards->save();

                                    $issuing->insurance_installment_daily = 0;
                                    $issuing->insurance_installment = 0;
                                    $issuing->insurance_supervision = 0;
                                    $issuing->insurance_tax = 0;
                                    $issuing->insurance_version = 0;
                                    $issuing->insurance_stamp = 0;
                                    $issuing->insurance_total = 0;
                                    $issuing->save();

                                    return response()->json([
                                        'status' => 'success',
                                        'message' => 'تمت عملية إلغاء البطاقة بنجاح'
                                    ], 200);

                                case 7008:
                                    $cards = Card::find($card_id);
                                    $cards->cardstautes_id = 3;
                                    $cards->save();
    
                                    $issuing->insurance_installment_daily = 0;
                                    $issuing->insurance_installment = 0;
    
                                    $issuing->insurance_supervision = 0;
                                    $issuing->insurance_tax = 0;
                                    $issuing->insurance_version = 0;
                                    $issuing->insurance_stamp = 0;
                                    $issuing->insurance_total = 0;
                                    $issuing->save();
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'هذه البطاقة ملغاة'
                                    ], 200);

                                case 8051:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'معلمات الطلب غير مكتملة'
                                    ], 200);

                                case 8052:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'غير قادر على تقديم الطلب'
                                    ], 200);

                                case 8069:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'عنوان URL غير موجود'
                                    ], 200);

                                case 8070:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'رقم الشهادة غير موجود أو غير موجود لدى المستخدم الذي قام بتسجيل الدخول'
                                    ], 200);

                                case 8053:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'غير قادر على تقديم الطلب'
                                    ], 200);

                                case 2501:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'لم يتم العثور على المستخدم في النظام'
                                    ], 200);

                                case 2502:
                                    return response()->json([
                                        'status' => 'warning',
                                        'message' => 'المستخدم غير نشط'
                                    ], 200);

                                default:
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'رمز استجابة غير معروف'
                                    ], 500);
                            }
                        } else {
                           return response()->json([
'status' => 'Warning',

'message' => 'It is been more than 24 hours since, Oscar couldnot be opened'
], 200);
                        }
                    }
                } else if ($responsee->status == 2001) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'فشلت مصادقة المستخدم'
                    ], 200);
                } else if ($responsee->status == 8051) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'معلمات الطلب غير مكتملة'
                    ], 200);
                } else if ($responsee->status == 8052) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'غير قادر على بدء الطلب'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'فشل إلغاء الوثيقة'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل إلغاء الوثيقة: ' . $e->getMessage()
            ], 500);
        }
    }


    public function cancelplicyold($card_id)
    {

        try {


            $lifos = new LifoApiService();
            $api = Apiuser::where('companies_id', Auth::user()->companies_id)->first();
            if ($api) {

                $cards = Card::find($card_id);
                $card_number = $cards->card_number;
                $userid = $api->username;
                $userpass = decrypt($api->password);

                $atuh = $lifos->getAuth($userid, $userpass);
                $body = $atuh->getBody();

                $responsee = json_decode($body->getContents());
                if ($responsee->status == 2000) {
                    $key = $responsee->data;

                    $headers = [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $key
                    ];

                    $card_numbcancle = Card::where('cardstautes_id', 3)->where('id', $card_number)->first();

                    if (!empty($card_numbcancle)) {
                        return response()->json([
                            'status' => 'warning',
                            'message' => 'هذه البطاقة ملغاة'
                        ], 200);
                        // Alert::warning("هده البطافة ملغية");

                        // return redirect()->back();
                    } else {


                        // $issuing = Issuing::where('cards_id', $card_id)
                        //     ->where('issuing_date', '>=', Carbon::now()->subHours(24)) // Ensure issuing date is not less than 24 hours ago
                        //     ->first();
                        
                        $issuing = Issuing::where('cards_id', $card_id)
                  ->where('issuing_date', '>=', Carbon::now()->subDays(7))
                  ->first();
                        if ($issuing) {

                            // Handle case where issuing is not found or the issuing date is older than 24 hours
                            // ... (your error handling or other logic)
                            $bodey = [
                                "POL_USER_ID"                => "$userid",
                                "POL_OC_NO"                => "$card_number",
                                "POL_NO"                     => "P/904/21/10020/20",
                            ];

                            $cancel = $lifos->cancelPolicy($headers, $bodey);
                            $bodyca = $cancel->getBody();
                            $responseca = json_decode($bodyca->getContents());

                            $code = $responseca->message->code;

                            if ($code == 7506) {

                                $cards = Card::find($card_id);
                                $cards->cardstautes_id = 3;
                                $cards->card_delete_date=now();
                                $cards->save();

                                $issuing->insurance_installment_daily = 0;
                                $issuing->insurance_installment = 0;

                                $issuing->insurance_supervision = 0;
                                $issuing->insurance_tax = 0;
                                $issuing->insurance_version = 0;
                                $issuing->insurance_stamp = 0;
                                $issuing->insurance_total = 0;
                                $issuing->save();



                                Alert::success("تمت عملية الغاء بطاقة    بنجاح");
                                return redirect()->back();
                            } else if ($code == 7008) {
                                $cards = Card::find($card_id);
                                $cards->cardstautes_id = 3;
                                $cards->save();

                                $issuing->insurance_installment_daily = 0;
                                $issuing->insurance_installment = 0;

                                $issuing->insurance_supervision = 0;
                                $issuing->insurance_tax = 0;
                                $issuing->insurance_version = 0;
                                $issuing->insurance_stamp = 0;
                                $issuing->insurance_total = 0;
                                $issuing->save();
                                Alert::warning("  هده البطاقه البطاقه ملغية ");
                                return redirect()->back();
                            } else if ($code == 8051) {
                                Alert::warning("معلمات الطلب غير مكتملة");
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
                        } else {

                            Alert::warning(" لقد مر اكثر من 24 ساعة علي اصدارها ، لايمكن الغاء الوثيقة ");

                            return redirect()->back();
                        }
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
            } else {
                Alert::warning("فشل الغاء وثيقة");

                return redirect()->back();
            }
        } catch (\Exception $e) {


            Alert::warning($e . "فشل الغاء وثيقة");

            return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(issuing $issuing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(issuing $issuing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, issuing $issuing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(issuing $issuing)
    {
        //
    }
}
