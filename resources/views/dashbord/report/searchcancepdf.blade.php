<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير البطاقات الملغية</title>
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
            <p>وقت وتاريخ الانشاء: {{ date('Y-m-d H:i') }}</p>
          
            <p>التقرير بواسطة: {{ $user->username ?? 'غير معروف'}}</p>
            <p>تقرير البطاقات الملغية</p>

<div class="search-params">
    @if(!empty($filters))
        @php
            $companyName = null;
            if(!empty($filters['companies_id']) && $filters['companies_id'] !== "0") {
                $comp = \App\Models\Company::find($filters['companies_id']);
                if($comp) $companyName = $comp->name;
            } elseif(isset($filters['companies_id']) && $filters['companies_id'] === "0") {
                $companyName = 'بدون شركة';
            } elseif(isset($filters['companies_id'])) {
                $companyName = 'الإتحاد الليبي للتأمين';
            }
        @endphp

        @if(!empty($companyName))
            <span>الشركة: {{ $companyName }}</span>
        @endif

        @if(!empty($filters['request_number']))
            <span>رقم الطلب: {{ $filters['request_number'] }}</span>
        @endif

        @if(!empty($filters['card_number']))
            <span>رقم البطاقة: {{ $filters['card_number'] }}</span>
        @endif

        @if(!empty($filters['fromdate']))
            <span>من تاريخ: {{ $filters['fromdate'] }}</span>
        @endif

        @if(!empty($filters['todate']))
            <span>إلى تاريخ: {{ $filters['todate'] }}</span>
        @endif
    @endif
</div>
        </div>
    </div>
</div>

<hr>

@if($cards->isEmpty())
    <p style="text-align:center; margin-top: 20px;">لا توجد بطاقات لعرضها</p>
@else
    <table>
        <thead>
            <tr>
                <th>م</th>
               
                <th>رقم البطاقة</th>
                <th>الشركة</th>
                <th>حالة البطاقة</th>
                <th>رقم الطلب</th>
                  <th> تاريخ اصدار البطاقة </th>
                <th>تاريخ الغاء البطاقة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cards as $index => $card)
                <tr>
                    <td>{{ $index + 1 }}</td>
                   
                    <td>{{ $card->card_number }}</td>
                    <td>{{ $card->companies ? $card->companies->name : 'الإتحاد الليبي للتأمين' }}</td>
                    <td>{{ $card->cardstautes->name }}</td>
                    <td>{{ $card->requests ? $card->requests->request_number : '---' }}</td>
                                        <td>{{ $card->issuing->issuing_date }}</td>

                    <td>{{ $card->card_delete_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>
