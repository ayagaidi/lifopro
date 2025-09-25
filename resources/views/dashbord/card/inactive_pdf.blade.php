<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير البطاقات المتبقية</title>
    <style>
        body { font-family: 'Arial', sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        .header { display: flex; justify-content: space-between; }
        .header div { width: 32%; }
        .header h4, .header p { margin: 0; padding: 0; }
        .logo { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <p>العنوان: الإتحاد الليبي للتأمين</p>
        <p><a href="mailto:info@insurancefed.ly">info@insurancefed.ly</a></p>
        <p><a href="http://www.insurancefed.ly">www.insurancefed.ly</a></p>
    </div>
    <div class="logo">
        <img src="{{ asset('logo.png') }}" alt="Logo" width="100">
        <h4>دولــة لـيـبـيـا</h4>
        <h4>الاتـــحـاد الليبي للتأمين</h4>
    </div>
    <div style="text-align:right;">
        <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        <p>المستخدم: {{ $user->username ?? 'غير معروف' }}</p>         <p>تقرير البطاقات المتبقية</p>
    </div>
</div>

<hr>

<table>
    <thead>
        <tr style="background-color:#ccc;">
         
            <th>رقم البطاقة</th>
            <th>الشركة</th>
            <th>الحالة</th>
            <th>رقم الطلب</th>
            <th>تاريخ الإدراج</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cards as $index => $card)
            <tr>
         
                <td>{{ $card->card_number ?? 'N/A' }}</td>
                <td>{{ $card->companies->name ?? 'الإتحاد الليبي للتأمين' }}</td>
                <td>{{ $card->cardstautes->name ?? 'غير معروف' }}</td>
                <td>{{ $card->requests->request_number ?? 'غير متوفر' }}</td>
                <td>{{ \Carbon\Carbon::parse($card->created_at)->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">لا توجد بيانات بطاقات متبقية</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>
