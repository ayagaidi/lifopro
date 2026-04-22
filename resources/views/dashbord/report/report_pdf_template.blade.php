<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تقرير إصدار وثيقة التأمين - رقم {{ $report->id }}</title>
<style type="text/css">
@page {
    size: A4;
    margin: 10px;
}

body {
    font-family: arial, tahoma, sans-serif;
    font-size: 12px;
    direction: rtl;
    text-align: right;
    margin: 0;
    padding: 10px;
    background-color: #f0f0f0;
}

.container {
    width: 100%;
    max-width: 800px;
    margin: auto;
    background-color: #fff;
    border: 2px solid #000;
    padding: 15px;
    position: relative;
}

.header-top {
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
    color: #000;
}

.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1a73e8;
}

.header-center {
    text-align: center;
    flex: 1;
}

.header-center h1 {
    margin: 0;
    font-size: 20px;
    color: #1a73e8;
}

.header-center h2 {
    margin: 5px 0;
    font-size: 14px;
    color: #333;
}

.header-center h3 {
    margin: 5px 0;
    font-size: 16px;
    color: #666;
}

.logo-img {
    width: 80px;
    height: auto;
}

.logo-left {
    width: 80px;
    text-align: left;
}

.logo-right {
    width: 80px;
    text-align: right;
}

.contact-info-wrapper {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    gap: 10px;
}

.contact-box {
    width: 48%;
    border: 1px solid #000;
    padding: 8px;
    font-size: 11px;
    line-height: 1.4;
}

.contact-box strong {
    color: #000;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
    font-size: 11px;
}

th, td {
    border: 1px solid #000;
    padding: 5px 8px;
    text-align: right;
}

th {
    background-color: #efefef;
    font-weight: bold;
}

.bg-gray {
    background-color: #efefef;
    font-weight: bold;
}

.info-row {
    display: table-row;
}

.info-label {
    display: table-cell;
    background-color: #f5f5f5;
    font-weight: bold;
    width: 25%;
    border: 1px solid #ccc;
    padding: 6px 10px;
}

.info-value {
    display: table-cell;
    border: 1px solid #ccc;
    padding: 6px 10px;
}

.section-title {
    background: #1a73e8;
    color: white;
    padding: 8px 12px;
    font-size: 13px;
    margin: 15px 0 8px 0;
    text-align: center;
}

.amount {
    font-weight: bold;
    color: #1a73e8;
    font-size: 13px;
}

