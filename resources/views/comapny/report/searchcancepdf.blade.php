<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>البطاقات</title>
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
                <p>تقرير البطاقات الملغية</p>

                {{-- عرض معايير البحث --}}
                <div class="search-params">
                    @if($searchParams['request_number'])
                        <span>رقم الطلب: {{ $searchParams['request_number'] }}</span>
                    @endif
                    @if($searchParams['card_number'])
                        <span>رقم البطاقة: {{ $searchParams['card_number'] }}</span>
                    @endif
                    @if($searchParams['fromdate'] && $searchParams['todate'])
                        <span>الفترة من: {{ $searchParams['fromdate'] }} إلى: {{ $searchParams['todate'] }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr>

    @if($cards->isEmpty())
        <p style="text-align:center; font-weight:bold; margin-top: 50px;">لا توجد بطاقات ملغية لعرضها</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>رقم البطاقة</th>
                    <th>الشركة</th>
                    <th>حالة البطاقة</th>
                    <th>رقم الطلب</th>
                                        <th>تاريخ اصدار البطاقة </th>

                    <th>تاريخ الغاء البطاقة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cards as $index => $card)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                      
                        <td>{{ $card->card_number }}</td>
                        <td>{{ $card->companies ? $card->companies->name : 'الإتحاد الليبي للتأمين' }}</td>
                        <td>{{ $card->cardstautes->name ?? '' }}</td>
                        <td>{{ $card->requests->request_number ?? '' }}</td>
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
