<?php 
namespace App\Exports;

use App\Models\issuing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Exportable;

class IssuingsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    use Exportable;

    public function __construct(protected Request $request) {
        
    }

    public function query()
    {
        $from = Carbon::parse($this->request->fromdate)->startOfDay();
        $to   = Carbon::parse($this->request->todate)->endOfDay();

        $q = Issuing::query()
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
                'id','cards_id','companies_id','offices_id','company_users_id','office_users_id',
                'insurance_name','issuing_date','insurance_installment','insurance_tax',
                'insurance_stamp','insurance_supervision','insurance_version','insurance_total',
                'insurance_day_from','nsurance_day_to','insurance_days_number','plate_number',
                'chassis_number','motor_number','cars_id','created_at'
            ])
            ->whereBetween('issuing_date', [$from, $to]);

        // نفس الفلاتر:
        $req = $this->request;
        $q->when($req->filled('companies_id'), function ($q2) use ($req) {
            $q2->where(function ($sub) use ($req) {
                $sub->where('companies_id', $req->companies_id)
                    ->orWhereHas('offices', fn($q3) => $q3->where('companies_id', $req->companies_id));
            });
        });
        $q->when($req->filled('offices_id'), fn($x) => $x->where('offices_id', $req->offices_id));
        $q->when($req->filled('company_users_id'), fn($x) => $x->where('company_users_id', $req->company_users_id));
        $q->when($req->filled('office_users_id'), fn($x) => $x->where('office_users_id', $req->office_users_id));
        $q->when($req->filled('card_number'), function ($x) use ($req) {
            $x->whereHas('cards', fn($q4) => $q4->where('card_number', $req->card_number));
        });
        $q->when($req->filled('insurance_name'), fn($x) => $x->where('insurance_name', 'like', '%'.$req->insurance_name.'%'));
        $q->when($req->filled('plate_number'), fn($x) => $x->where('plate_number', $req->plate_number));
        $q->when($req->filled('chassis_number'), fn($x) => $x->where('chassis_number', $req->chassis_number));

        return $q->orderBy('created_at','asc');
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم البطاقة',
            'المُصدر',
            'الشركة',
            'المكتب',
            'المؤمن له',
            'تاريخ الاصدار',
            'صافي القسط',
            'الضريبة',
            'رسم الدمغة',
            'الإشراف',
            'الإصدار',
            'الإجمالي',
            'مدة التأمين من',
            'مدة التأمين إلى',
            'عدد الأيام',
            'نوع المركبة',
            'رقم اللوحة',
            'رقم الهيكل',
            'رقم المحرك',
        ];
    }

    protected int $row = 0;

    public function map($item): array
    {
        $this->row++;

        $companies = 'الإتحاد الليبي للتأمين';
        $offices   = 'الفرع الرئيسي';
        $user      = '-';

        if ($item->offices) {
            $offices   = $item->offices->name ?? $offices;
            $companies = $item->offices->companies->name ?? $companies;
        } elseif ($item->companies) {
            $companies = $item->companies->name ?? $companies;
        }

        if ($item->office_users_id && $item->office_users) {
            $user = $item->office_users->username ?? $user;
        } elseif ($item->company_users_id && $item->company_users) {
            $user = $item->company_users->username ?? $user;
        }

        $card = $item->cards?->card_number ?? $item->id;
        $car  = $item->cars?->name ?? $item->cars_id ?? '-';

        return [
            $this->row,
            $card,
            $user,
            $companies,
            $offices,
            $item->insurance_name ?? '-',
            $item->issuing_date ?? '-',
            (float)($item->insurance_installment ?? 0),
            (float)($item->insurance_tax ?? 0),
            (float)($item->insurance_stamp ?? 0),
            (float)($item->insurance_supervision ?? 0),
            (float)($item->insurance_version ?? 0),
            (float)($item->insurance_total ?? 0),
            $item->insurance_day_from ?? '-',
            $item->nsurance_day_to ?? '-', // ← تأكد من تصحيح اسم الحقل في select
            $item->insurance_days_number ?? '-',
            $car,
            $item->plate_number ?? '-',
            $item->chassis_number ?? '-',
            $item->motor_number ?? '-',
        ];
    }

    public function chunkSize(): int
    {
        return 2000; // مناسب لأحجام كبيرة
    }
}
