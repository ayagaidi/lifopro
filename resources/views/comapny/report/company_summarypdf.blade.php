<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير ملخص للمكاتب</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; }
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
            <p><a href="http://www.insurancefed.ly">www.insurancefed.ly :الموقع الالكتروني</a></p>
        </div>
        <div style="flex:1; text-align: center;">
            <img src="{{ asset('logo.png') }}" alt="Report Logo" style="width: 100px;">
            <h4>دولــة لـيـبـيـا</h4>
            <h4>الاتـــحـاد الليبي للتأمين</h4>
        </div>
        <div style="flex:1; text-align: right;">
            <p>تاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
               <p style="font-weight: bold">الشركة : {{ Auth::user()->companies->name }}</p>

                                <p style="font-weight: bold"> <span id="report-creator">
                                        تم إنشاؤه من قبل</span>
                                    :{{ Auth::user()->username }}</p>
            
            <p>تقرير ملخص للمكاتب</p>
            <div class="search-params">
                @if($startDate)
                    <span>من: {{ $startDate }}</span>
                @endif
                @if($endDate)
                    <span>إلى: {{ $endDate }}</span>
                @endif
               
            </div>
        </div>
    </div>
</div>

<hr>

@if($data->isEmpty())
    <p style="text-align:center; margin-top: 20px;">لا توجد بيانات لعرضها</p>
@else
    <table>
        <thead>
            <tr>
                        <th>المكتب</th>
                <th>البطاقات المصدرة</th>
                <th>البطاقات الملغية</th>
                <th>صافي القسط</th>
                <th>الضريبة</th>
                <th>الطابع</th>
                <th>الإشراف</th>
                <th>رسوم الإصدار</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
                <tr>
                            <td>{{ $row->office_name }}</td>


                            <td>{{ $row->issued_count }}</td>
                    <td>{{ $row->canceled_count }}</td>
                    <td>{{ number_format($row->net_premium, 2) }}</td>
                    <td>{{ number_format($row->tax, 2) }}</td>
                    <td>{{ number_format($row->stamp, 2) }}</td>
                    <td>{{ number_format($row->supervision, 2) }}</td>
                    <td>{{ number_format($row->issuing_fee, 2) }}</td>
                    <td>{{ number_format($row->total, 2) }}</td>
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