.footer {
    margin-top: 20px;
    text-align: center;
    font-size: 10px;
    color: #666;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

.signature-table {
    width: 100%;
    margin-top: 30px;
    border-top: 1px solid #ddd;
}

.signature-table td {
    width: 50%;
    text-align: center;
    padding: 10px;
}

.signature-line {
    border-top: 1px solid #333;
    margin-top: 30px;
    padding-top: 5px;
    font-size: 10px;
}

.warning {
    color: red;
    font-weight: bold;
    margin-top: 10px;
    text-align: center;
    font-size: 11px;
}
</style>
</head>
<body>

<div class="container">
    <div class="main-header">
        <div class="logo-left">
            <img class="logo-img" src="{{ public_path('logo.png') }}" alt="Logo">
        </div>
        <div class="header-center">
            <h1>تقرير إصدار وثيقة التأمين</h1>
            <h2>الإتحاد الليبي للتأمين</h2>
            <h3>رقم التقرير: {{ $report->id }}</h3>
        </div>
        <div class="logo-right">
            <img class="logo-img" src="{{ public_path('gaif-logo.png') }}" alt="Logo">
        </div>
    </div>

    <div class="contact-info-wrapper">
        <div class="contact-box">
            <strong>تاريخ الطباعة:</strong> {{ $generated_at }}<br>
            <strong>طبعة بواسطة:</strong> {{ $user }}
        </div>
        <div class="contact-box">
            <strong>الشركة:</strong> {{ $report->companies->name ?? 'غير متوفر' }}<br>
            <strong>المكتب:</strong> {{ $report->offices->name ?? 'غير متوفر' }}
        </div>
    </div>

    <div class="section-title">معلومات المؤمن له</div>
    <table>
        <tr>
            <th class="bg-gray" width="20%">رقم البطاقة</th>
            <td width="30%">{{ $report->cards->card_number ?? 'غير متوفر' }}</td>
            <th class="bg-gray" width="20%">اسم المؤمن له</th>
            <td>{{ $report->insurance_name ?? 'غير متوفر' }}</td>
        </tr>
        <tr>
            <th class="bg-gray">رقم اللوحة</th>
            <td>{{ $report->plate_number ?? 'غير متوفر' }}</td>
            <th class="bg-gray">رقم الهيكل</th>
            <td>{{ $report->chassis_number ?? 'غير متوفر' }}</td>
        </tr>
        <tr>
            <th class="bg-gray">رقم المحرك</th>
            <td>{{ $report->motor_number ?? 'غير متوفر' }}</td>
            <th class="bg-gray">تاريخ الإصدار</th>
            <td>{{ $report->issuing_date ? \Carbon\Carbon::parse($report->issuing_date)->format('Y-m-d') : 'غير متوفر' }}</td>
        </tr>
        <tr>
            <th class="bg-gray">نوع السيارة</th>
            <td>{{ $report->cars->name ?? 'غير متوفر' }}</td>
            <th class="bg-gray">جنسية السيارة</th>
            <td>{{ $report->countries->name ?? 'غير متوفر' }}</td>
        </tr>
    </table>

    <div class="section-title">تفاصيل المبالغ</div>
    <table>
        <tr>
            <th class="bg-gray" width="50%">البند</th>
            <th class="bg-gray" width="50%">المبلغ (د.ل)</th>
        </tr>
        <tr>
            <td>القسط</td>
            <td>{{ number_format($report->insurance_installment ?? 0, 3) }}</td>
        </tr>
        <tr>
            <td>الضريبة</td>
            <td>{{ number_format($report->insurance_tax ?? 0, 3) }}</td>
        </tr>
        <tr>
            <td>الدمغة</td>
            <td>{{ number_format($report->insurance_stamp ?? 0, 3) }}</td>
        </tr>
        <tr>
            <td>رسوم الإشراف</td>
            <td>{{ number_format($report->insurance_supervision ?? 0, 3) }}</td>
        </tr>
        <tr>
            <td>رسوم الإصدار</td>
            <td>{{ number_format($report->insurance_version ?? 0, 3) }}</td>
        </tr>
        <tr>
            <td class="bg-gray">الإجمالي</td>
            <td class="amount">{{ number_format($report->insurance_total ?? 0, 3) }}</td>
        </tr>
    </table>

    <div class="section-title">فترة التأمين</div>
    <table>
        <tr>
            <th class="bg-gray" width="25%">من تاريخ</th>
            <td width="25%">{{ $report->insurance_day_from ? \Carbon\Carbon::parse($report->insurance_day_from)->format('Y-m-d') : 'غير متوفر' }}</td>
            <th class="bg-gray" width="25%">إلى تاريخ</th>
            <td>{{ $report->nsurance_day_to ? \Carbon\Carbon::parse($report->nsurance_day_to)->format('Y-m-d') : 'غير متوفر' }}</td>
        </tr>
        <tr>
            <th class="bg-gray">عدد الأيام</th>
            <td colspan="3">{{ $report->insurance_days_number ?? 0 }}</td>
        </tr>
    </table>

    <div class="section-title">معلومات إضافية</div>
    <table>
        <tr>
            <th class="bg-gray" width="25%">اسم المستخدم</th>
            <td>{{ $report->company_users->username ?? $report->office_users->username ?? 'غير متوفر' }}</td>
            <th class="bg-gray" width="25%">تاريخ الإنشاء</th>
            <td>{{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->format('Y-m-d H:i') : 'غير متوفر' }}</td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>الإتحاد الليبي للتأمين - Libya Insurance Federation</strong></p>
        <p>البريد الإلكتروني: info@insurancefed.ly | الموقع: www.insurancefed.ly</p>
        <p>هذا التقرير مُولد إلكترونياً</p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line">التوقيع والختم</div>
            </td>
            <td>
                <div class="signature-line">المدير المسؤول</div>
            </td>
        </tr>
    </table>

    <div class="warning">
        هام: أي كشط أو شطب أو تعديل في هذا التقرير يبطله
    </div>
</div>

</body>
</html>