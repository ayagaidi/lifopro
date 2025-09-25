<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير البطاقات</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        #report-header .row { display: flex; justify-content: space-between; }
        #report-header p, #report-header h4 { margin: 0; }
        .search-params { margin-top: 10px; font-weight: bold; }
        .search-params span { margin-left: 20px; }
    </style>
</head>
<body>

<div id="report-header">
    <div class="row">
        <div style="flex:1;">
            <p>العنوان: الإتحاد الليبي للتأمين</p>
            <p><a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a></p>
            <p><a href="http://www.insurancefed.ly">www.insurancefed.ly: الموقع الالكتروني</a></p>
        </div>
        <div style="flex:1; text-align: center;">
            <img src="{{ asset('logo.png') }}" alt="Report Image" style="width: 100px;">
            <h4>دولــة لـيـبـيـا</h4>
            <h4>الاتـــحـاد الليبي للتأمين</h4>
        </div>
        <div style="flex:1; text-align: right;">
            <p>وقت وتاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
            <p>التقرير بواسطة: {{ $user->fullname ?? 'غير معروف' }}</p>         </div>
    </div>
</div>

<hr>

<div class="search-params">
    @php
        $filters = $filters ?? [];
        $companyName = '-';
        if (!empty($filters['companies_id']) && $filters['companies_id'] !== "0") {
            $company = \App\Models\Company::find($filters['companies_id']);
            $companyName = $company ? $company->name : '-';
        } elseif (isset($filters['companies_id']) && $filters['companies_id'] === "0") {
            $companyName = 'الإتحاد الليبي للتأمين';
        }

        $statusName = '-';
        if (!empty($filters['cardstautes_id'])) {
            $status = \App\Models\Cardstautes::find($filters['cardstautes_id']);
            $statusName = $status->name ?? '-';
        }
    @endphp

    <span>الشركة: {{ $companyName }}</span>
    <span>الحالة: {{ $statusName }}</span>
    @if(!empty($filters['request_number']))
        <span>رقم الطلب: {{ $filters['request_number'] }}</span>
    @endif
    @if(!empty($filters['card_number']))
        <span>رقم البطاقة: {{ $filters['card_number'] }}</span>
    @endif
    @if(!empty($filters['fromdate']))
        <span>من: {{ $filters['fromdate'] }}</span>
    @endif
    @if(!empty($filters['todate']))
        <span>إلى: {{ $filters['todate'] }}</span>
    @endif
</div>

@if($cards->isEmpty())
    <p style="text-align: center; margin-top: 20px;">لا توجد نتائج لعرضها.</p>
@else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم البطاقة</th>
                <th>الشركة</th>
                <th>الحالة</th>
                <th>رقم الطلب</th>
                <th> التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cards as $index => $card)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $card->card_number }}</td>
                    <td>{{ $card->companies->name ?? 'الإتحاد الليبي للتأمين' }}</td>
                    <td>{{ $card->cardstautes->name ?? '-' }}</td>
                    <td>{{ $card->requests->request_number ?? '-' }}</td>
                         <td>
            @php
                switch ((int) $card->cardstautes_id) {
                    case 0:
                        $date = $card->created_at;
                        break;
                    case 1:
                        $date = $card->requests->uploded_datetime;
                        break;
                    case 2:
                        $date = $card->issuing_date;
                        break;
                    case 3:
                        $date = $card->card_delete_date;
                        break;
                    default:
                        $date = null;
                }
            @endphp

            {{ $date ? \Carbon\Carbon::parse($date)->format('Y-m-d H:i') : '-' }}
        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>
