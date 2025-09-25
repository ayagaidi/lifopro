<?php

namespace App\Http\Controllers\Dashbord;

use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Cardstautes;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search()
    {
        ActivityLogger::activity("[بحث بواسطة]عرض كافة البطاقات");

        $Company = Company::get();
        $Cardstautes = Cardstautes::get();
        return view('dashbord.card.search')
            ->with('Cardstautes', $Cardstautes)
            ->with('Company', $Company);
    }

//   public function searchby(Request $request)
// {
//     $query = Card::with(['users', 'companies', 'cardstautes', 'requests']);

//     // فلترة حسب رقم الطلب
//     if (!empty($request->request_number)) {
//         $query->whereHas('requests', function ($q) use ($request) {
//             $q->where('request_number', $request->request_number);
//         });
//     }

//     // فلترة حسب الشركة
//     if ($request->companies_id === "0") {
//         $query->whereNull('companies_id');
//     } elseif (!empty($request->companies_id)) {
//         $query->where('companies_id', $request->companies_id);
//     }

//     // فلترة حسب رقم البطاقة
//     if (!empty($request->card_number)) {
//         $query->where('card_number', $request->card_number);
//     }

//     // فلترة حسب حالة البطاقة
//     if (!empty($request->cardstautes_id)) {
//         $query->where('cardstautes_id', $request->cardstautes_id);
//     }

//     // فلترة حسب التاريخ
//     if (!empty($request->fromdate) && !empty($request->todate)) {
//         try {
//             $from = Carbon::parse($request->fromdate)->startOfDay();
//             $to = Carbon::parse($request->todate)->endOfDay();
//             $query->whereBetween('card_insert_date', [$from, $to]);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'code' => 0,
//                 'status' => false,
//                 'message' => 'تنسيق التاريخ غير صالح.',
//             ], 422);
//         }
//     }

//     $cards = $query->get();

//     if ($cards->isEmpty()) {
//         return response()->json([
//             'code' => 2,
//             'status' => false,
//             'message' => 'لايوجد بطاقات',
//         ], 200);
//     }

//     return response()->json([
//         'code' => 1,
//         'status' => true,
//         'message' => 'يتم عرض البطاقات',
//         'data' => $cards
//     ], 200);
// }
public function searchby(Request $request)
{
    $query = Card::query()
        ->with(['users', 'companies', 'cardstautes', 'requests']);

    $status = $request->cardstautes_id;


    if (!is_null($status)) {
        $query->where('cardstautes_id', $status);

        if (!empty($request->fromdate) && !empty($request->todate)) {
            try {
                $from = Carbon::parse($request->fromdate)->startOfDay();
                $to = Carbon::parse($request->todate)->endOfDay();

                switch ($status) {
                    case 0: // متبقية
                        $query->whereBetween('created_at', [$from, $to]);
                        break;

                    case 1: // معينة (استخدم تاريخ الرفع من requests)
                        $query->whereHas('requests', function ($q) use ($from, $to) {
                            $q->whereBetween('uploded_datetime', [$from, $to]);
                        });
                        break;

                    case 2: // مصدّرة (من جدول issuings)
                        $query->join('issuings', 'issuings.cards_id', '=', 'cards.id')
                              ->select('cards.*', 'issuings.created_at as issuing_date')
                              ->whereBetween('issuings.created_at', [$from, $to])
                              ->orderByDesc('issuings.created_at');
                        break;

                    case 3: // ملغاة
                        $query->whereBetween('card_delete_date', [$from, $to]);
                        break;
                }
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 0,
                    'status' => false,
                    'message' => 'تنسيق التاريخ غير صالح.',
                ], 422);
            }
        }
    }

    if (!empty($request->request_number)) {
        $query->whereHas('requests', fn($q) => $q->where('request_number', $request->request_number));
    }

    if ($request->companies_id === "0") {
        $query->whereNull('companies_id');
    } elseif (!empty($request->companies_id)) {
        $query->where('cards.companies_id', $request->companies_id);
    }

    if (!empty($request->card_number)) {
        $query->where('card_number', $request->card_number);
    }

    $cards = $query->get();

    if ($cards->isEmpty()) {
        return response()->json([
            'code' => 2,
            'status' => false,
            'message' => 'لا توجد بطاقات.',
        ]);
    }

    return response()->json([
        'code' => 1,
        'status' => true,
        'message' => 'تم جلب البيانات بنجاح.',
        'data' => $cards
    ]);
}



