<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير بحث بواسطة</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        #report-header .row { display: flex; justify-content: space-between; }
        #report-header p, #report-header h4 { margin: 0; }
    </style>
</head>
<body>

<div id="report-header">
    <div class="row">
        <div style="flex:1;">
            <p>العنوان: الإتحاد الليبي للتأمين</p>
            <p><a href="mailto:info@insurancefed.ly">info@insurancefed.ly</a></p>
            <p><a href="http://www.insurancefed.ly">www.insurancefed.ly</a></p>
        </div>
        <div style="flex:1; text-align: center;">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 100px;">
            <h4>دولــة لـيـبـيـا</h4>
            <h4>الاتـــحـاد الليبي للتأمين</h4>
        </div>
        <div style="flex:1; text-align: right;">
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
            <p>المستخدم: {{ $user->username ?? 'غير معروف' }}</p>
        </div>
    </div>
</div>

<hr>

<h3 style="text-align: center;">تقرير بحث بواسطة</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>رقم البطاقة</th>
            
            <th>الحالة</th>
            <th>رقم الطلب</th>
            <th>تاريخ </th>
        </tr>
    </thead>
    <tbody>
        @foreach($cards as $index => $card)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $card->card_number }}</td>
        <td>{{ $card->cardstautes->name ?? '-' }}</td>
        <td>{{ $card->requests->request_number ?? '-' }}</td>
        <td>
            @php
    switch ((int) $card->cardstautes_id) {
        case 0:
            $date = $card->created_at;
            break;
        case 1:
            $date = $card->requests?->uploded_datetime;
            break;
        case 2:
            $date = $card->issuing?->issuing_date;
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

<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
