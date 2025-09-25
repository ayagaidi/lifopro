<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير اجمالي المبيعات</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            margin: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ba8173;
            color: white;
            font-weight: bold;
        }

        #report-header .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #report-header div {
            flex: 1;
            padding: 10px;
        }

        #report-header h4, #report-header p {
            margin: 5px 0;
        }

        .summary {
            font-size: 16px;
            font-weight: bold;
            color: #444;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div id="report-header">
    <div class="row">
        <div>
            <p>العنوان: الإتحاد الليبي للتأمين</p>
            <p><a href="mailto:info@insurancefed.ly">info@insurancefed.ly :البريد الالكتروني</a></p>
            <p><a href="http://www.insurancefed.ly">www.insurancefed.ly: الموقع الالكتروني</a></p>
        </div>
        <div style="text-align: center;">
            <img src="{{ asset('logo.png') }}" alt="Report Logo" style="width: 80px; margin-bottom: 10px;">
            <h4>دولــة لـيـبـيـا</h4>
            <h4>الاتـــحـاد الليبي للتأمين</h4>
        </div>
        <div style="text-align: right;">
            <p>تاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
            <p>تم الإنشاء بواسطة: {{ $user->username ?? 'غير معروف' }}</p>
            <p>تقرير اجمالي المبيعات</p>
        </div>
    </div>
</div>

<div class="summary">
    اجمالي مبيعات البطاقات: {{ number_format($insurance_total, 2) }} د.ل
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>الشركة</th>
            <th>اجمالي المبيعات (د.ل)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($insurance_totalt as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->companies_name }}</td>
                <td>{{ number_format($item->total_insurance, 2) }} د.ل</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">لا يوجد بيانات</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>