public function searchbypdf(Request $request)
{
    
    $query = Card::query()
        ->with(['users', 'companies', 'cardstautes', 'requests']);

    $status = $request->cardstautes_id;


    if (!is_null($status)) {
        $query->where('cardstautes_id', $status);

        if (!empty($request->fromdate) && !empty($request->todate)) {
            try {
                $from = Carbon::parse($request->fromdate)->startOfDay();
                $to = Carbon::parse($request->todate)->endOfDay();

                switch ($status) {
                    case 0: // متبقية
                        $query->whereBetween('created_at', [$from, $to]);
                        break;

                    case 1: // معينة (استخدم تاريخ الرفع من requests)
                        $query->whereHas('requests', function ($q) use ($from, $to) {
                            $q->whereBetween('uploded_datetime', [$from, $to]);
                        });
                        break;

                    case 2: // مصدّرة (من جدول issuings)
                        $query->join('issuings', 'issuings.cards_id', '=', 'cards.id')
                              ->select('cards.*', 'issuings.created_at as issuing_date')
                              ->whereBetween('issuings.created_at', [$from, $to])
                              ->orderByDesc('issuings.created_at');
                        break;

                    case 3: // ملغاة
                        $query->whereBetween('card_delete_date', [$from, $to]);
                        break;
                }
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 0,
                    'status' => false,
                    'message' => 'تنسيق التاريخ غير صالح.',
                ], 422);
            }
        }
    }

    if (!empty($request->request_number)) {
        $query->whereHas('requests', fn($q) => $q->where('request_number', $request->request_number));
    }

    if ($request->companies_id === "0") {
        $query->whereNull('companies_id');
    } elseif (!empty($request->companies_id)) {
        $query->where('cards.companies_id', $request->companies_id);
    }

    if (!empty($request->card_number)) {
        $query->where('card_number', $request->card_number);
    }

    $data = $query->get();
    $user = auth()->user();

    return view('dashbord.card.searchpdf')
        ->with('cards', $data)
        ->with('filters', $request->all())
        ->with('user', $user);
}



    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // dd(ini_get('memory_limit'));
        // $peakMemoryUsage = memory_get_peak_usage();
        // dd( "أقصى استخدام للذاكرة: " . $peakMemoryUsage . " بايت");
        ActivityLogger::activity("عرض كافة البطاقات");

        return view('dashbord.card.index');
    }
    public function cardall()
    {
        // Use Eloquent's paginate method for server-side pagination
        $cards = Card::with(['users', 'companies', 'cardstautes', 'requests'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10); // Adjust the number per page as needed

        // Map the results for additional formatting
        $formattedCards = $cards->getCollection()->map(function ($card) {
            return [
                'id' => $card->id,
                'card_serial' => $card->card_serial ?? 'N/A',
                'card_number' => $card->card_number ?? 'N/A',
                'book_id' => $card->book_id ?? 'N/A',
                'companies_id' => $card->companies->name ?? 'لدي الاتحاد',
                                'cardstautesname' => $card->cardstautes->name ?? 'N/A',

                'request_numberr' => $card->requests->request_number ?? 'N/A',
                'created_at' => $card->created_at, // Format date for DataTables
            ];
        });

        // Return as JSON for DataTables with pagination info
        return response()->json([
            'draw' => request()->get('draw'),
            'recordsTotal' => $cards->total(), // Total number of records in the database
            'recordsFiltered' => $cards->total(), // Total number of filtered records (same as total in this case)
            'data' => $formattedCards // Actual data for the current page
        ]);
    }


    public function cardall2()
    {
        $startMemory = memory_get_peak_usage();
        $cards = [];

        // Chunk the data to handle large datasets efficiently
        Card::with(['users', 'companies', 'cardstautes', 'requests'])
            ->orderBy('created_at', 'DESC')
            ->chunk(100, function ($chunk) use (&$cards) {
                foreach ($chunk as $card) {
                    $cards[] = $this->processCard($card);
                }
            });

        return datatables()->of(collect($cards))

            ->make(true);
        $endMemory = memory_get_peak_usage();
        Log::info('Peak memory usage: ' . ($endMemory - $startMemory) . ' bytes');
    }


    private function processCard($card)
    {
        $processedCard = [
            'id' => $card->id, // Assuming you want to include the card's ID
            'card_serial' => $card->card_serial,
            'card_number' => $card->card_number,
            'book_id' => $card->book_id,
            'companies_id' => $card->companies->name ?? 'الإتحاد الليبي للتأمين',
            'cardstautesname' => $card->cardstautes->name,
            'request_numberr' => $card->requests->request_number,
            'created_at' => $card->created_at,
        ];

        return $processedCard;
    }


public function printSoldCardsPdf()
{
    $cards = DB::table('cards')
        ->leftJoin('companies', 'cards.companies_id', '=', 'companies.id')
        ->leftJoin('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
        ->leftJoin('requests', 'cards.requests_id', '=', 'requests.id')
                        ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id') // 

        ->where('cards.cardstautes_id', 2)
        ->orderBy('cards.created_at', 'desc')
        ->select([
            'cards.card_serial',
            'cards.card_number',
            'cards.book_id',
            'companies.name as companies_name',
            'cardstautes.name as cardstautes_name',
            'requests.request_number',
            'cards.created_at',
                           'issuings.issuing_date as issuing_datee',

        ])
        ->get();

    $user = auth()->user();

    return view('dashbord.card.soldpdf', compact('cards', 'user'));
}

    public function indexsold()
    {
        ActivityLogger::activity("عرض كافة البطاقات المصدرة");

        return view('dashbord.card.indexsold');
    }
   public function cardallsold()
{
    $query = DB::table('cards')
        ->leftJoin('companies', 'cards.companies_id', '=', 'companies.id')
        ->leftJoin('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
        ->leftJoin('requests', 'cards.requests_id', '=', 'requests.id')
                ->leftJoin('issuings', 'cards.id', '=', 'issuings.cards_id') // 
        ->where('cards.cardstautes_id', 2)
        ->select([
            'cards.id',
            'cards.card_serial',
            'cards.card_number',
            'cards.book_id',
            'companies.name as companies_name',
            'cardstautes.name as cardstautes_name',
            'requests.request_number',
            'cards.created_at',
               'issuings.issuing_date as issuing_datee',
        ])
        ->orderBy('cards.created_at', 'desc');

    // Handle pagination for DataTables
    $draw = request()->get('draw');
    $start = request()->get("start", 0);
    $length = request()->get("length", 10);

    $totalRecords = $query->count();

    $records = $query->skip($start)->take($length)->get();

    $data = $records->map(function ($card, $index) use ($start) {
        return [
            'id' => $start + $index + 1,
            'card_serial' => $card->card_serial ?? 'N/A',
             'issuing_datee' => $card->issuing_datee ?? 'N/A',
            'card_number' => $card->card_number ?? 'N/A',
            'book_id' => $card->book_id ?? 'N/A',
            'companies_name' => $card->companies_name ?? 'الإتحاد الليبي للتأمين',
            'cardstautes_name' => $card->cardstautes_name ?? 'غير محدد',
            'request_number' => $card->request_number ?? 'غير متوفر',
            'created_at' => $card->created_at ? \Carbon\Carbon::parse($card->created_at)->format('Y-m-d H:i') : 'N/A',
        ];
    });

    return response()->json([
        'draw' => intval($draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $data,
    ]);
}



    public function indexcancel()
    {
        ActivityLogger::activity("عرض كافة البطاقات الملغية");

        return view('dashbord.card.indexcancel');
    }




public function indexcancelpdf()
{
    $cards = DB::table('cards')
        ->leftJoin('companies', 'cards.companies_id', '=', 'companies.id')
        ->leftJoin('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
        ->leftJoin('requests', 'cards.requests_id', '=', 'requests.id')
             ->leftJoin('issuings', 'issuings.cards_id', '=', 'cards.id')
        ->where('cards.cardstautes_id', 3)
        ->orderBy('cards.created_at', 'desc')
        ->select([
            'cards.card_serial',
            'cards.card_number',
            'cards.book_id',
            'companies.name as companies_name',
            'cardstautes.name as cardstautes_name',
            'requests.request_number',
            'cards.created_at',
            'cards.card_delete_date',
            'issuings.created_at as issuing_date'    
        ])
        ->get();

    $user = auth()->user();

    return view('dashbord.card.cancel_pdf', compact('cards', 'user'));
}



public function cardallcancel()
{
    $cards = DB::table('cards')
        ->leftJoin('companies', 'cards.companies_id', '=', 'companies.id')
        ->leftJoin('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
        ->leftJoin('requests', 'cards.requests_id', '=', 'requests.id')
         ->leftJoin('issuings', 'issuings.cards_id', '=', 'cards.id')
        ->where('cards.cardstautes_id', 3)
        ->orderBy('cards.created_at', 'desc')
        ->select([
            'cards.id',
            'cards.card_serial',
            'cards.card_number',
            'cards.book_id',
            'companies.name as companies_name',
            'cardstautes.name as cardstautes_name',
            'requests.request_number',
            'cards.created_at',
            'cards.card_delete_date',
            'issuings.created_at as issuing_date'        ])
        ->get();

    return response()->json([
        'draw' => request()->get('draw'),
        'recordsTotal' => $cards->count(),
        'recordsFiltered' => $cards->count(),
        'data' => $cards
    ]);
}




    public function indexactive()
    {
        ActivityLogger::activity("عرض كافة  البطاقة المعينة");

        return view('dashbord.card.indexactive');
    }



public function printActiveCards()
{
    $cards = Card::with(['companies', 'cardstautes', 'requests'])
        ->where('cardstautes_id', 1)
        ->orderBy('created_at', 'DESC')
        ->get();

    $user = auth()->user();

    return view('dashbord.card.active_print', compact('cards', 'user'));
}
 public function cardallactive()
{
    $cards = Card::with(['companies', 'cardstautes', 'requests'])
        ->where('cardstautes_id', 1)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

    $formattedCards = $cards->getCollection()->transform(function ($card) {
        return [
            'id' => $card->id,
            'card_serial' => $card->card_serial ?? 'N/A',
            'card_number' => $card->card_number ?? 'N/A',
            'book_id' => $card->book_id ?? 'N/A',
            'uploded_datetime'=>$card->requests->uploded_datetime ?? 'N/A',
            'companies_name' => $card->companies->name ?? 'لدي الاتحاد',
            'cardstautes_name' => $card->cardstautes->name ?? 'غير معروف',
            'request_number' => $card->requests->request_number ?? 'غير متوفر',
'created_at' => \Carbon\Carbon::parse($card->created_at)->format('Y-m-d H:i'),        ];
    });

    return response()->json([
        'draw' => request()->get('draw'),
        'recordsTotal' => $cards->total(),
        'recordsFiltered' => $cards->total(),
        'data' => $formattedCards
    ]);
}

   




    public function indexinactive()
    {
        ActivityLogger::activity("عرض كافة  البطاقة متبقية");

        return view('dashbord.card.indexinactive');
    }


public function printInactiveCards()
{
    $cards = Card::with(['companies', 'cardstautes', 'requests'])
        ->where('cardstautes_id', 0)
        ->orderBy('created_at', 'DESC')
        ->get();

    $user = auth()->user();

    return view('dashbord.card.inactive_pdf', compact('cards', 'user'));
}
   public function cardallinactive()
{
    $query = DB::table('cards')
        ->leftJoin('companies', 'cards.companies_id', '=', 'companies.id')
        ->leftJoin('cardstautes', 'cards.cardstautes_id', '=', 'cardstautes.id')
        ->leftJoin('requests', 'cards.requests_id', '=', 'requests.id')
        ->where('cards.cardstautes_id', 0)
        ->select([
            'cards.id',
            'cards.card_serial',
            'cards.card_number',
            'cards.book_id',
            'companies.name as companies_name',
            'cardstautes.name as cardstautes_name',
            'requests.request_number',
            'cards.created_at'
        ])
        ->orderBy('cards.created_at', 'desc');

    // DataTables pagination
    $draw = request()->get('draw');
    $start = request()->get('start', 0);
    $length = request()->get('length', 10);

    $totalRecords = $query->count();
    $records = $query->skip($start)->take($length)->get();

    $data = $records->map(function ($card, $index) use ($start) {
        return [
            'id' => $start + $index + 1,
            'card_serial' => $card->card_serial ?? 'N/A',
            'card_number' => $card->card_number ?? 'N/A',
            'book_id' => $card->book_id ?? 'N/A',
            'companies_id' => $card->companies_name ?? 'الإتحاد الليبي للتأمين',
            'cardstautesname' => $card->cardstautes_name ?? 'غير محدد',
            'request_numberr' => $card->request_number ?? 'غير متوفر',
            'created_at' => $card->created_at ? \Carbon\Carbon::parse($card->created_at)->format('Y-m-d H:i') : 'N/A',
        ];
    });

    return response()->json([
        'draw' => intval($draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $data,
    ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        //
    }




    /**
     * Remove the specified resource from storage.
     */
    public function replaceNumbers()
    {
        try {
            // Retrieve cards with specified statuses
            $cards = Card::whereIn('cardstautes_id', [0, 1])->get();

            foreach ($cards as $card) {

                $year = date('Y');
                $cardnumber = $card->card_number;
                $newNumber = substr($year, -2);

                // Define pattern and replacement
                $pattern = '/\/\d+\//'; // Matches the pattern "/number/"
                $replacement = "/$newNumber/";

                // Replace the pattern in the card number
                $updatedNumber = preg_replace($pattern, $replacement, $cardnumber);

                // Debugging: Log the card number before and after the update
                Log::info("Card Number Before Update: " . $cardnumber);
                Log::info("Card Number After Update: " . $updatedNumber);

                // Check if the card number is updated
                if ($updatedNumber === null) {
                    throw new \Exception('Failed to replace pattern in card number: ' . $cardnumber);
                }

                // Update the card number and save
                // dd($updatedNumber);

                $card->card_number = $updatedNumber;
                $card->save();
            }

            // Successful update
            Alert::success("تمت عملية تحديث البطاقات ");
            ActivityLogger::activity("تمت عملية تحديث البطاقات ");

            return redirect()->route('home');
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error updating card numbers: " . $e->getMessage());

            // Error response
            Alert::warning("فشل تحديث البطاقات ");
            ActivityLogger::activity($e->getMessage() . "فشل تحديث  البطاقات");

            return redirect()->route('home');
        }
    }
}
