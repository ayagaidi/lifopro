<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: amiri; font-size: 11pt; }
    table { width:100%; border-collapse: collapse; table-layout: fixed; }
    th, td { border:1px solid #000; padding:4px; word-wrap: break-word; }
    thead { display: table-header-group; }
    tfoot { display: table-row-group; }
    tr { page-break-inside: avoid; }
    h3 { text-align:center; margin: 0 0 8px 0; }
    .header { width:100%; margin-bottom: 10px; }
    .header td { border: none; }
    .logo { width:120px; }
    .meta { font-size: 10pt; text-align: left; }
  </style>
</head>
<body>
  <table class="header">
    <tr>
      
      <td style="text-align:center">
          <h3>العنوان:الإتحاد الليبي للتأمين</h3>
                   <h3>البريد: info@insurancefed.ly</h3>
                   <h3>الموقع: www.insurancefed.ly</h3>

    
      </td>
      <td class="logo" style="text-align:right">
        {{-- change path to your logo file --}}
        <img src="{{ asset('logo.png') }}" style="height:100px;">
      </td>
      <td class="text-align:center">
                  <h3>التقارير من {{ $meta['from'] }} إلى {{ $meta['to'] }}</h3>

        <div>المستخدم: {{ $meta['username'] }}</div>
        <div>تاريخ الطباعة: {{ $meta['today'] }}</div>
      </td>
    </tr>
  </table>

  <table>
    <thead>
      <tr>
          <th style="width:1%">#</th>
                  <th style="width:9%">رقم البطاقة</th>

        <th style="width:11%">التاريخ</th>
        <th style="width:16%"> الشركة/المكتب</th>
        <th style="width:10%">اسم المؤمن </th>
                <th style="width:10%">رقم اللوحة</th>

        <th style="width:16%">رقم الهيكل</th>
        <th style="width:9%">القسط</th>
        <th style="width:9%">الضريبة</th>
        <th style="width:9%">الدمغة</th>
        <th style="width:9%">الإشراف</th>
        <th style="width:9%">الإصدار</th>
        <th style="width:10%">الإجمالي</th
        >
     <th style="width:13%">مدة التامين من</th>
        <th style="width:13%">مدة التأمين إلى</th>
        <th style="width:6%">عدد الأيام</th>
      </tr>
    </thead>
    <tbody>
