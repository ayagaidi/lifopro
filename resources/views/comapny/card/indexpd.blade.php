<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير البطاقات</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        #report-header .row { display: flex; justify-content: space-between; }
        #report-header p, #report-header h4 { margin: 0; }
    </style>
</head>
<body>

    <div id="report-header">
        <div class="row">
            <div style="flex:1;">
                <p style="font-weight: bold">العنوان: الإتحاد الليبي للتأمين</p>
                <p style="font-weight: bold">
                    <a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a>
                </p>
                <p style="font-weight: bold">
                    <a href="http://www.insurancefed.ly">www.insurancefed.ly: الموقع الالكتروني</a>
                </p>
            </div>
            <div style="flex:1; text-align: center;">
                <img src="{{ asset('logo.png') }}" alt="Report Image" style="width: 100px;">
                <h4 style="font-weight: bold">دولــة لـيـبـيـا</h4>
                <h4 style="font-weight: bold">الاتـــحـاد الليبي للتأمين</h4>
            </div>
            <div style="flex:1; text-align: right;">
                <p style="font-weight: bold">وقت وتاريخ الانشاء: {{ date('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    <hr>

    <table>
        <thead>
            <tr>
                <th>رقم البطاقة</th>
                <th>إسم الشركة</th>
                <th>اسم المكتب</th>
                <th>حالة البطاقة</th>
                <th>رقم الطلب</th>
                <th>تاريخ ادراج البطاقة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cards as $card)
                <tr>
                    <td>{{ $card->card_number }}</td>
                    <td>{{ $card->companies ? $card->companies->name : 'الإتحاد الليبي للتأمين' }}</td>
                    <td>{{ $card->offices ? $card->offices->name : 'لدي الشركة' }}</td>
                    <td>{{ $card->cardstautes ? $card->cardstautes->name : '' }}</td>
                    <td>{{ $card->requests ? $card->requests->request_number : '' }}</td>
                    <td>{{ $card->card_insert_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>
